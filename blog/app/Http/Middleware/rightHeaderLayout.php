<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use View;

class rightHeaderLayout
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
        function rightHeader()
        {
            $request = request()->path();
            
            if(Auth::check()){ return 'layouts/rightHeader/authenticated/userMenu'; }
            if($request == 'login'){ return 'layouts/rightHeader/unAuthenticated/login'; }
            if($request == 'register'){ return 'layouts/rightHeader/unAuthenticated/register'; }
            return 'layouts/rightHeader/unAuthenticated/other';
        }

        View::composer('*', function($view)
        {
            $rightHeaderLayout = rightHeader();
            $view->with('rightHeaderLayout', $rightHeaderLayout);
        });

        return $next($request);
    }


}
