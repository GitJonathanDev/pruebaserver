<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;

class CompraReportController extends Controller
{
    public function index1()
    {
        return view('ReportesCompra.index');
    }

    public function generarreportecompra(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $startDate = $request->input('fecha_inicio');
        $endDate = $request->input('fecha_fin');

        $compras = Compra::whereBetween('fechaCompra', [$startDate, $endDate])->get();

        return view('ReportesCompra.resultado', [
            'compras' => $compras,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
