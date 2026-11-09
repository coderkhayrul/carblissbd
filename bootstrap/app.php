<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: ['webhooks/steadfast', 'webhooks/pathao']);
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdminMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return url('/admin/login');
            }
            return url('/login');
        });


        $middleware->redirectUsersTo(function (Request $request) {
            $user = Auth::user();
            if ($user && $user->role === 'admin') {
                return url('/admin/dashboard');
            }
            if ($user && $user->role === 'customer') {
                return url('/profile');
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontFlash(['mail_password', 'steadfast_api_key', 'steadfast_secret_key', 'steadfast_webhook_token', 'pathao_client_id', 'pathao_client_secret', 'pathao_username', 'pathao_password', 'pathao_webhook_secret']);
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );
    })->create();
