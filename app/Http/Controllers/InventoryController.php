<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Category;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $components = Component::with('category')->latest()->paginate(10);
        return view('inventario.index', compact('components'));
    }

    public function create()
    {
        // Solo traemos subcategorías (ej. Intel, AMD, DDR4, etc.)
        $categories = Category::whereNotNull('parent_id')->get();
        return view('inventario.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Empaquetar datos para la IA
        $especificaciones = [];
        if ($request->filled('spec_socket')) $especificaciones['socket'] = $request->spec_socket;
        if ($request->filled('spec_memory_type')) $especificaciones['tipo_memoria'] = $request->spec_memory_type;
        if ($request->filled('spec_watts')) $especificaciones['watts'] = $request->spec_watts;

        Component::create([
            'category_id' => $request->category_id,
            'nombre' => $request->nombre,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'especificaciones' => empty($especificaciones) ? null : $especificaciones,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Componente agregado correctamente.');
    }

    public function destroy(Component $inventario)
    {
        $inventario->delete();
        return redirect()->route('inventario.index')->with('success', 'Componente eliminado.');
    }
}