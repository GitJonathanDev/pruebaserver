<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Muestra una lista de los clientes.
     */
    public function index(Request $request)
    {
        $criterio = $request->input('criterio', 'nombre'); 
        $buscar = $request->input('buscar', '');

        $query = Cliente::query();

        if (!empty($buscar)) {
            $query->where($criterio, 'like', '%'.$buscar.'%');
        }

        $clientes = $query->paginate(10);

        return view('GestionarCliente.index', compact('clientes', 'buscar', 'criterio'));
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        $usuarios = User::all();
        return view('GestionarCliente.create', compact('usuarios'));
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        // Crear nuevo usuario
        $user = new User();
        $user->nombreUsuario = $request->input('nombreUsuario');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->estadoBloqueado = false;
        $user->codTipoUsuarioF = 1; 
        $user->save();

        // Crear nuevo cliente
        $cliente = new Cliente();
        $cliente->carnetIdentidad = $request->input('carnetIdentidad');
        $cliente->nombre = $request->input('nombre');
        $cliente->apellidoPaterno = $request->input('apellidoPaterno');
        $cliente->apellidoMaterno = $request->input('apellidoMaterno');
        $cliente->sexo = $request->input('sexo');
        $cliente->edad = $request->input('edad');
        $cliente->telefono = $request->input('telefono');
        $cliente->codUsuarioF = $user->codUsuario; 
        $cliente->save();

        return redirect()->route('cliente.index')->with('success', 'Cliente registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar el cliente especificado.
     */
    public function edit($carnetIdentidad)
    {
        $cliente = Cliente::where('carnetIdentidad', $carnetIdentidad)->firstOrFail();
        return view('GestionarCliente.edit', compact('cliente'));
    }

    /**
     * Actualiza el cliente especificado en la base de datos.
     */
    public function update(Request $request, $carnetIdentidad)
    {
        $cliente = Cliente::where('carnetIdentidad', $carnetIdentidad)->firstOrFail();
        $cliente->nombre = $request->input('nombre');
        $cliente->apellidoPaterno = $request->input('apellidoPaterno');
        $cliente->apellidoMaterno = $request->input('apellidoMaterno');
        $cliente->sexo = $request->input('sexo');
        $cliente->edad = $request->input('edad');
        $cliente->telefono = $request->input('telefono');
        $cliente->save();

        return redirect()->route('cliente.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Elimina el cliente especificado de la base de datos.
     */
    public function destroy($carnetIdentidad)
    {
        $cliente = Cliente::where('carnetIdentidad', $carnetIdentidad)->firstOrFail();
        $cliente->delete();
        return redirect()->route('cliente.index')->with('success', 'Cliente eliminado exitosamente.');
    }

    /**
     * Verifica si el carnet de identidad ya existe.
     */
    public function ciYaExiste(Request $request)
    {
        $ci = $request->input('carnetIdentidad');
        $existe = Cliente::where('carnetIdentidad', $ci)->exists();
        return response()->json(['existe' => $existe]);
    }
}
