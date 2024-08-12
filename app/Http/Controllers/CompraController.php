<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Encargado;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $criterio = $request->get('criterio');
        $buscar = $request->get('buscar');

        $query = Compra::query();

        if ($criterio && $buscar) {
            $query->where($criterio, 'like', "%$buscar%");
        }

        $compras = $query->paginate(10);

        return view('GestionarCompra.index', compact('compras'));
    }

    public function create()
    {

        $usuario = Auth::user();

        $encargado = Encargado::where('codUsuarioF', $usuario->codUsuario)->first();
        $proveedores = Proveedor::all();
        $productos = Producto::all();

        return view('GestionarCompra.create', compact('proveedores', 'productos', 'encargado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fechaCompra' => 'required|date',
            'codEncargadoF' => 'required|exists:Encargado,carnetIdentidad',
            'codProveedorF' => 'required|exists:Proveedor,codProveedor',
            'productosSeleccionados' => 'required|json',
        ]);

        $compra = new Compra();
        $compra->fechaCompra = $request->fechaCompra;
        $compra->codEncargadoF = $request->codEncargadoF;
        $compra->codProveedorF = $request->codProveedorF;

        $productosSeleccionados = json_decode($request->productosSeleccionados);
        $montoTotal = 0;

        foreach ($productosSeleccionados as $producto) {
            $montoTotal += $producto->precio * $producto->cantidad;
        }

        $compra->montoTotal = $montoTotal;
        $compra->save();

        foreach ($productosSeleccionados as $producto) {
            $detalleCompra = new DetalleCompra();
            $detalleCompra->codCompra = $compra->codCompra;
            $detalleCompra->codProducto = $producto->id;
            $detalleCompra->cantidad = $producto->cantidad;
            $detalleCompra->precioC = $producto->precio;
            $detalleCompra->save();
        }

        return redirect()->route('compra.show', $compra->codCompra);
    }

    public function show($codCompra)
    {
        $compra = Compra::findOrFail($codCompra);
        $detalleCompra = DetalleCompra::where('codCompra', $codCompra)->get();

        return view('GestionarCompra.detalle', compact('compra', 'detalleCompra'));
    }

    public function edit($codCompra)
    {
        $compra = Compra::findOrFail($codCompra);
        $proveedores = Proveedor::all();
        $productos = Producto::all();
        $encargados = Encargado::all();

        return view('GestionarCompra.edit', compact('compra', 'proveedores', 'productos', 'encargados'));
    }

    public function update(Request $request, $codCompra)
    {
        $request->validate([
            'fechaCompra' => 'required|date',
            'codEncargadoF' => 'required|exists:Encargado,carnetIdentidad',
            'codProveedorF' => 'required|exists:Proveedor,codProveedor',
            'productosSeleccionados' => 'required|json',
        ]);

        $compra = Compra::findOrFail($codCompra);
        $compra->fechaCompra = $request->fechaCompra;
        $compra->codEncargadoF = $request->codEncargadoF;
        $compra->codProveedorF = $request->codProveedorF;
        $compra->montoTotal = $request->montoTotal;
        $compra->save();

        DetalleCompra::where('codCompra', $codCompra)->delete();

        $productosSeleccionados = json_decode($request->productosSeleccionados);

        foreach ($productosSeleccionados as $producto) {
            $detalleCompra = new DetalleCompra();
            $detalleCompra->codCompra = $codCompra;
            $detalleCompra->codProducto = $producto->id;
            $detalleCompra->cantidad = $producto->cantidad;
            $detalleCompra->precioC = $producto->precio;
            $detalleCompra->save();
        }

        return redirect()->route('compra.show', $codCompra);
    }

    public function destroy($codCompra)
    {
        $compra = Compra::findOrFail($codCompra);
        $compra->delete();

        return redirect()->route('compra.index')->with('delete', 'Compra eliminada exitosamente');
    }
}
