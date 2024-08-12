<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        $query = Proveedor::query();

        if ($buscar != '') {
            $query->where($criterio, 'like', '%'.$buscar.'%');
        }

        $proveedor = $query->paginate(5);

        return view('GestionarProveedor.index', compact('proveedor'));
    }

    public function create()
    {
        return view('GestionarProveedor.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codProveedor' => 'required|integer|unique:Proveedor,codProveedor',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|numeric|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->route('proveedor.create')
                ->withErrors($validator)
                ->withInput();
        }

        $proveedor = new Proveedor();
        $proveedor->codProveedor = $request->codProveedor;
        $proveedor->nombre = $request->nombre;
        $proveedor->direccion = $request->direccion;
        $proveedor->telefono = $request->telefono;
        $proveedor->save();

        return redirect()->route('proveedor.index')->with('success', 'Proveedor registrado exitosamente.');
    }

    public function edit($codProveedor)
    {
        $proveedor = Proveedor::findOrFail($codProveedor);
        return view('GestionarProveedor.edit', compact('proveedor'));
    }

    public function update(Request $request, $codProveedor)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|numeric|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->route('proveedor.edit', $codProveedor)
                ->withErrors($validator)
                ->withInput();
        }

        $proveedor = Proveedor::findOrFail($codProveedor);
        $proveedor->nombre = $request->nombre;
        $proveedor->direccion = $request->direccion;
        $proveedor->telefono = $request->telefono;
        $proveedor->save();

        return redirect()->route('proveedor.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy($codProveedor)
    {
        $proveedor = Proveedor::findOrFail($codProveedor);
        $proveedor->delete();

        return redirect()->route('proveedor.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
