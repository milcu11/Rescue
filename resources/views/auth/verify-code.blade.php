<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Verify your email to finish creating your DRMS donor account.">
    <title>Verify Email | DRMS</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo/baras_seal_l.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root { --drms-login-red: #c62828; --drms-login-red-dark: #6d1f2a; --drms-login-burgundy: #3d1419; --drms-login-soft: #fdeaea; }
        * { box-sizing: border-box; }
        body.drms-login-page { min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center; background-color: #2b1115; background-image: linear-gradient(rgba(43, 17, 21, 0.68), rgba(20, 6, 9, 0.82)), url('{{ asset('assets/logo/472849389_1012327994260123_290553008324288451_n.jpg') }}'); background-position: center; background-size: cover; font-family: 'DM Sans', 'Segoe UI', system-ui, sans-serif; -webkit-font-smoothing: antialiased; padding: 2rem 1.5rem; }
        .drms-login-card { width: 100%; max-width: 440px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.18); border-radius: 16px; color: #fff; background: rgba(38, 13, 17, 0.90); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.55); backdrop-filter: blur(14px); }
        .drms-login-card-header { padding: 1.75rem 2rem 0; }
        .drms-login-card-header h2 { margin: 0 0 0.25rem; color: #fff; font-size: 1.5rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); }
        .drms-login-card-header p { margin: 0; color: rgba(255, 230, 230, 0.85); font-size: 0.9rem; }
        .drms-login-card-body { padding: 1.5rem 2rem 2rem; }
        .drms-login-card .form-group label { color: #ffcdd2; font-size: 0.85rem; font-weight: 600; }
        .drms-login-card .form-control { height: auto; padding: 0.65rem 0.9rem; border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 10px; color: #210b0e; background: #fff; font-weight: 500; text-align: center; letter-spacing: 6px; font-size: 1.3rem; }
        .drms-login-card .form-control:focus { border-color: #ff8a80; box-shadow: 0 0 0 0.2rem rgba(255, 138, 128, 0.25); }
        .btn-drms-login { width: 100%; margin-top: 0.5rem; padding: 0.7rem 1rem; border: 1px solid #b71c1c; border-radius: 10px; color: #fff; background: var(--drms-login-red); box-shadow: 0 4px 14px rgba(198, 40, 40, 0.4); font-weight: 600; }
        .btn-drms-login:hover, .btn-drms-login:focus { color: #fff; border-color: var(--drms-login-red); background: #e53935; box-shadow: 0 6px 18px rgba(229, 57, 53, 0.5); }
        .drms-login-footer-links { margin: 1rem 0 0; color: rgba(255, 230, 230, 0.85); font-size: 0.85rem; text-align: center; }
        .btn-link-resend { color: #ffcdd2; text-decoration: none; background: none; border: 0; padding: 0; font-weight: 700; }
        .btn-link-resend:hover { color: #fff; text-decoration: underline; }
    </style>
</head>
<body class="drms-login-page">
    <section class="drms-login-card" aria-labelledby="verify-title">
        <div class="drms-login-card-header">
            <h2 id="verify-title">Verify your email</h2>
            <p>Enter the 6-digit code we sent to <strong>{{ $email }}</strong></p>
        </div>
        <div class="drms-login-card-body">
            @if($errors->any())
                <div class="alert alert-danger small" role="alert">{{ $errors->first() }}</div>
            @endif
            @if(session('status'))
                <div class="alert alert-success small" role="alert">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('register.verify.post') }}">
                @csrf
                <div class="form-group">
                    <label for="verifyCode">Verification code</label>
                    <input type="text" inputmode="numeric" maxlength="6" pattern="\d{6}" class="form-control" id="verifyCode" name="code" placeholder="------" required autofocus>
                </div>
                <button type="submit" class="btn btn-drms-login"><i class="fas fa-check-circle mr-1"></i> Verify & continue</button>
            </form>
            <form method="POST" action="{{ route('register.verify.resend') }}" class="mt-3 text-center">
                @csrf
                <span class="drms-login-footer-links mb-0 d-inline">Didn't get a code?</span>
                <button type="submit" class="btn-link-resend">Resend code</button>
            </form>
        </div>
    </section>
</body>
</html>
