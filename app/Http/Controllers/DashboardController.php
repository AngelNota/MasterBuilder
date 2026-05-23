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
        $activeQuotes = $user->hasRole('admin') ? Quote::count() : Quote::where('user_id', $user->id)->count();
        $totalStock = Component::sum('stock');
        // Capacidad simulación del almacén (500 piezas = 100%)
        $inventoryLoad = min(100, round(($totalStock / 500) * 100)); 
        $aiValidations = $user->hasRole('admin') ? 342 : Quote::where('user_id', $user->id)->count() * 2; // Simulado

        // 2. Inicializar variables para el Administrador y Cliente
        $totalSales = $user->hasRole('admin') ? Quote::sum('total') : Quote::where('user_id', $user->id)->sum('total');
        $chartLabels = [];
        $chartData = [];
        $myQuotes = collect();
        $lowStockComponents = collect();

        // Si es administrador, calculamos métricas de ventas y componentes populares (Rúbrica)
        if ($user->hasRole('admin')) {
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

            // Componentes con stock bajo (menos de 3 unidades) para alertas de inventario bajo
            $lowStockComponents = Component::where('stock', '<', 3)
                ->orderBy('stock')
                ->take(5)
                ->get();
        } else {
            // Cargar las cotizaciones del cliente
            $myQuotes = Quote::where('user_id', $user->id)
                ->with('components')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', [
            'activeQuotes' => $activeQuotes,
            'inventoryLoad' => $inventoryLoad,
            'aiValidations' => $aiValidations,
            'totalSales' => $totalSales,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'myQuotes' => $myQuotes,
            'lowStockComponents' => $lowStockComponents,
        ]);
    }
}