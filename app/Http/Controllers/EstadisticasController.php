<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use App\Models\Venta;
use App\Models\Compra;
use Carbon\Carbon;

class EstadisticasController extends Controller
{
    public function index()
    {
        $totalMembers = Membresia::count();
        $activeMembers = Membresia::whereHas('detalles', function($query) {
            $query->where('fechaFin', '>=', Carbon::today());
        })->count();

        $totalSales = Venta::count();
        $totalPurchases = Compra::count();
        $monthlySales = Venta::whereMonth('fechaVenta', Carbon::now()->month)->count();
        $monthlyPurchases = Compra::whereMonth('fechaCompra', Carbon::now()->month)->count();

        return view('estadisticas', compact('totalMembers', 'activeMembers', 'totalSales', 'totalPurchases', 'monthlySales', 'monthlyPurchases'));
    }
}