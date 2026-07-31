<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationPaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_monetary_donation_can_show_payment_options(): void
    {
        $role = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $donation = Donation::create([
            'donor_name' => 'Juan Dela Cruz',
            'donor_email' => 'juan@example.com',
            'type' => 'monetary',
            'amount' => 250.50,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('donations.payment.create', $donation));

        $response->assertOk();
        $response->assertViewIs('donations.payment.create');
        $response->assertSee('Choose Payment Method');
    }
}
