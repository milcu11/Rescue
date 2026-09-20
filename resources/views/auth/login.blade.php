<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Staff access for the Disaster Response and Volunteer Matching System.">
    <title>Login | DRMS</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo/baras_seal_l.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root { --drms-red: #c62828; --drms-burgundy: #6d1f2a; --drms-burgundy-deep: #3d1419; --drms-ink: #2c1819; --drms-muted: #735f62; }
        * { box-sizing: border-box; }
        body.drms-login-page { min-height: 100vh; margin: 0; color: var(--drms-ink); background: #f9f5f4; font-family: 'DM Sans', sans-serif; }
        .drms-login-wrap { display: grid; min-height: 100vh; grid-template-columns: minmax(0, 1.15fr) minmax(420px, 0.85fr); }
        .drms-login-brand { position: relative; display: flex; align-items: center; overflow: hidden; padding: 4rem clamp(2rem, 7vw, 7rem); color: #fff; background-image: linear-gradient(90deg, rgba(61, 20, 25, 0.94) 0%, rgba(109, 31, 42, 0.86) 47%, rgba(61, 20, 25, 0.45) 100%), url('{{ asset('assets/logo/472849389_1012327994260123_290553008324288451_n.jpg') }}'); background-position: center; background-size: cover; }
        .drms-login-brand::after { position: absolute; right: -16rem; bottom: -20rem; width: 42rem; height: 42rem; border: 1px solid rgba(255, 255, 255, 0.18); border-radius: 50%; box-shadow: 0 0 0 3rem rgba(255, 255, 255, 0.04), 0 0 0 6rem rgba(255, 255, 255, 0.035); content: ''; }
        .drms-login-brand-inner { position: relative; z-index: 1; max-width: 42rem; }
        .drms-login-logo { margin-bottom: 1.5rem; }
        .drms-login-logo img { width: 108px; height: 112px; object-fit: contain; }
        .drms-login-brand h1 { margin: 0 0 0.5rem; font-size: 3.2rem; font-weight: 700; letter-spacing: 0; }
        .drms-login-brand .badge { padding: 0.45rem 0.8rem; color: var(--drms-burgundy-deep); font-size: 0.78rem; font-weight: 700; }
        .tagline { max-width: 35rem; margin: 1.1rem 0 1.4rem; color: rgba(255, 255, 255, 0.85); font-size: 1.1rem; line-height: 1.65; }
        .area-badge { display: inline-block; padding: 0.5rem 0.75rem; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 4px; background: rgba(0, 0, 0, 0.12); font-size: 0.9rem; }
        .drms-login-features { margin: 2rem 0; padding: 0; list-style: none; }
        .drms-login-features li { margin: 0.8rem 0; color: rgba(255, 255, 255, 0.92); }
        .drms-login-features i { width: 1.5rem; color: #ffd5d5; }
        .drms-login-back a { color: rgba(255, 255, 255, 0.88); font-size: 0.9rem; text-decoration: none; }
        .drms-login-back a:hover { color: #fff; text-decoration: underline; }
        .drms-login-main { display: flex; align-items: center; justify-content: center; padding: 2rem; background: #fff; }
        .drms-login-card { width: 100%; max-width: 430px; overflow: hidden; border: 1px solid #eee4e3; border-radius: 8px; box-shadow: 0 16px 45px rgba(61, 20, 25, 0.12); }
        .drms-login-card-header { padding: 1.75rem 2rem 1.25rem; color: #fff; background: linear-gradient(125deg, var(--drms-burgundy-deep), var(--drms-burgundy)); }
        .drms-login-card-header h2 { margin: 0 0 0.2rem; font-size: 1.55rem; font-weight: 700; }
        .drms-login-card-header p { margin: 0; color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; }
        .drms-login-card-body { padding: 2rem; }
        .drms-login-card label { color: #4d3538; font-size: 0.9rem; font-weight: 600; }
        .drms-login-card .input-group-text { min-width: 44px; justify-content: center; border-color: #ded0d0; border-right: 0; color: var(--drms-burgundy); background: #fffafa; }
        .drms-login-card .form-control { height: calc(1.5em + 1rem + 2px); border-color: #ded0d0; border-left: 0; font-size: 0.95rem; }
        .drms-login-card .form-control:focus { border-color: var(--drms-red); box-shadow: 0 0 0 0.2rem rgba(198, 40, 40, 0.12); }
        .drms-login-card .form-check-label { color: var(--drms-muted); font-size: 0.85rem; font-weight: 400; }
        .btn-drms-login { width: 100%; padding: 0.7rem 1rem; border: 0; border-radius: 4px; color: #fff; background: var(--drms-red); font-weight: 700; transition: background 0.15s ease, transform 0.15s ease; }
        .btn-drms-login:hover, .btn-drms-login:focus { color: #fff; background: #a51f22; transform: translateY(-1px); }
        .drms-login-footer-links { margin: 1.35rem 0 0; color: var(--drms-muted); font-size: 0.86rem; text-align: center; }
        .drms-login-loader-overlay { position: fixed; z-index: 1050; inset: 0; align-items: center; justify-content: center; background: rgba(40, 11, 15, 0.87); }
        .drms-login-loader-overlay.is-active { display: flex !important; }
        .drms-login-loader-content { color: #fff; }
        .drms-login-loader-content img { width: 82px; height: 86px; object-fit: contain; }
        .drms-pulse-logo { animation: pulse 1.35s ease-in-out infinite alternate; }
        @keyframes pulse { from { transform: scale(0.95); opacity: 0.8; } to { transform: scale(1.05); opacity: 1; } }
        @media (max-width: 991.98px) { .drms-login-wrap { grid-template-columns: 1fr; } .drms-login-brand { min-height: auto; padding: 2.75rem 2rem; } .drms-login-logo, .drms-login-features { display: none; } .drms-login-brand h1 { font-size: 2.25rem; } .tagline { margin-bottom: 1rem; font-size: 1rem; } .drms-login-main { padding: 2rem 1rem; } }
    </style>
</head>
<body class="drms-login-page">
    <div class="drms-login-wrap">
        <aside class="drms-login-brand">
            <div class="drms-login-brand-inner">
                <div class="drms-login-logo"><img src="{{ asset('assets/logo/baras_seal_l.png') }}" alt="Municipality of Baras seal"></div>
                <h1>DRMS</h1>
                <span class="badge badge-light">Disaster Response &amp; Volunteer Matching System</span>
                <p class="tagline">Unified disaster response for LGUs — incidents, volunteers, relief, evacuation, and accountable operations.</p>
                <span class="area-badge"><i class="fas fa-map-marker-alt mr-1"></i> Municipality of Baras, Rizal</span>
                <ul class="drms-login-features">
                    <li><i class="fas fa-check-circle"></i> Incident reporting and verification</li>
                    <li><i class="fas fa-check-circle"></i> Volunteer matching and deployment</li>
                    <li><i class="fas fa-check-circle"></i> Relief, evacuation, and operations analytics</li>
                </ul>
                <p class="drms-login-back mb-0"><a href="{{ route('home') }}"><i class="fas fa-arrow-left mr-1"></i> Back to public homepage</a></p>
            </div>
        </aside>
        <main class="drms-login-main">
            <section class="drms-login-card" aria-labelledby="login-title">
                <div class="drms-login-card-header"><h2 id="login-title">Login</h2><p>Staff access to the operations dashboard</p></div>
                <div class="drms-login-card-body">
                    @if($errors->any())<div class="alert alert-danger small" role="alert">{{ $errors->first() }}</div>@endif
                    @if(session('status'))<div class="alert alert-success small" role="alert">{{ session('status') }}</div>@endif
                    <form method="POST" action="{{ route('login.post') }}" autocomplete="on" id="login-form">
                        @csrf
                        <div class="form-group">
                            <label for="loginEmail">Email address</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="loginEmail" name="email" autocomplete="username" placeholder="Enter your email address" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="loginPassword">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="loginPassword" name="password" autocomplete="current-password" placeholder="Enter your password" required>
                            </div>
                            @error('password')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group form-check mb-4">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberMe" value="1" @checked(old('remember'))>
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-drms-login"><i class="fas fa-sign-in-alt mr-1"></i> Login</button>
                    </form>
                    <p class="drms-login-footer-links">Need help with access? Contact your DRMS administrator.</p>
                </div>
            </section>
        </main>
    </div>
    <div id="loginLoaderOverlay" class="drms-login-loader-overlay" style="display: none;" aria-live="polite">
        <div class="drms-login-loader-content text-center">
            <img src="{{ asset('assets/logo/baras_seal_l.png') }}" alt="" class="mb-3 drms-pulse-logo">
            <div class="spinner-border text-light mb-3 d-block mx-auto" role="status"><span class="sr-only">Loading</span></div>
            <h4 class="font-weight-bold mb-1">Authenticating...</h4>
            <p class="text-white-50 small mb-0">Preparing your DRMS operations dashboard</p>
        </div>
    </div>
    <script>
        document.getElementById('login-form').addEventListener('submit', function () {
            if (this.checkValidity()) document.getElementById('loginLoaderOverlay').classList.add('is-active');
        });
    </script>
</body>
</html>