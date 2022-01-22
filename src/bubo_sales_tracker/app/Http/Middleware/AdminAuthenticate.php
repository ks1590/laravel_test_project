<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuthenticate
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->is_admin === 0) {
            return back();
        }
        return $next($request);
    }
}
