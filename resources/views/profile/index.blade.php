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

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h2 class="h3 font-weight-bold text-dark mb-1">My Profile</h2>
      <p class="text-muted small mb-0">Manage your account information, contact details, and organization overview.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm font-weight-bold mt-2 mt-sm-0">
      <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card card-outline card-danger">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title font-weight-bold">Account &amp; Leadership Details</h3>
          <span class="badge badge-light border px-2 py-1">{{ str($user->role?->name ?? 'User')->title() }}</span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 text-center mb-4 mb-md-0 border-right-md pr-md-4">
              <img
                src="{{ $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) : asset('assets/adminlte/dist/img/user2-160x160.jpg') }}"
                alt="Profile photo for {{ $user->name }}"
                class="img-circle img-thumbnail mb-3"
                style="width:90px;height:90px;object-fit:cover;"
              >
              <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-2 text-left">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="profilePhoto" name="profile_photo" accept="image/jpeg,image/png,image/webp" required>
                    <label class="custom-file-label text-left text-truncate small" for="profilePhoto">Change photo...</label>
                  </div>
                </div>
                <button type="submit" class="btn btn-outline-secondary btn-sm btn-block font-weight-bold">
                  <i class="fas fa-camera mr-1"></i> Update Photo
                </button>
              </form>
              <div class="small text-muted mt-3">Username: <strong>{{ \Illuminate\Support\Str::before($user->email, '@') }}</strong></div>
            </div>

            <div class="col-md-9 pl-md-4">
              <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="profileName" class="font-weight-bold small mb-1">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="profileName" name="name" value="{{ old('name', $user->name) }}" maxlength="255" required>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="profileUsername" class="font-weight-bold small mb-1">Username <small class="text-muted font-weight-normal">(email login)</small></label>
                    <input type="text" class="form-control" id="profileUsername" value="{{ \Illuminate\Support\Str::before($user->email, '@') }}" readonly>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="profileEmail" class="font-weight-bold small mb-1">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="profileEmail" name="email" value="{{ old('email', $user->email) }}" maxlength="255" required>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="profilePhone" class="font-weight-bold small mb-1">Contact Phone Number</label>
                    <input type="tel" class="form-control" id="profilePhone" name="phone" value="{{ old('phone', $user->phone) }}" maxlength="50" autocomplete="tel">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="profileAge" class="font-weight-bold small mb-1">Age</label>
                    <input type="number" class="form-control" id="profileAge" name="age" value="{{ old('age', $user->age) }}" min="15" max="120">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="newPassword" class="font-weight-bold small mb-1">Change Password <small class="text-muted font-weight-normal">(optional)</small></label>
                    <input type="password" class="form-control" id="newPassword" name="new_password" autocomplete="new-password" placeholder="New password">
                  </div>
                </div>

                <div class="form-group">
                  <label for="profileAddress" class="font-weight-bold small mb-1">Address / Headquarters Location</label>
                  <textarea class="form-control" id="profileAddress" name="address" rows="2" maxlength="2000">{{ old('address', $user->address) }}</textarea>
                </div>

                <div id="passwordConfirmationFields" class="form-row d-none">
                  <div class="form-group col-md-6">
                    <label for="currentPassword">Current password</label>
                    <input type="password" class="form-control" id="currentPassword" name="current_password" autocomplete="current-password">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="newPasswordConfirmation">Confirm new password</label>
                    <input type="password" class="form-control" id="newPasswordConfirmation" name="new_password_confirmation" autocomplete="new-password">
                  </div>
                  <small class="form-text text-muted col-12 mb-3">New passwords need at least 8 characters, a number, and a special character.</small>
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
      .border-right-md { border-right: 1px solid #dee2e6; }
    }
  </style>
  <script>
    document.getElementById('profilePhoto').addEventListener('change', function () {
      this.nextElementSibling.textContent = this.files[0] ? this.files[0].name : 'Change photo...';
    });

    document.getElementById('newPassword').addEventListener('input', function () {
      document.getElementById('passwordConfirmationFields').classList.toggle('d-none', !this.value);
    });
  </script>
@endsection