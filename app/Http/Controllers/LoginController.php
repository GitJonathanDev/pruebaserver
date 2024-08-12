<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function create()
    {
        return view('IniciarSesion.login');
    }

    /**
     * Redirige a la vista correspondiente según el tipo de usuario.
     */
    public function vista()
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


        return redirect()->route('login');
    }

    /**
     * Intenta autenticar al usuario con las credenciales proporcionadas.
     */
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return $this->vista();
        } else {
            return back()->withErrors([
                'message' => 'El correo electrónico o la contraseña son incorrectos, por favor intenta de nuevo.'
            ]);
        }
    }

    /**
     * Cierra la sesión del usuario y redirige al usuario a la página de inicio.
     */
    public function destroy()
    {
        Auth::logout();
        return redirect()->to('/');
    }
}
