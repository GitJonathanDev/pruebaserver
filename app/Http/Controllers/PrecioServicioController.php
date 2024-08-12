<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrecioServicio;
use App\Models\Servicio;

class PrecioServicioController extends Controller
{
    public function index(Request $request)
    {
        $preciosServicio = PrecioServicio::paginate(5);
        return view('GestionarPrecioServicio.index', compact('preciosServicio'));
    }

    public function create()
    {
        $servicios = Servicio::all();

        $tiposExistentes = PrecioServicio::distinct('tipo')->pluck('tipo')->toArray();
        $tiposDisponibles = ['Diario', 'Mensual', 'Anual'];

        $tiposDisponibles = array_values(array_diff($tiposDisponibles, $tiposExistentes));

        $tiposRegistrados = [];

        foreach ($servicios as $servicio) {
            $tiposRegistrados[$servicio->codServicio] = PrecioServicio::where('codServicioF', $servicio->codServicio)
                                                                      ->distinct('tipo')
                                                                      ->pluck('tipo')
                                                                      ->toArray();
        }

        return view('GestionarPrecioServicio.create', compact('servicios', 'tiposDisponibles', 'tiposRegistrados'));
    }

    public function edit($codPrecioServicio)
    {
        $precioServicio = PrecioServicio::findOrFail($codPrecioServicio);
        $servicios = Servicio::all();

        $tiposRegistrados = PrecioServicio::where('codServicioF', $precioServicio->codServicioF)
                                           ->where('codPrecioServicio', '!=', $precioServicio->codPrecioServicio)
                                           ->distinct('tipo')
                                           ->pluck('tipo')
                                           ->toArray();

        $tiposDisponibles = ['Diario', 'Mensual', 'Anual'];
        $tiposDisponibles = array_diff($tiposDisponibles, $tiposRegistrados);

        return view('GestionarPrecioServicio.edit', compact('precioServicio', 'servicios', 'tiposDisponibles', 'tiposRegistrados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required',
            'precio' => 'required|numeric',
            'codServicioF' => 'required|exists:Servicio,codServicio',
        ]);

        $existingPrecioServicio = PrecioServicio::where('tipo', $request->tipo)
                                                 ->where('codServicioF', $request->codServicioF)
                                                 ->first();

        if ($existingPrecioServicio) {
            return redirect()->back()->withInput()->withErrors(['tipo' => 'Ya existe un precio de servicio con este tipo para el servicio seleccionado.']);
        }

        PrecioServicio::create([
            'tipo' => $request->tipo,
            'precio' => $request->precio,
            'codServicioF' => $request->codServicioF,
        ]);

        return redirect()->route('precioServicio.index')->with('success', 'Precio de servicio creado correctamente');
    }

    public function update(Request $request, $codPrecioServicio)
    {
        $request->validate([
            'tipo' => 'required',
            'precio' => 'required|numeric',
            'codServicioF' => 'required|exists:Servicio,codServicio',
        ]);

        $precioServicio = PrecioServicio::findOrFail($codPrecioServicio);

        if ($request->tipo !== $precioServicio->tipo) {
            $existingPrecioServicio = PrecioServicio::where('tipo', $request->tipo)
                                                    ->where('codServicioF', $request->codServicioF)
                                                    ->first();

            if ($existingPrecioServicio) {
                return redirect()->back()->withInput()->withErrors(['tipo' => 'Ya existe un precio de servicio con este tipo para el servicio seleccionado.']);
            }
        }

        $precioServicio->update([
            'tipo' => $request->tipo,
            'precio' => $request->precio,
            'codServicioF' => $request->codServicioF,
        ]);

        return redirect()->route('precioServicio.index')->with('success', 'Precio de servicio actualizado correctamente');
    }

    public function destroy($codPrecioServicio)
    {
        $precioServicio = PrecioServicio::findOrFail($codPrecioServicio);
        $precioServicio->delete();

        return redirect()->route('precioServicio.index')->with('success', 'Precio de servicio eliminado correctamente');
    }
}
