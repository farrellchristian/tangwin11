<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // Parameter role yang diharapkan (misal: 'admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login DAN rolenya sesuai
        if (!Auth::check() || Auth::user()->role !== $role) {
            // 2. Jika tidak sesuai, lempar ke halaman 403 (Forbidden)
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.'); 
        }

        // 3. Jika sesuai, lanjutkan ke halaman tujuan
        return $next($request);
    }
}