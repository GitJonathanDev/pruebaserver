<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MenuController extends Controller
{
    /**
     * Muestra el menú basado en el tipo de usuario.
     */
    public function index()
    {

        if (!Auth::check()) {
            return Redirect::to('/');
        }

  
        $tipoUsuarioId = Auth::user()->codTipoUsuarioF;

        $menus = Menu::where('codTipoUsuarioF', $tipoUsuarioId)
            ->whereNull('padreId')
            ->with('hijos')
            ->get();


        return view('layouts.plantilla', compact('menus'));
    }
}
