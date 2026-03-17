<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExtendTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jika user terautentikasi dan memiliki token akses saat ini
        if ($request->user() && $request->user()->currentAccessToken()) {
            /** @var \Laravel\Sanctum\PersonalAccessToken $token */
            $token = $request->user()->currentAccessToken();

            // Perpanjang masa berlaku token (misal: +24 jam dari akses terakhir)
            // Menggunakan forceFill karena kolom expires_at mungkin tidak fillable secara default di model bawaan Sanctum
            $token->forceFill([
                'expires_at' => now()->addHours(24),
            ])->save();
        }

        return $response;
    }
}
