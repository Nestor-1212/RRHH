<?php

namespace App\Providers;

use App\Models\Empleado;
use App\Models\User;
use App\Policies\EmpleadoPolicy;
use App\Policies\UserPolicy;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Carbon::setLocale('es');

        // Policies
        Gate::policy(Empleado::class, EmpleadoPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // Super Admin bypasses all gates
        Gate::before(fn(User $user) => $user->hasRole('Super Admin') ? true : null);

        // Rate limiters
        RateLimiter::for('login', fn(Request $request) =>
            Limit::perMinute(5)->by($request->ip())->response(fn() =>
                back()->withErrors(['email' => 'Demasiados intentos. Intenta en 60 segundos.'])
            )
        );

        RateLimiter::for('api', fn(Request $request) =>
            Limit::perMinute(120)->by($request->user()?->id ?: $request->ip())
        );
    }
}
