<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class Jerarquia
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
        $user_id = \Auth::id();
        // dd($user_id);
        $user = User::find($user_id);
        // dd($user);
        if($user == null)
        {
            return redirect('home');
        }
        // dd($user->edad);
        elseif($user->cargo == 'Empleado')
        {
            return redirect('home');
        }
        // dd($request);
        return $next($request);
    }
}
