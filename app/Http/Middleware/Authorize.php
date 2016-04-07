<?php namespace App\Http\Middleware;

use Closure;

class Authorize {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $controller){

        $controller = new $controller();

        if(!$controller->isAuthorized($request)){
            return $controller->error("You aren't allowed to perform the requested action", 403);
        }

        return $next($request);
    }
}
