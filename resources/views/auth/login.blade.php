<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RescuePH | Sign In</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
  <style>
    .login-page  { background: #f4f6f9; }
    .login-logo a { color: #7B1113; }
    .login-logo a b { color: #7B1113; }
    .login-logo small {
      display: block;
      font-size: .85rem;
      font-weight: 300;
      color: #888;
      margin-top: -4px;
    }
    .btn-block.btn-danger {
      background-color: #7B1113;
      border-color:     #7B1113;
    }
    .btn-block.btn-danger:hover {
      background-color: #9B1416;
      border-color:     #9B1416;
    }
    .dev-box {
      margin-top: 14px;
      padding-top: 12px;
      border-top: 1px solid #eee;
      text-align: center;
    }
    .dev-box p { font-size: 11px; color: #aaa; margin-bottom: 6px; }
    .dev-btn {
      display: inline-block;
      font-size: 11px;
      padding: 3px 9px;
      margin: 2px;
      border: 1px solid #ddd;
      border-radius: 4px;
      background: #fff;
      cursor: pointer;
      color: #555;
    }
    .dev-btn:hover { background: #f0f0f0; }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="#">
      <b>Rescue</b>PH
      <small>Disaster Relief Operations Management</small>
    </a>
  </div>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>{{ $errors->first() }}</strong>
        </div>
      @endif

      @if(session('status'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" autofocus>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>
        @error('email')
          <span class="text-danger">{{ $message }}</span>
        @enderror

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>
        @error('password')
          <span class="text-danger">{{ $message }}</span>
        @enderror

        <div class="row mb-3">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Remember Me</label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-danger btn-block">Sign In</button>
          </div>
        </div>
      </form>

      <div class="dev-box">
        <p>Dev accounts | password: <code>password</code></p>
        <button type="button" class="dev-btn" onclick="fill('admin@resqlink.ph')">Super Admin</button>
        <button type="button" class="dev-btn" onclick="fill('drrm@resqlink.ph')">DRRM Officer</button>
        <button type="button" class="dev-btn" onclick="fill('warehouse@resqlink.ph')">Warehouse</button>
        <button type="button" class="dev-btn" onclick="fill('evac@resqlink.ph')">Evac Manager</button>
        <button type="button" class="dev-btn" onclick="fill('donor@resqlink.ph')">Donor</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script>
  function fill(email) {
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = 'password';
  }
</script>
</body>
</html>
