@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('breadcrumb')<li class="breadcrumb-item active">Users</li>@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">System Users</h4>
  <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-user-plus mr-1"></i>Add User</a>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="GET" class="card mb-3"><div class="card-body"><div class="form-row align-items-end">
  <div class="form-group col-md-4 mb-0"><label for="user-role">Role</label><select id="user-role" name="role" class="form-control"><option value="">All roles</option>@foreach($roles as $role)<option value="{{ $role->slug }}" @selected(request('role') === $role->slug)>{{ $role->name }}</option>@endforeach</select></div>
  <div class="form-group col-md-4 mb-0"><label for="user-status">Status</label><select id="user-status" name="status" class="form-control"><option value="">All statuses</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="inactive" @selected(request('status') === 'inactive')>Inactive</option></select></div>
  <div class="form-group col-md-4 mb-0"><button class="btn btn-outline-primary">Filter</button> <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Clear</a></div>
</div></div></form>
<div class="card"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th><th class="text-right">Actions</th></tr></thead><tbody>
@forelse($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->role?->name ?? '—' }}</td><td><span class="badge badge-{{ $user->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($user->status) }}</span></td><td>{{ $user->created_at?->format('M d, Y') }}</td><td class="text-right"><a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit user"><i class="fas fa-edit"></i></a>@if(!$user->is(auth()->user()))<form method="POST" action="{{ route('users.toggle-status', $user) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-{{ $user->status === 'active' ? 'warning' : 'success' }}" title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}"><i class="fas fa-user-{{ $user->status === 'active' ? 'slash' : 'check' }}"></i></button></form>@endif</td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No users found.</td></tr>@endforelse
</tbody></table></div><div class="card-footer">{{ $users->links() }}</div></div>
@endsection
