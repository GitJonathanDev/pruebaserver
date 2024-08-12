<?php

namespace App\Http\Controllers;

use App\Models\TipoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoUsuarioController extends Controller
{
    /**
     * Muestra una lista de los recursos.
     */
    public function index(Request $request)
    {
        $criterio = $request->input('criterio', 'descripcion'); // Por defecto, usar 'descripcion'
        $buscar = $request->input('buscar', '');

        $query = TipoUsuario::query();

        if (!empty($buscar)) {
            $query->where($criterio, 'like', '%'.$buscar.'%');
        }

        $tipoUsuarios = $query->paginate(10);

        return view('GestionarTipoUsuario.index', compact('tipoUsuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        return view('GestionarTipoUsuario.create');
    }

    /**
     * Almacena un nuevo recurso en la base de datos.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tipoUsuario.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        TipoUsuario::create($request->only('descripcion'));

        return redirect()->route('tipoUsuario.index')
                         ->with('success', 'Tipo de usuario registrado con éxito');
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit($codTipoUsuario)
    {
        $tipoUsuario = TipoUsuario::findOrFail($codTipoUsuario);
        return view('GestionarTipoUsuario.edit', compact('tipoUsuario'));
    }

    /**
     * Actualiza el recurso especificado en la base de datos.
     */
    public function update(Request $request, $codTipoUsuario)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tipoUsuario.edit', $codTipoUsuario)
                             ->withErrors($validator)
                             ->withInput();
        }

        $tipoUsuario = TipoUsuario::findOrFail($codTipoUsuario);
        $tipoUsuario->update($request->only('descripcion'));

        return redirect()->route('tipoUsuario.index')
                         ->with('success', 'Tipo de usuario actualizado con éxito');
    }

    /**
     * Elimina el recurso especificado de la base de datos.
     */
    public function destroy($codTipoUsuario)
    {
        $tipoUsuario = TipoUsuario::findOrFail($codTipoUsuario);
        $tipoUsuario->delete();

        return redirect()->route('tipoUsuario.index')
                         ->with('delete', 'Tipo de usuario eliminado con éxito');
    }
}
