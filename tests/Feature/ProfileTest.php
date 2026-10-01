<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function profileUser(): User
    {
        $role = Role::create(['name' => 'Donor', 'slug' => 'donor']);

        return User::create([
            'name' => 'Profile User',
            'email' => 'profile@example.com',
            'password' => Hash::make('OriginalPass1!'),
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    public function test_authenticated_user_can_view_and_update_their_profile(): void
    {
        $user = $this->profileUser();

        $this->actingAs($user)->get(route('profile'))->assertOk()->assertSee('Account details');

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '09170000000',
            'age' => 30,
            'address' => 'Baras, Rizal',
        ])->assertRedirect(route('profile'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '09170000000',
            'age' => 30,
            'address' => 'Baras, Rizal',
        ]);
    }

    public function test_profile_password_change_requires_current_password_and_strong_new_password(): void
    {
        $user = $this->profileUser();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'OriginalPass1!',
            'new_password' => 'StrongPass2!',
            'new_password_confirmation' => 'StrongPass2!',
        ]);

        $response->assertRedirect(route('profile'));
        $this->assertTrue(Hash::check('StrongPass2!', $user->fresh()->password));
    }

    public function test_user_can_upload_a_profile_photo(): void
    {
        Storage::fake('public');
        $user = $this->profileUser();

        $this->actingAs($user)->post(route('profile.photo'), [
            'profile_photo' => UploadedFile::fake()->create('profile.png', 100, 'image/png'),
        ])->assertRedirect(route('profile'));

        $this->assertNotNull($user->fresh()->profile_photo_path);
        Storage::disk('public')->assertExists($user->fresh()->profile_photo_path);
    }
}