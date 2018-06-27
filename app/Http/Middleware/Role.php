<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $user = Auth::user();

        if($user->hasRole('admin'))
            return $next($request);

        foreach($roles as $role) {
            if($user->hasRole($role))
                return $next($request);
        }

        return redirect('login');
    }
}
