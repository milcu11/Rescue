<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive. Contact the administrator.',
                ]);
            }

            if (!$user->email_verified_at) {
                Auth::logout();
                $this->issueVerificationCode($user);
                $request->session()->put('verify_user_id', $user->id);
                return redirect()->route('register.verify.show')
                    ->with('status', 'Please verify your email address. We sent you a new code.');
            }

            AuditService::login($user->name);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Incorrect email or password.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            AuditService::logout($user->name);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $donorRole = Role::where('slug', 'donor')->firstOrFail();

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $donorRole->id,
            'status'   => 'active',
        ]);

        AuditService::logForUser($user, 'register', 'users', $user->name, $user->id, null, null, 'Self-registered as donor');

        $this->issueVerificationCode($user);
        $request->session()->put('verify_user_id', $user->id);

        return redirect()->route('register.verify.show');
    }

    public function showVerify(Request $request)
    {
        $userId = $request->session()->get('verify_user_id');
        if (!$userId || !($user = User::find($userId)) || $user->email_verified_at) {
            return redirect()->route('login');
        }

        return view('auth.verify-code', ['email' => $user->email]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $userId = $request->session()->get('verify_user_id');
        $user = $userId ? User::find($userId) : null;

        if (!$user) {
            return redirect()->route('register')->withErrors(['code' => 'Your verification session expired. Please register again.']);
        }

        if (
            !$user->email_verification_code
            || $user->email_verification_code !== $request->code
            || !$user->email_verification_expires_at
            || $user->email_verification_expires_at->isPast()
        ) {
            return back()->withErrors(['code' => 'That code is invalid or has expired. Request a new one below.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_expires_at' => null,
        ]);

        $request->session()->forget('verify_user_id');

        Auth::login($user);
        $request->session()->regenerate();

        AuditService::logForUser($user, 'verify', 'users', $user->name, $user->id, null, null, 'Verified email address');

        return redirect()->route('dashboard')->with('status', 'Welcome to DRMS! Your donor account has been created.');
    }

    public function resendCode(Request $request)
    {
        $userId = $request->session()->get('verify_user_id');
        $user = $userId ? User::find($userId) : null;

        if (!$user || $user->email_verified_at) {
            return redirect()->route('login');
        }

        $this->issueVerificationCode($user);

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    protected function issueVerificationCode(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->update([
            'email_verification_code' => $code,
            'email_verification_expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($user, $code));
    }
}
