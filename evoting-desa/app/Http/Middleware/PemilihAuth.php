<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilihAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('pemilih')->check()) {
            return redirect()->route('pemilih.login')
                ->withErrors(['session' => 'Silakan login terlebih dahulu']);
        }

        return $next($request);
    }
}