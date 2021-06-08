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
        $visit_ip = $request->getClientIp();
        $allow_ip_arr = explode(',',env('ALLOW_VISIT_IP_LIST'));
        if (in_array($visit_ip,$allow_ip_arr)){
            //允许,不做限制
//            logger('CheckIP允许:'.$visit_ip);
            return $next($request);
        }else{
            //不允许
            logger('CheckIP不允许:'.$visit_ip);
            return redirect('/forbid_visit_prompt');
        }

    }
}
