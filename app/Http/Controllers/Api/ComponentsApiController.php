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
