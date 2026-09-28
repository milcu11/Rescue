@extends('layouts.app')

@section('title', 'Track Donation')
@section('page-title', 'Track a Donation')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('donor.index') }}">Donor Portal</a></li>
  <li class="breadcrumb-item active">Track a Donation</li>
@endsection

@section('content')
<style>
  .track-card { max-width: 680px; border: 0; border-radius: 14px; box-shadow: 0 10px 35px rgba(109,31,42,.1); }
  .track-card .card-header { background: #fff; border-bottom: 1px solid #eee; }
  .track-card .card-title { color: #6d1f2a; font-weight: 700; }
</style>
@include('donations._track-content')
@endsection
