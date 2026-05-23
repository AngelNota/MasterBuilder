<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Services\ComponentCompatibilityService;
use Illuminate\Http\Request;

class ComponentsApiController extends Controller
{
    public function __construct(private ComponentCompatibilityService $compatibilityService) {}

    /**
     * Lista completa del catálogo con disponibilidad de stock
     * GET /api/components
     */
    public function index()
    {
        $components = Component::with('category')->get();

        return response()->json([
            'success' => true,
            'components' => $components->map(fn($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'marca' => $c->marca,
                'modelo' => $c->modelo,
                'precio' => number_format($c->precio, 2),
                'stock' => $c->stock,
                'categoria' => $c->category->name,
                'especificaciones' => $c->especificaciones,
                'imagen_url' => $c->imagen ? asset('storage/' . $c->imagen) : null,
            ])
        ]);
    }

    /**
     * Detalle de un componente específico
     * GET /api/components/{id}
     */
    public function show($id)
    {
        $component = Component::with('category')->find($id);

        if (!$component) {
            return response()->json([
                'success' => false,
                'message' => 'Componente no encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'component' => [
                'id' => $component->id,
                'nombre' => $component->nombre,
                'marca' => $component->marca,
                'modelo' => $component->modelo,
                'precio' => number_format($component->precio, 2),
                'stock' => $component->stock,
                'categoria' => $component->category->name,
                'especificaciones' => $component->especificaciones,
                'imagen_url' => $component->imagen ? asset('storage/' . $component->imagen) : null,
            ]
        ]);
    }

    /**
     * Listado de categorías disponibles
     * GET /api/categories
     */
    public function categories()
    {
        $categories = \App\Models\Category::all();

        return response()->json([
            'success' => true,
            'categories' => $categories->map(fn($cat) => [
                'id' => $cat->id,
                'nombre' => $cat->name,
                'parent_id' => $cat->parent_id
            ])
        ]);
    }

    /**
     * Obtiene componentes compatibles para una categoría
     * GET /api/components/compatible?category_id=1&selected_ids=2,3,4
     */
    public function compatible(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'selected_ids' => 'nullable|string',
        ]);

        $selectedIds = $request->query('selected_ids')
            ? array_filter(explode(',', $request->query('selected_ids')))
            : [];

        $components = $this->compatibilityService->getCompatibleComponents(
            $request->category_id,
            $selectedIds
        );

        return response()->json([
            'success' => true,
            'components' => $components->map(fn($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'marca' => $c->marca,
                'modelo' => $c->modelo,
                'precio' => number_format($c->precio, 2),
                'stock' => $c->stock,
            ])->values(),
        ]);
    }
}
