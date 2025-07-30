<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check user status
        if (Auth::user()->status !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact administrator.',
            ]);
        }

        // Redirect based on user role
        $user = Auth::user();
        
        if ($user->hasRole('Admin')) {
            return redirect()->intended(RouteServiceProvider::HOME);
        } elseif ($user->hasRole('Manager')) {
            return redirect()->intended('/dashboard');
        } elseif ($user->hasRole('Salesman')) {
            return redirect()->intended('/pos');
        } elseif ($user->hasRole('Accountant')) {
            return redirect()->intended('/reports');
        } elseif ($user->hasRole('Inventory Manager')) {
            return redirect()->intended('/inventory');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
