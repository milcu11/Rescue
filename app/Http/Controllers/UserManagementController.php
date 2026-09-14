<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->when($request->filled('role'), fn($query) => $query->whereHas('role', fn($role) => $role->where('slug', $request->role)))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('users.form', ['user' => null, 'roles' => Role::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        AuditService::created('users', $user->name, $user->id, [
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'status' => $user->status,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.form', ['user' => $user, 'roles' => Role::orderBy('name')->get()]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($user->is(auth()->user()) && $data['status'] === 'inactive') {
            return back()->withInput()->withErrors(['status' => 'You cannot deactivate your own account.']);
        }

        $old = $user->only(['name', 'email', 'role_id', 'status']);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        $new = $user->fresh()->only(['name', 'email', 'role_id', 'status']);
        AuditService::updated('users', $user->name, $user->id, $old, $new);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors(['status' => 'You cannot change your own account status.']);
        }

        $old = ['status' => $user->status];
        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);

        AuditService::log(
            'status_changed',
            'users',
            $user->name,
            $user->id,
            $old,
            ['status' => $user->status]
        );

        return back()->with('success', 'User status updated successfully.');
    }
}
