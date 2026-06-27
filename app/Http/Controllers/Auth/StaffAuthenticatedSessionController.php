<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StaffAuthenticatedSessionController extends Controller
{
    /**
     * Display the staff login view.
     */
    public function create(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Handle an incoming staff authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isOfficer()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This portal is for NEMA staff only. Citizens should use the public login.',
            ]);
        }

        return redirect()->intended(route('officer.dashboard'));
    }
}
