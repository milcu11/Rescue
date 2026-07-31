@extends('layouts.app')

@section('title', 'Donor Portal')
@section('page-title', 'Donor Portal')

@section('breadcrumb')
  <li class="breadcrumb-item active">Donor Portal</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-8">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Welcome, {{ $user->name }}</h3>
        </div>
        <div class="card-body">
          <p class="lead">This is your donor portal.</p>
          <p>
            You can track your donations, review donation receipts, and keep your contact details up to date.
          </p>
          <p>If you need assistance, please contact the RescuePH team.</p>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Donor Details</h3>
        </div>
        <div class="card-body">
          <p><strong>Name</strong><br>{{ $user->name }}</p>
          <p><strong>Email</strong><br>{{ $user->email }}</p>
          <p><strong>Role</strong><br>{{ ucfirst(str_replace('_', ' ', $user->role->slug)) }}</p>
        </div>
      </div>
    </div>
  </div>
@endsection
