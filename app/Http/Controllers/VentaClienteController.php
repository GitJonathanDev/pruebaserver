<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VentaClienteController extends Controller
{
    public function index() {
        $categorias = Categoria::all();
        $productos = Producto::all();
        return view('RealizarVenta.index', compact('categorias', 'productos'));
    }

    public function getProductos($codCategoria)
    {
        $productos = Producto::where('codCategoriaF', $codCategoria)->get();
        return response()->json($productos);
    }

    public function obtenerProductos(Request $request)
    {
        $codCategoria = $request->query('categoria');
        
        if ($codCategoria) {
            $productos = Producto::where('codCategoriaF', $codCategoria)->get();
        } else {
            $productos = Producto::all();
        }
        
        return response()->json($productos);
    }
    public function mostrarDetalles($idsYCantidades)
    {

        $items = explode(',', $idsYCantidades);
        $ids = [];
        $cantidades = [];
        
        foreach ($items as $item) {
            list($id, $cantidad) = explode(':', $item);
            $ids[] = $id;
            $cantidades[$id] = $cantidad;
        }
        
     
        $productos = Producto::whereIn('codProducto', $ids)->get();
        
        if ($productos->isEmpty()) {
            abort(404);
        }
        
 
        $user = Auth::user();
        $cliente = Cliente::where('codUsuarioF', $user->codUsuario)->first();
        

        return view('RealizarVenta.compra', [
            'productos' => $productos,
            'cantidades' => $cantidades,
            'cliente' => $cliente
        ]);
    }
    public function store(Request $request) {
    
 
        $userId = Auth::user()->codUsuario;

        $cliente = Cliente::where('codUsuarioF', $userId)->first();
    

        if (!$cliente) {
            return redirect()->back()->withErrors('Cliente no encontrado.');
        }
    
   
        $idCliente = $cliente->carnetIdentidad;
        $montoTotal = 0;
    
     
        foreach ($request->productos as $producto) {
            $montoTotal += $producto['cantidad'] * $producto['precio'];
        }
    
  
        $pago = new Pago();
        $pago->monto = $request->tnMonto;
        $pago->fechaPago = now()->toDateString();  
        $pago->codClienteF = $idCliente;
        $pago->save();
    

        $venta = new Venta();
        $venta->fechaVenta = now()->toDateString();
        $venta->montoTotal = $montoTotal; 
        $venta->codClienteF = $idCliente;
        $venta->codEncargadoF = 12454859; 
        $venta->codPagoF = $pago->codPago;
        $venta->save();
    
 
        foreach ($request->productos as $producto) {
            $detalleVenta = new DetalleVenta();
            $detalleVenta->codVenta = $venta->codVenta;
            $detalleVenta->codProducto = $producto['idproducto'];
            $detalleVenta->cantidad = $producto['cantidad'];
            $detalleVenta->precioV = $producto['precio'];
            $detalleVenta->save();
        }

        return redirect()->route('cliente')->with('success', 'Compra realizada con éxito.');
    }
    
}
