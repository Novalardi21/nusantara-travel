<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'identifier' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $throttleKey = 'account-login:'.hash('sha256', Str::lower($input['identifier']).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'identifier' => 'Terlalu banyak percobaan. Coba lagi dalam '.RateLimiter::availableIn($throttleKey).' detik.',
            ]);
        }

        $matches = [];
        foreach (['member' => 'username', 'admin' => 'email', 'operator' => 'user'] as $guard => $column) {
            $account = Auth::guard($guard)->getProvider()->retrieveByCredentials([$column => $input['identifier']]);

            if ($account === null) {
                continue;
            }

            $storedPassword = $account->getAuthPassword();
            $isHashed = password_get_info($storedPassword)['algo'] !== null;
            $legacyLength = $guard === 'member' ? 20 : 50;
            $validPassword = $isHashed
                ? password_verify($input['password'], $storedPassword)
                : strlen($storedPassword) <= $legacyLength && hash_equals($storedPassword, $input['password']);

            if ($validPassword) {
                $matches[$guard] = [$account, $isHashed];
            }
        }

        if (count($matches) !== 1) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages(['identifier' => 'Username, email, atau kata sandi tidak cocok.']);
        }

        $guard = array_key_first($matches);
        [$account, $isHashed] = $matches[$guard];

        if (! $isHashed) {
            $account->forceFill(['password' => Hash::make($input['password'])])->save();
        }

        Auth::guard($guard)->login($account);
        $request->session()->regenerate();
        RateLimiter::clear($throttleKey);

        return redirect()->route($guard === 'member' ? 'booking' : 'operations.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        foreach (['member', 'admin', 'operator'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
