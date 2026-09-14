@extends('layouts.app')
@section('title', $user ? 'Edit User' : 'Add User')
@section('page-title', $user ? 'Edit User' : 'Add User')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li><li class="breadcrumb-item active">{{ $user ? 'Edit' : 'Add' }}</li>@endsection
@section('content')
<div class="card"><div class="card-body"><form method="POST" action="{{ $user ? route('users.update', $user) : route('users.store') }}">@csrf @if($user) @method('PUT') @endif
  <div class="form-row"><div class="form-group col-md-6"><label for="name">Name</label><input id="name" name="name" class="form-control" value="{{ old('name', $user?->name) }}" required></div><div class="form-group col-md-6"><label for="email">Email</label><input id="email" type="email" name="email" class="form-control" value="{{ old('email', $user?->email) }}" required></div></div>
  <div class="form-row"><div class="form-group col-md-4"><label for="role_id">Role</label><select id="role_id" name="role_id" class="form-control" required>@foreach($roles as $role)<option value="{{ $role->id }}" @selected(old('role_id', $user?->role_id) == $role->id)>{{ $role->name }}</option>@endforeach</select></div><div class="form-group col-md-4"><label for="status">Status</label><select id="status" name="status" class="form-control" required><option value="active" @selected(old('status', $user?->status ?? 'active') === 'active')>Active</option><option value="inactive" @selected(old('status', $user?->status) === 'inactive')>Inactive</option></select></div></div>
  <div class="form-row"><div class="form-group col-md-6"><label for="password">Password @if($user)<small class="text-muted">(leave blank to keep current)</small>@endif</label><input id="password" type="password" name="password" class="form-control" {{ $user ? '' : 'required' }}></div><div class="form-group col-md-6"><label for="password_confirmation">Confirm Password</label><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" {{ $user ? '' : 'required' }}></div></div>
  @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
  <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a> <button class="btn btn-primary">{{ $user ? 'Update User' : 'Create User' }}</button>
</form></div></div>
@endsection
