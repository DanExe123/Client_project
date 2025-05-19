<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasRole('admin')) {
            return redirect()->intended(route('admin.masterfiles.customer-master', absolute: false) . '?verified=1');
        }

        if ($request->user()->hasRole('user')) {
            return redirect()->intended(route('user.dashboard', absolute: false) . '?verified=1');
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');

    }
}
