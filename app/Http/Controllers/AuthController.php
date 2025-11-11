<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use Illuminate\Auth\Events\Registered;



class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login with credentials
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            
            // CRITICAL: Check if email is verified
            $user = Auth::user();
            
            if (!$user->hasVerifiedEmail()) {
                // Logout immediately
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Return with error
                return back()->withErrors([
                    'email' => 'Your email address is not verified. Please check your email for the verification link.',
                ])->withInput($request->only('email'));
            }

            // Email verified - proceed with login
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Welcome back!');
        }

        // Invalid credentials
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //Auth::login($user);

        // Trigger email verification notification
        event(new Registered($user));

        //return redirect('/dashboard')->with('success', 'Registration successful!');
        
        // DO NOT login the user - redirect to login with message
        return redirect()->route('login')->with('success', 'Registration successful! Please check your email to verify your account before logging in.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Logged out successfully!');
    }

    // Show forgot password form
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Send password reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // Show reset password form
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // Show confirm password form
    public function showConfirmPassword()
    {
        return view('auth.confirm-password');
    }

    // Handle password confirmation
    public function confirmPassword(Request $request)
    {
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended();
    }

    // Show verify email notice
    public function showVerifyEmail()
    {
        return view('auth.verify-email');
    }

    // Send verification email
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    // Handle email verification
    public function verifyEmail(Request $request)
    {
        // Check if user is logged in (shouldn't be, but just in case)
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard?verified=1');
        }

        // Find user by ID from the URL
        $user = User::findOrFail($request->route('id'));

        // Verify the hash matches
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        // Mark email as verified
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'Email verified successfully! You can now log in.');
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $user = User::where('email', $request->email)->first();
        
        if ($user->hasVerifiedEmail()) {
            return back()->with('success', 'Email is already verified. You can log in now.');
        }
        
        $user->sendEmailVerificationNotification();
        
        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }

}