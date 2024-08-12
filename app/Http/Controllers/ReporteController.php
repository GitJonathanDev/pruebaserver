<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;

class ReporteController extends Controller
{
    public function index()
    {
        return view('Reportes.index');
    }
    public function generarReporte(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $startDate = $request->input('fecha_inicio');
        $endDate = $request->input('fecha_fin');

        $pagos = Pago::whereBetween('fechaPago', [$startDate, $endDate])->get();

        return view('reportes.resultado', [
            'pagos' => $pagos,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
