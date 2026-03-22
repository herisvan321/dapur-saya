<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('api', [
            \App\Http\Middleware\ExtendTokenExpiration::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
            }
            return '/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });

       $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                
                // 1. Jika Gagal Validasi (422)
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->validator->errors()->first(), // Ambil pesan error pertama
                        'errors' => $e->validator->errors()
                    ], 422);
                }

                // 2. Jika Token Expired/Salah (401)
                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sesi berakhir. Silakan login kembali.',
                    ], 401);
                }

                // 3. Error Lainnya (500, 404, dll)
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage() ?: 'Terjadi kesalahan sistem di server.',
                ], $statusCode);
            }
        });
    })->create();
