<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use User;

class Stationadmin
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
        $user = Auth::user()->usertype;
        if ($user == 'stationadmin'){
            return $next($request);
        }
        //return redirect('/dashboard');
        return redirect()->back();
    }
}
