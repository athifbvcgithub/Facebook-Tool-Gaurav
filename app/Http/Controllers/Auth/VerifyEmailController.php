<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Http\Request;  // ← Change this

class VerifyEmailController extends Controller
{   

    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse  // ← Change this
    {
        // Now dd() will work!
        // dd($request->all(), $request->route('id'), $request->route('hash'));
        
        // Get parameters from URL
        $userId = $request->route('id');
        $hash = $request->route('hash');
        
        // Find user by ID
        $user = User::findOrFail($userId);
        
        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('success', 'Email already verified! You can log in now.');
        }
        
        // Verify hash matches
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Invalid verification link.']);
        }
        
        // Verify signature (security check)
        if (!$request->hasValidSignature()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Verification link has expired.']);
        }
        
        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now log in.');
    }



    /**
     * Mark the authenticated user's email address as verified.
     */
    /* public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        dd($request->all());
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    } */
}
