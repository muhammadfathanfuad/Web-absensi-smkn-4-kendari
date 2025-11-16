<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\Notification;
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
        
        // Update last login timestamp and IP address
        $user->update([
            'last_login_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        // Check if user has any roles
        if (!$user->roles()->exists()) {
            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Akun Anda belum diberi peran. Silakan hubungi administrator.']);
        }

        // Check if user is still using default password ("password")
        if ($user->isUsingDefaultPassword()) {
            // Check if notification already exists (regardless of read status)
            $existingNotification = Notification::where('user_id', $user->id)
                ->where('type', 'change_password')
                ->first();

            if (!$existingNotification) {
                // Create notification to remind user to change password (only once)
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'change_password',
                    'title' => 'Ganti Password Anda',
                    'message' => 'Anda masih menggunakan password default. Silakan ganti password Anda untuk keamanan akun.',
                    'is_read' => false,
                ]);
            } elseif ($existingNotification->is_read) {
                // If notification exists but is read, mark it as unread again
                // This ensures the notification always appears if user still uses default password
                $existingNotification->markAsUnread();
            }
            // If notification exists and is unread, do nothing (already showing)
        }

        if ($user->roles()->where('name', 'admin')->exists()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->roles()->where('name', 'teacher')->exists()) {
            return redirect()->route('guru.dashboard');
        } elseif ($user->roles()->where('name', 'student')->exists()) {
            return redirect()->route('murid.dashboard');
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
