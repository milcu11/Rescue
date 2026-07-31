@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('breadcrumb')
  <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Recent Notifications</h5>
      <form method="POST" action="{{ route('notifications.markAllRead') }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary">
          Mark all as read
        </button>
      </form>
    </div>
    <div class="card-body p-0">
      @if($notifications->isEmpty())
        <div class="p-4 text-center text-secondary">
          <i class="bi bi-bell-slash fs-1"></i>
          <p class="mt-3 mb-0">No notifications yet.</p>
        </div>
      @else
        <div class="list-group list-group-flush">
          @foreach($notifications as $notification)
            <a href="{{ $notification->link ?? route('notifications.index') }}"
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ $notification->is_read ? '' : 'bg-light' }}">
              <div>
                <div class="fw-semibold">{{ $notification->title }}</div>
                <div class="small text-muted">{{ $notification->message }}</div>
              </div>
              <span class="badge rounded-pill {{ $notification->is_read ? 'bg-secondary' : 'bg-danger' }}">
                {{ $notification->is_read ? 'Read' : 'New' }}
              </span>
            </a>
          @endforeach
        </div>
      @endif
    </div>
    <div class="card-footer text-muted">
      Showing {{ $notifications->count() }} of {{ $notifications->total() }} notifications.
    </div>
  </div>
@endsection
