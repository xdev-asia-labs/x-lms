<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class MemberAuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('member.auth.login');
    }

    /**
     * Handle member login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('member')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('member.dashboard'))
                ->with('success', 'Welcome back!');
        }

        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records.'),
        ]);
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('member.auth.register');
    }

    /**
     * Handle member registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:members'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $member = Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($member));

        Auth::guard('member')->login($member);

        return redirect()->route('member.verification.notice')
            ->with('success', 'Registration successful! Please verify your email.');
    }

    /**
     * Handle member logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('member')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the password reset request form.
     */
    public function showResetRequestForm()
    {
        return view('member.auth.forgot-password');
    }

    /**
     * Send password reset link.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::broker('members')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('member.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::broker('members')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($member) use ($request) {
                $member->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('member.login')
                ->with('success', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Show email verification notice.
     */
    public function showVerificationNotice()
    {
        return Auth::guard('member')->user()->hasVerifiedEmail()
            ? redirect()->route('member.dashboard')
            : view('member.auth.verify-email');
    }

    /**
     * Handle email verification.
     */
    public function verify(Request $request, string $id, string $hash)
    {
        $member = Member::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($member->getEmailForVerification()))) {
            throw ValidationException::withMessages([
                'email' => ['The verification link is invalid.'],
            ]);
        }

        if ($member->hasVerifiedEmail()) {
            return redirect()->route('member.dashboard')
                ->with('info', 'Your email is already verified.');
        }

        if ($member->markEmailAsVerified()) {
            event(new Verified($member));
        }

        return redirect()->route('member.dashboard')
            ->with('success', 'Your email has been verified!');
    }

    /**
     * Resend verification email.
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user('member')->hasVerifiedEmail()) {
            return redirect()->route('member.dashboard');
        }

        $request->user('member')->sendEmailVerificationNotification();

        return back()->with('success', 'A new verification link has been sent!');
    }
}
