<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.signin');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        session()->regenerate();

        // Check user role and redirect accordingly
        $user = Auth::user();

        // Check if user has any roles
        if (!$user->roles()->exists()) {
            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Akun Anda belum diberi peran. Silakan hubungi administrator.']);
        }

        if ($user->roles()->where('name', 'admin')->exists()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->roles()->where('name', 'teacher')->exists()) {
            return redirect()->route('guru.dashboard');
        } elseif ($user->roles()->where('name', 'student')->exists()) {
            return redirect()->route('student.dashboard');
        }

        // Default redirect if no role found
        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
