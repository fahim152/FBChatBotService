<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Http\Controllers\Controller;
class MakeUserPermission
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
        if(Auth::check()) {
            $controller = new Controller;
            Auth::user()->menus = $controller->menu();
            // dd($controller->menu());
        }
        return $next($request);
    }
}
