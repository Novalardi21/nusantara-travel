<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->redirectUsersTo(fn (): string => route(
            Auth::guard('admin')->check() || Auth::guard('operator')->check()
                ? 'operations.dashboard'
                : 'booking'
        ));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpException $exception, Request $request): ?RedirectResponse {
            if (! ($exception->getPrevious() instanceof TokenMismatchException)
                || ! $request->routeIs('payment.store')
                || $request->expectsJson()) {
                return null;
            }

            if (! Auth::guard('member')->check()) {
                return redirect()->route('login', [], 303)->withErrors([
                    'identifier' => 'Sesi masuk berakhir. Masuk kembali, lalu buka Konfirmasi Bayar.',
                ]);
            }

            return redirect()->route('payment', ['pesanan' => $request->route('booking')], 303)->withErrors([
                'payment' => 'Sesi formulir berakhir. Isi ulang detail transfer dan kirim kembali.',
            ]);
        });
    })->create();
