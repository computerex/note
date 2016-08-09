<?php

namespace App\Http\Middleware;

use Closure;
class ExampleMiddleware
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
        if(!isset($_SESSION['user']))
          return response()->json(["success" => FALSE], 401); 
        return $next($request);
    }
}
