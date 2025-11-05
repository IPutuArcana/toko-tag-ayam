<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN perannya 'admin'
        if (auth()->check() && auth()->user()->role == 'admin') {
            // Jika ya, izinkan lanjut
            return $next($request);
        }

        // Jika tidak, tendang dia ke halaman dashboard biasa
        return redirect('/dashboard')->with('error', 'Anda tidak punya hak akses Admin.');
    }
}