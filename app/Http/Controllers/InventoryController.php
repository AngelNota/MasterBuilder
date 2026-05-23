<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Category;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtenemos las categorías principales para pintar el menú de pestañas
        $categories = Category::query()
            ->whereNull('parent_id')
            ->whereIn('name', [
                'Procesadores', 
                'Tarjetas Madre', 
                'Memoria RAM', 
                'Almacenamiento', 
                'Fuentes de Poder', 
                'Gabinetes', 
                'Ventilación', 
                'Tarjetas de Video'
            ])
            ->get()
            ->unique('name');

        // 2. Determinamos qué pestaña está activa (por defecto la primera que encuentre)
        $activeTab = $request->query('tab', $categories->first()->name ?? '');

        // 3. Traemos solo los componentes de esa pestaña y los agrupamos por MARCA
        $components = Component::query()
            ->with('category')
            ->whereHas('category', function($query) use ($activeTab) {
                $query->where('name', $activeTab);
            })
            ->orderBy('marca') 
            ->latest()
            ->get()
            ->groupBy('marca'); 

        return view('inventario.index', compact('categories', 'activeTab', 'components'));
    }

    public function create()
    {
        $categories = Category::query()
            ->whereNull('parent_id')
            ->whereIn('name', [
                'Procesadores', 
                'Tarjetas Madre', 
                'Memoria RAM', 
                'Almacenamiento', 
                'Fuentes de Poder', 
                'Gabinetes', 
                'Ventilación', 
                'Tarjetas de Video'
            ])
            ->get()
            ->unique('name');
        
        return view('inventario.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'marca' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validación de imagen
        ]);

        // Empaquetar TODOS los datos dinámicos para la IA y el Configurador
        $especificaciones = [];
        if ($request->filled('spec_socket')) $especificaciones['socket'] = $request->spec_socket;
        if ($request->filled('spec_memory_type')) $especificaciones['tipo_memoria'] = $request->spec_memory_type;
        if ($request->filled('spec_ram_slots')) $especificaciones['ram_slots'] = $request->spec_ram_slots; // NUEVO: Slots de RAM
        if ($request->filled('spec_watts')) $especificaciones['watts'] = $request->spec_watts;
        if ($request->filled('spec_certificacion')) $especificaciones['certificacion'] = $request->spec_certificacion;
        if ($request->filled('spec_ventilacion')) $especificaciones['tipo_ventilacion'] = $request->spec_ventilacion;
        if ($request->filled('spec_almacenamiento')) $especificaciones['tipo_almacenamiento'] = $request->spec_almacenamiento;
        if ($request->filled('spec_vram')) $especificaciones['vram'] = $request->spec_vram;

        
        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            
            $rutaImagen = $request->file('imagen')->store('componentes', 'public');
        }

        Component::create([
            'category_id' => $request->category_id,
            'nombre' => $request->nombre,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'imagen' => $rutaImagen, 
            'especificaciones' => empty($especificaciones) ? null : $especificaciones,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Componente agregado correctamente.');
    }

    public function destroy(Component $inventario)
    {
        $inventario->delete();
        return redirect()->route('inventario.index')->with('success', 'Componente eliminado.');
    }

    public function edit(Component $inventario)
{
    $categories = Category::query()
        ->whereNull('parent_id')
        ->whereIn('name', [
            'Procesadores', 'Tarjetas Madre', 'Memoria RAM', 'Almacenamiento',
            'Fuentes de Poder', 'Gabinetes', 'Ventilación', 'Tarjetas de Video'
        ])
        ->get()
        ->unique('name');

    return view('inventario.edit', compact('inventario', 'categories'));
}

public function update(Request $request, Component $inventario)
{
    $request->validate([
        'category_id'  => 'required|exists:categories,id',
        'marca'        => 'required|string|max:255',
        'nombre'       => 'required|string|max:255',
        'modelo'       => 'nullable|string|max:255',
        'precio'       => 'required|numeric|min:0',
        'stock'        => 'required|integer|min:0',
        'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $especificaciones = [];
    if ($request->filled('spec_socket'))        $especificaciones['socket']             = $request->spec_socket;
    if ($request->filled('spec_memory_type'))   $especificaciones['tipo_memoria']       = $request->spec_memory_type;
    if ($request->filled('spec_ram_slots'))     $especificaciones['ram_slots']          = $request->spec_ram_slots;
    if ($request->filled('spec_watts'))         $especificaciones['watts']              = $request->spec_watts;
    if ($request->filled('spec_certificacion')) $especificaciones['certificacion']      = $request->spec_certificacion;
    if ($request->filled('spec_ventilacion'))   $especificaciones['tipo_ventilacion']   = $request->spec_ventilacion;
    if ($request->filled('spec_almacenamiento'))$especificaciones['tipo_almacenamiento']= $request->spec_almacenamiento;
    if ($request->filled('spec_vram'))          $especificaciones['vram']               = $request->spec_vram;

    $rutaImagen = $inventario->imagen;
    if ($request->hasFile('imagen')) {
        // Eliminar imagen anterior si existe
        if ($inventario->imagen) {
            \Storage::disk('public')->delete($inventario->imagen);
        }
        $rutaImagen = $request->file('imagen')->store('componentes', 'public');
    }

    $inventario->update([
        'category_id'     => $request->category_id,
        'nombre'          => $request->nombre,
        'marca'           => $request->marca,
        'modelo'          => $request->modelo,
        'precio'          => $request->precio,
        'stock'           => $request->stock,
        'imagen'          => $rutaImagen,
        'especificaciones'=> empty($especificaciones) ? null : $especificaciones,
    ]);

    return redirect()->route('inventario.index')->with('success', 'Componente actualizado correctamente.');
}
}