<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Métricas generales para las tarjetas superiores
        $activeQuotes = Quote::count();
        $totalStock = Component::sum('stock');
        // Capacidad simulación del almacén (500 piezas = 100%)
        $inventoryLoad = min(100, round(($totalStock / 500) * 100)); 
        $aiValidations = 342; // Simulado temporalmente

        // 2. Inicializar variables para el Administrador
        $totalSales = 0;
        $chartLabels = [];
        $chartData = [];

        // Si es administrador, calculamos métricas de ventas y componentes populares (Rúbrica)
        if ($user->hasRole('admin')) {
            // Suma total de los presupuestos generados
            $totalSales = Quote::sum('total');

            // Obtener los 5 componentes más populares usando la tabla pivote quote_component
            $popularComponents = DB::table('quote_component')
                ->select('component_id', DB::raw('SUM(cantidad) as total_qty'))
                ->groupBy('component_id')
                ->orderByDesc('total_qty')
                ->take(5)
                ->get();

            // Mapear los nombres de los componentes y cantidades para Chart.js
            foreach ($popularComponents as $item) {
                $component = Component::find($item->component_id);
                if ($component) {
                    $chartLabels[] = $component->nombre;
                    $chartData[] = (int) $item->total_qty;
                }
            }
        }

        return view('dashboard', [
            'activeQuotes' => $activeQuotes,
            'inventoryLoad' => $inventoryLoad,
            'aiValidations' => $aiValidations,
            'totalSales' => $totalSales,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}