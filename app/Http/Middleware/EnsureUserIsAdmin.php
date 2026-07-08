<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Cuma user dengan role 'admin' (guard tbuser) yang boleh lewat.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('tbuser')->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (Auth::guard('tbuser')->user()->role !== 'admin') {
            abort(403, 'Halaman ini khusus untuk admin.');
        }

        return $next($request);
    }
}
