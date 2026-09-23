<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Create a donor account for the Disaster Response and Volunteer Matching System.">
    <title>Register | DRMS</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo/baras_seal_l.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root { --drms-login-red: #c62828; --drms-login-red-dark: #6d1f2a; --drms-login-burgundy: #3d1419; --drms-login-soft: #fdeaea; }
        * { box-sizing: border-box; }
        body.drms-login-page { min-height: 100vh; margin: 0; background: var(--drms-login-burgundy); font-family: 'DM Sans', 'Segoe UI', system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        .drms-login-wrap { display: flex; min-height: 100vh; }
        .drms-login-brand { position: relative; display: flex; flex: 1 1 42%; flex-direction: column; justify-content: center; overflow: hidden; padding: 3rem 3.5rem; color: #fff; background: linear-gradient(145deg, var(--drms-login-burgundy) 0%, var(--drms-login-red-dark) 45%, #8b2635 100%); }
        .drms-login-brand::before { position: absolute; inset: 0; background: radial-gradient(ellipse 70% 50% at 100% 0%, rgba(255, 255, 255, 0.1), transparent 55%), radial-gradient(ellipse 50% 40% at 0% 100%, rgba(0, 0, 0, 0.2), transparent 50%); content: ''; pointer-events: none; }
        .drms-login-brand-inner { position: relative; z-index: 1; max-width: 420px; }
        .drms-login-logo { display: flex; width: 96px; height: 100px; align-items: center; justify-content: center; margin-bottom: 1.5rem; background: transparent; }
        .drms-login-logo img { width: 96px; height: 100px; object-fit: contain; filter: drop-shadow(0 5px 12px rgba(0, 0, 0, 0.28)); }
        .drms-login-brand h1 { margin: 0 0 0.75rem; font-size: 2rem; font-weight: 700; letter-spacing: -0.03em; }
        .drms-login-brand .badge { margin-bottom: 0.5rem; padding: 0.4rem 0.8rem; color: var(--drms-login-burgundy); font-size: 0.78rem; font-weight: 700; }
        .tagline { margin: 0 0 2rem; color: rgba(255, 235, 235, 0.88); font-size: 1.05rem; line-height: 1.5; }
        .area-badge { display: inline-block; align-self: flex-start; margin-bottom: 2rem; padding: 0.4rem 0.9rem; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 999px; background: rgba(255, 255, 255, 0.12); font-size: 0.85rem; }
        .drms-login-features { margin: 0; padding: 0; list-style: none; }
        .drms-login-features li { padding: 0.35rem 0; color: rgba(255, 230, 230, 0.85); font-size: 0.9rem; }
        .drms-login-features i { width: 1.25rem; margin-right: 0.5rem; color: #ef9a9a; }
        .drms-login-back { margin-top: 2.5rem; font-size: 0.9rem; }
        .drms-login-back a { color: #ffcdd2; text-decoration: none; }
        .drms-login-back a:hover { color: #fff; text-decoration: underline; }
        .drms-login-main { position: relative; display: flex; flex: 1 1 58%; align-items: center; justify-content: center; padding: 2rem 1.5rem; background-color: #2b1115; background-image: linear-gradient(rgba(43, 17, 21, 0.68), rgba(20, 6, 9, 0.82)), url('{{ asset('assets/logo/472849389_1012327994260123_290553008324288451_n.jpg') }}'); background-position: center; background-size: cover; }
        .drms-login-card { width: 100%; max-width: 440px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.18); border-radius: 16px; color: #fff; background: rgba(38, 13, 17, 0.90); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.55); backdrop-filter: blur(14px); }
        .drms-login-card-header { padding: 1.75rem 2rem 0; }
        .drms-login-card-header h2 { margin: 0 0 0.25rem; color: #fff; font-size: 1.5rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); }
        .drms-login-card-header p { margin: 0; color: rgba(255, 230, 230, 0.85); font-size: 0.9rem; }
        .drms-login-card-body { padding: 1.5rem 2rem 2rem; }
        .drms-login-card .form-group label { color: #ffcdd2; font-size: 0.85rem; font-weight: 600; }
        .drms-login-card .input-group-text { min-width: 44px; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.25); border-right: 0; border-radius: 10px 0 0 10px; color: var(--drms-login-red-dark); background: #fff; }
        .drms-login-card .form-control { height: auto; padding: 0.65rem 0.9rem; border: 1px solid rgba(255, 255, 255, 0.25); border-left: 0; border-radius: 0 10px 10px 0; color: #210b0e; background: #fff; font-weight: 500; }
        .drms-login-card .form-control:focus { border-color: #ff8a80; box-shadow: 0 0 0 0.2rem rgba(255, 138, 128, 0.25); }
        .btn-drms-login { width: 100%; margin-top: 0.5rem; padding: 0.7rem 1rem; border: 1px solid #b71c1c; border-radius: 10px; color: #fff; background: var(--drms-login-red); box-shadow: 0 4px 14px rgba(198, 40, 40, 0.4); font-weight: 600; }
        .btn-drms-login:hover, .btn-drms-login:focus { color: #fff; border-color: var(--drms-login-red); background: #e53935; box-shadow: 0 6px 18px rgba(229, 57, 53, 0.5); }
        .drms-login-footer-links { margin: 1rem 0 0; color: rgba(255, 230, 230, 0.85); font-size: 0.85rem; text-align: center; }
        @media (max-width: 991px) { .drms-login-wrap { flex-direction: column; } .drms-login-brand { flex: none; padding: 2rem 1.5rem; text-align: center; } .drms-login-brand-inner { max-width: none; } .drms-login-logo { margin-right: auto; margin-left: auto; } .drms-login-features { display: none; } .drms-login-back { margin-top: 1rem; } .area-badge { align-self: center; } }
    </style>
</head>
<body class="drms-login-page">
    <div class="drms-login-wrap">
        <aside class="drms-login-brand">
            <div class="drms-login-brand-inner">
                <div class="drms-login-logo"><img src="{{ asset('assets/logo/baras_seal_l.png') }}" alt="Municipality of Baras seal"></div>
                <h1>DRMS</h1>
                <span class="badge badge-light">Disaster Response &amp; Volunteer Matching System</span>
                <p class="tagline">Create a donor account to support relief operations and track your donations online.</p>
                <span class="area-badge"><i class="fas fa-map-marker-alt mr-1"></i> Municipality of Baras, Rizal</span>
                <ul class="drms-login-features">
                    <li><i class="fas fa-check-circle"></i> Donate cash or in-kind items securely</li>
                    <li><i class="fas fa-check-circle"></i> Track the status of every donation</li>
                    <li><i class="fas fa-check-circle"></i> Get notified when relief is distributed</li>
                </ul>
                <p class="drms-login-back mb-0"><a href="{{ route('home') }}"><i class="fas fa-arrow-left mr-1"></i> Back to public homepage</a></p>
            </div>
        </aside>
        <main class="drms-login-main">
            <section class="drms-login-card" aria-labelledby="register-title">
                <div class="drms-login-card-header"><h2 id="register-title">Register</h2><p>Create a donor account</p></div>
                <div class="drms-login-card-body">
                    @if($errors->any())
                        <div class="alert alert-danger small" role="alert">
                            <ul class="mb-0 pl-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register.post') }}" autocomplete="on" id="register-form">
                        @csrf
                        <div class="form-group">
                            <label for="registerName">Full name</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="registerName" name="name" autocomplete="name" placeholder="Enter your full name" value="{{ old('name') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="registerEmail">Email address</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="registerEmail" name="email" autocomplete="username" placeholder="Enter your email address" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="registerPassword">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="registerPassword" name="password" autocomplete="new-password" placeholder="At least 8 characters" required>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="registerPasswordConfirm">Confirm password</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                <input type="password" class="form-control" id="registerPasswordConfirm" name="password_confirmation" autocomplete="new-password" placeholder="Re-enter your password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-drms-login"><i class="fas fa-user-plus mr-1"></i> Create account</button>
                    </form>
                    <p class="drms-login-footer-links mb-0">Already have an account? <a href="{{ route('login') }}" class="font-weight-bold" style="color:#ffcdd2;">Login</a></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
