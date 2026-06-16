<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $throttleKey = strtolower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts('login|' . $throttleKey, 5)) {
            $seconds = RateLimiter::availableIn('login|' . $throttleKey);
            throw ValidationException::withMessages([
                'email' => "Demasiados intentos fallidos. Intenta de nuevo en {$seconds} segundos.",
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit('login|' . $throttleKey, 60);

            AuditLog::record('login_failed', null, [], [
                'email' => $request->email,
            ]);

            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas son incorrectas.',
            ]);
        }

        RateLimiter::clear('login|' . $throttleKey);
        $request->session()->regenerate();

        AuditLog::record('login');

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        AuditLog::record('logout');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
