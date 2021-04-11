<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //记录访问接口的IP
        logger('CheckIP:'.$request->getClientIp());
        return $next($request);
    }
}
