<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class Login
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
        auth()->login(User::inRandomOrder()->first());
        return $next($request);
    }
}
