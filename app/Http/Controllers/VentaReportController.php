<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;

class VentaReportController extends Controller
{
    public function index2()
    {
        return view('ReportesVenta.index');
    }

    public function generarreporteventa(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $startDate = $request->input('fecha_inicio');
        $endDate = $request->input('fecha_fin');

        $ventas = Venta::whereBetween('fechaVenta', [$startDate, $endDate])->get();

        return view('ReportesVenta.resultado', [
            'ventas' => $ventas,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
