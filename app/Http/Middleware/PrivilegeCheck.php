<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PrivilegeCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$privilege)
    {
        if(in_array($request->user()->privilege, $privilege)) {
            return $next($request);
        }
        return redirect()->route('login');
    }
}
