<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('reservations*', 'wishlist*', 'fines*', 'profile*')) {
                return route('login.member');
            }
            return route('admin.login');
        });
        $middleware->web(append: [
            \App\Http\Middleware\SetLocaleMiddleware::class,
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Database\UniqueConstraintViolationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Gagal menyimpan: Data yang dimasukkan (kode, username, atau email) sudah terdaftar di sistem.',
                ], 422);
            }
            return back()->withInput()->with('error', 'Gagal menyimpan: Data yang Anda masukkan (seperti kode, username, atau email) sudah terdaftar di sistem.');
        });
    })->create();
