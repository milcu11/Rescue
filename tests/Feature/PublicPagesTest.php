<?php

namespace Tests\Feature;

use App\Models\EvacuationCenter;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
        ]);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'role' => 'super_admin',
        ]);

        EvacuationCenter::create([
            'name' => 'San Juan Evacuation',
            'barangay' => 'San Juan',
            'address' => 'Baras Rizal',
            'capacity' => 50,
            'current_occupancy' => 3,
            'status' => 'open',
            'contact_person' => 'MDRRMO Head',
            'contact_phone' => '09123456789',
            'latitude' => 14.5257,
            'longitude' => 121.2661,
            'created_by' => $user->id,
        ]);
    }
    public function test_site_root_shows_login_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sign in to start your session');
    }

    public function test_public_donate_page_is_accessible(): void
    {
        $response = $this->get('/donate');

        $response->assertStatus(200);
        $response->assertSee('Donate');
    }

    public function test_login_page_does_not_show_public_donation_links(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('Donate to RescuePH');
        $response->assertDontSee('Track an existing donation');
    }

    public function test_evac_centers_page_is_accessible(): void
    {
        $response = $this->get('/evac-centers');

        $response->assertStatus(200);
        $response->assertSee('Evacuation centers — Municipality of Baras');
        $response->assertSee('Open centers on map');
        $response->assertSee('Register family at center');
        $response->assertSee('Check in with QR token');
    }

    public function test_captcha_image_returns_svg(): void
    {
        $response = $this->get('/captcha/image');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/svg+xml');
    }

    public function test_nearest_evac_endpoint_returns_json(): void
    {
        $response = $this->getJson('/api/evac/nearest?lat=14.5171&lng=121.2672');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'nearest',
        ]);
    }

    public function test_family_registration_and_checkin_flow(): void
    {
        $center = EvacuationCenter::first();

        // Simulate captcha in session
        $response = $this->withSession(['captcha_code' => 'ABCDE'])
            ->post('/evac-centers/register-family', [
                'evacuation_center_id' => $center->id,
                'family_head_name' => 'Juan Dela Cruz',
                'members_count' => 4,
                'medical_needs' => 'Hypertension',
                'contact_phone' => '09123456789',
                'captcha_code' => 'ABCDE',
            ]);

        $response->assertRedirect('/evac-centers');
        $this->assertDatabaseHas('evacuees', [
            'name' => 'Juan Dela Cruz',
            'family_members' => 4,
            'status' => 'registered',
        ]);

        $evacuee = \App\Models\Evacuee::where('name', 'Juan Dela Cruz')->first();
        $this->assertNotNull($evacuee->family_qr_token);
        $this->assertStringStartsWith('FAM-', $evacuee->family_qr_token);

        // Check-in via token
        $checkinResponse = $this->postJson('/evac-centers/check-in-family', [
            'family_qr_token' => $evacuee->family_qr_token,
        ]);

        $checkinResponse->assertStatus(200);
        $checkinResponse->assertJson([
            'status' => 'ok',
        ]);

        $this->assertEquals('checked_in', $evacuee->fresh()->status);
    }
}
