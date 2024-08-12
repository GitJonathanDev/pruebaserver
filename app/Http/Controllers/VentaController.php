<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::query();

        if ($request->filled('criterio') && $request->filled('buscar')) {
            $criterio = $request->criterio;
            $buscar = $request->buscar;

            if ($criterio == 'fechaVenta') {
                $query->whereDate('fechaVenta', $buscar);
            } elseif ($criterio == 'codClienteF') {
                $query->where('codClienteF', (int)$buscar);
            }
        }

        $ventas = $query->paginate(10);

        return view('GestionarVenta.index', compact('ventas'));
    }

    public function create()
    {
        $productos = Producto::all();
        $clientes = Cliente::all();
        return view('GestionarVenta.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fechaVenta' => 'required|date',
            'codClienteF' => 'required|exists:Cliente,codCliente',
            'productos_seleccionados' => 'required|json',
            'montoTotal' => 'required|numeric',
        ]);

        $venta = new Venta();
        $venta->fechaVenta = $request->fechaVenta;
        $venta->codClienteF = $request->codClienteF;
        $venta->codEncargadoF = 1; // Asume que el encargado está fijo en 1. Cambia esto según tu lógica.
        $venta->montoTotal = $request->montoTotal;
        $venta->save();

        $productos_seleccionados = json_decode($request->productos_seleccionados, true);

        foreach ($productos_seleccionados as $producto) {
            $detalleVenta = new DetalleVenta();
            $detalleVenta->codVenta = $venta->codVenta;
            $detalleVenta->codProducto = $producto['id'];
            $detalleVenta->cantidad = $producto['cantidad'];
            $detalleVenta->precioV = $producto['precio'];
            $detalleVenta->save();
        }

        return redirect()->route('venta.show', $venta->codVenta)->with('success', 'Venta registrada exitosamente.');
    }

    public function show($codVenta)
    {
        $venta = Venta::findOrFail($codVenta);
        $detalleVenta = DetalleVenta::where('codVenta', $codVenta)->get();

        return view('GestionarVenta.detalle', compact('venta', 'detalleVenta'));
    }

    public function anularVenta($codVenta)
    {
        $venta = Venta::findOrFail($codVenta);
        $venta->estado = 'Anulada';
        $venta->save();

        return redirect()->back()->with('success', 'Venta anulada correctamente');
    }
}
