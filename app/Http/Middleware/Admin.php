<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use User;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*if ( Auth::check() && Auth::user()->isAdmin() )
        {
            return $next($request);
        }*/

        $user = Auth::user()->usertype;
        if ($user == 'admin'){
            return $next($request);
        }
        //return redirect('/dashboard/stationadm');
        return redirect()->back();
    }
}
