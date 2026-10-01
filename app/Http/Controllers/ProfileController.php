<?php

namespace App\Http\Controllers;

use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.index', ['user' => auth()->user()->load('role')]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'age' => ['nullable', 'integer', 'min:15', 'max:120'],
            'address' => ['nullable', 'string', 'max:2000'],
            'current_password' => ['required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::min(8)->numbers()->symbols()],
        ]);

        $old = $user->only(['name', 'email', 'phone', 'age', 'address']);
        unset($data['current_password']);

        if (filled($data['new_password'] ?? null)) {
            $data['password'] = Hash::make($data['new_password']);
        }
        unset($data['new_password']);

        $user->update($data);

        AuditService::logForUser(
            $user,
            'updated',
            'profile',
            $user->name,
            $user->id,
            $old,
            $user->fresh()->only(['name', 'email', 'phone', 'age', 'address']),
            'Updated own profile'
        );

        return redirect()->route('profile')->with('status', 'Profile updated successfully.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $oldPath = $user->profile_photo_path;
        $path = $request->file('profile_photo')->storePublicly('profile-photos', 'public');
        $user->update(['profile_photo_path' => $path]);

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->route('profile')->with('status', 'Profile photo updated.');
    }
}