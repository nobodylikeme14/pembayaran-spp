<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guard('petugas')->check()) {
            if (Auth::guard('petugas')->user()->privilege == "Administrator") {
                return redirect()->route('dashboard');
            } elseif (Auth::guard('petugas')->user()->privilege == "Petugas") {
                return redirect()->route('entri_pembayaran');
            }
        } elseif (Auth::guard('siswa')->check()) {
            if (Auth::guard('siswa')->user()->privilege == "Siswa") {
                return redirect()->route('histori_pembayaran');
            }
        }
        return $next($request);
    }
}
