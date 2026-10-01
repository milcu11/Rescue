@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('breadcrumb')
  <li class="breadcrumb-item active">My Profile</li>
@endsection

@section('content')
  @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('status') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger" role="alert">
      <ul class="mb-0 pl-3">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row">
    <div class="col-xl-10">
      <div class="card card-outline card-danger">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">Account details</h3>
          <span class="badge badge-light">{{ str($user->role?->name ?? 'User')->title() }}</span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 text-center mb-4">
              <img
                src="{{ $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) : asset('assets/adminlte/dist/img/user2-160x160.jpg') }}"
                alt="Profile photo for {{ $user->name }}"
                class="img-circle img-thumbnail mb-3"
                style="width:130px;height:130px;object-fit:cover;"
              >
              <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group text-left">
                  <label for="profilePhoto" class="small font-weight-bold">Profile photo</label>
                  <input type="file" class="form-control-file" id="profilePhoto" name="profile_photo" accept="image/jpeg,image/png,image/webp" required>
                  <small class="form-text text-muted">JPG, PNG, or WebP, up to 2 MB.</small>
                </div>
                <button type="submit" class="btn btn-outline-danger btn-sm btn-block">
                  <i class="fas fa-camera mr-1"></i> Update photo
                </button>
              </form>
              <div class="small text-muted mt-3">Account status: <strong>{{ ucfirst($user->status) }}</strong></div>
            </div>

            <div class="col-md-9 border-left-md">
              <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="profileName">Full name</label>
                    <input type="text" class="form-control" id="profileName" name="name" value="{{ old('name', $user->name) }}" maxlength="255" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="profileEmail">Email address</label>
                    <input type="email" class="form-control" id="profileEmail" name="email" value="{{ old('email', $user->email) }}" maxlength="255" required>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="profilePhone">Contact phone</label>
                    <input type="tel" class="form-control" id="profilePhone" name="phone" value="{{ old('phone', $user->phone) }}" maxlength="50" autocomplete="tel">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="profileAge">Age</label>
                    <input type="number" class="form-control" id="profileAge" name="age" value="{{ old('age', $user->age) }}" min="15" max="120">
                  </div>
                </div>

                <div class="form-group">
                  <label for="profileAddress">Address / headquarters location</label>
                  <textarea class="form-control" id="profileAddress" name="address" rows="2" maxlength="2000">{{ old('address', $user->address) }}</textarea>
                </div>

                <hr>
                <h4 class="h6 font-weight-bold">Change password</h4>
                <p class="small text-muted">Leave blank to keep your current password. New passwords need at least 8 characters, a number, and a special character.</p>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="currentPassword">Current password</label>
                    <input type="password" class="form-control" id="currentPassword" name="current_password" autocomplete="current-password">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="newPassword">New password</label>
                    <input type="password" class="form-control" id="newPassword" name="new_password" autocomplete="new-password">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="newPasswordConfirmation">Confirm new password</label>
                    <input type="password" class="form-control" id="newPasswordConfirmation" name="new_password_confirmation" autocomplete="new-password">
                  </div>
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-danger px-4">
                    <i class="fas fa-save mr-1"></i> Save changes
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    @media (min-width: 768px) {
      .border-left-md { border-left: 1px solid #dee2e6; }
    }
  </style>
@endsection