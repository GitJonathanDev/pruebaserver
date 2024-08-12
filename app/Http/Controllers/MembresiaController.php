<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\DetalleMembresia;
use App\Models\Pago;
use App\Models\Servicio;
use App\Models\Encargado;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembresiaController extends Controller
{
    public function index(Request $request)
    {
        $criterio = $request->get('criterio');
        $buscar = $request->get('buscar');
    
        $query = Membresia::query();

        if ($criterio && $buscar) {
            $query->where($criterio, 'like', "%$buscar%");
        }

        $membresias = $query->paginate(10);
    
        return view('GestionarMembresia.index', compact('membresias'));
    }

    public function create()
    {

        $user = auth()->user();

        $encargado = Encargado::where('codUsuarioF', $user->codUsuario)->first();


        $clientes = Cliente::all();
        $servicios = Servicio::with('precios', 'horario')->get();
        
        return view('GestionarMembresia.create', [
            'clientes' => $clientes,
            'servicios' => $servicios,
            'encargado' => $encargado,
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'codClienteF' => 'required|integer',
        'descripcion' => 'required|string',
        'serviciosSeleccionados' => 'required|json',
    ]);

    $serviciosSeleccionados = json_decode($request->input('serviciosSeleccionados'));

    if (!is_array($serviciosSeleccionados)) {
        return redirect()->back()->withErrors(['error' => 'No se encontraron servicios seleccionados.']);
    }

    $montoTotal = 0;

    foreach ($serviciosSeleccionados as $servicio) {
        if (!isset($servicio->codServicio, $servicio->tipoPrecio, $servicio->cantidad)) {
            continue;
        }

        $servicioModel = Servicio::with('precios')->find($servicio->codServicio);

        if (!$servicioModel || $servicioModel->precios->isEmpty()) {
            continue;
        }

        $precioServicio = $servicioModel->precios->firstWhere('tipo', $servicio->tipoPrecio)->precio ?? 0;
        $montoTotal += $precioServicio * $servicio->cantidad;
    }

    $pago = Pago::create([
        'monto' => $montoTotal,
        'fechaPago' => now()->toDateString(),
        'codClienteF' => $request->codClienteF,
    ]);

    $membresia = Membresia::create([
        'descripcion' => $request->descripcion,
        'precioTotal' => $montoTotal,
        'codClienteF' => $request->codClienteF,
        'codEncargadoF' => $request->codEncargadoF, 
        'codPagoF' => $pago->codPago,
    ]);

    foreach ($serviciosSeleccionados as $servicio) {
        DetalleMembresia::create([
            'fechaInicio' => $servicio->fechaInicio,
            'fechaFin' => $servicio->fechaFin,
            'subTotal' => $servicio->precio * $servicio->cantidad,
            'tipo' => $servicio->tipoPrecio,
            'codMembresia' => $membresia->codMembresia,
            'codServicio' => $servicio->codServicio, 
        ]);
    }

    return redirect()->route('membresia.show', $membresia->codMembresia);
}

    public function show($codMembresia)
    {
        $membresia = Membresia::findOrFail($codMembresia);
        $detalleMembresia = DetalleMembresia::where('codMembresia', $codMembresia)->get();

        return view('GestionarMembresia.detalle', compact('membresia', 'detalleMembresia'));
    }

    public function edit($codMembresia)
    {
        $membresia = Membresia::findOrFail($codMembresia);
        $clientes = Cliente::all();
        $servicios = Servicio::with('precios', 'horario')->get(); 

        return view('GestionarMembresia.edit', compact('membresia', 'clientes', 'servicios'));
    }

    public function update(Request $request, $codMembresia)
    {
        $membresia = Membresia::findOrFail($codMembresia);
        $membresia->update([
            'descripcion' => $request->descripcion,
            'precioTotal' => $request->precioTotal,
            'codClienteF' => $request->codClienteF,
            'codEncargadoF' => $request->codEncargadoF,
            'codPagoF' => $request->codPagoF,
        ]);

        DetalleMembresia::where('codMembresia', $codMembresia)->delete();

        foreach ($request->input('servicios') as $servicio) {
            DetalleMembresia::create([
                'fechaInicio' => $request->fechaInicio,
                'fechaFin' => $request->fechaFin,
                'subTotal' => $servicio['precio'] * $servicio['cantidad'],
                'tipo' => $servicio['tipoPrecio'],
                'codMembresia' => $codMembresia,
                'codServicio' => $servicio['codServicio'],
            ]);
        }

        return redirect()->route('membresia.show', $codMembresia);
    }

    public function destroy($codMembresia)
    {
        $membresia = Membresia::findOrFail($codMembresia);
        $membresia->delete();

        return redirect()->route('membresia.index')->with('delete', 'Membresia eliminada exitosamente');
    }

    public function mostrarMembresias()
    {
        $cliente = Cliente::where('codUsuarioF', Auth::id())->firstOrFail();

        $membresias = Membresia::with('detalles.servicio', 'detalles.servicio.horario')
            ->where('codClienteF', $cliente->carnetIdentidad)
            ->get();

        return view('MisMembresias', compact('membresias'));
    }

    public function buscarCliente(Request $request)
    {
        $query = $request->get('query');

        $clientes = Cliente::where('nombre', 'like', "%$query%")
            ->orWhere('apellidoPaterno', 'like', "%$query%")
            ->orWhere('telefono', 'like', "%$query%")
            ->get(['nombre', 'apellidoPaterno', 'telefono', 'carnetIdentidad']);

        return response()->json($clientes);
    }
}
