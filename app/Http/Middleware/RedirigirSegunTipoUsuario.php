<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirigirSegunTipoUsuario
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

        
            switch ($user->codTipoUsuarioF) { 
                case 1:
                    return redirect()->route('cliente');
                case 2:
                    return redirect()->route('encargado');
                case 3:
                    return redirect()->route('admin');
                default:
                    return redirect()->route('principal'); 
            }
        }


        return $next($request);
    }
}
