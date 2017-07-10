<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class VerificaEdad
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
        // restringe que haya ususarios menores de edad
        $user_id = \Auth::id();
        // dd($user_id);
        $user = User::find($user_id);
        // dd($user);
        if($user == null)
        {
            return redirect('home');
        }
        // dd($user->edad);
        elseif($user->edad < 18)
        {
            return redirect('home');
        }
        // dd($request);
        return $next($request);
    }
}
