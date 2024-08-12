<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $criterio = $request->input('criterio', 'fechaPago'); 
        $buscar = $request->input('buscar', '');
        $fechaInicio = $request->input('fecha_inicio', '');
        $fechaFin = $request->input('fecha_fin', '');

        $query = Pago::query();

        if ($buscar != '') {
            $query->where($criterio, 'like', '%'.$buscar.'%');
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fechaPago', [$fechaInicio, $fechaFin]);
        }

        $pagos = $query->paginate(5);

        return view('GestionarPago.index', compact('pagos'));
    }

    public function edit($codPago)
    {
        $pago = Pago::findOrFail($codPago);
        return view('GestionarPago.edit', compact('pago'));
    }

    public function update(Request $request, $codPago)
    {
        $pago = Pago::findOrFail($codPago);
        $pago->fechaPago = $request->input('fechaPago');
        $pago->monto = $request->input('monto');
        $pago->estado = $request->input('estado', 'Pendiente'); 
        $pago->codClienteF = $request->input('codClienteF');
        $pago->save();

        return back()->with('success', 'Pago actualizado con éxito.');
    }

    public function destroy($codPago)
    {
        $pago = Pago::findOrFail($codPago);
        $pago->delete();

        return back()->with('success', 'Pago eliminado con éxito.');
    }
}
