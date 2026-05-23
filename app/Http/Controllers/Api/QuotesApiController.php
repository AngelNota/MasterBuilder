<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Component;
use App\Services\ComponentCompatibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotesApiController extends Controller
{
    public function __construct(private ComponentCompatibilityService $compatibilityService) {}

    /**
     * Crea una nueva cotización desde cliente externo
     * POST /api/quotes
     */
    public function store(Request $request)
    {
        $request->validate([
            'components' => 'required|array',
            'components.*' => 'required|exists:components,id'
        ]);

        $selectedIds = array_filter($request->components);

        // 1. Validar compatibilidad
        $errors = $this->compatibilityService->validateQuote($selectedIds);
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de compatibilidad encontrados.',
                'errors' => $errors
            ], 422);
        }

        $subtotal = 0;
        $attachData = [];

        DB::beginTransaction();
        try {
            // 2. Validar Stock y preparar datos
            foreach ($selectedIds as $id) {
                $component = Component::lockForUpdate()->findOrFail($id);

                if ($component->stock < 1) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "El componente {$component->nombre} no tiene stock suficiente."
                    ], 422);
                }

                $subtotal += $component->precio;
                $attachData[$id] = [
                    'cantidad' => 1,
                    'precio_unitario' => $component->precio
                ];

                // Restar el stock
                $component->decrement('stock', 1);
            }

            $total = $subtotal * 1.16; // Subtotal + IVA

            // 3. Crear cotización
            $quote = Quote::create([
                'user_id' => $request->user()->id,
                'subtotal' => $subtotal,
                'iva' => $subtotal * 0.16,
                'total' => $total,
            ]);

            $quote->components()->attach($attachData);

            DB::commit();

            // Enviar correo automático
            try {
                \Illuminate\Support\Facades\Mail::to($request->user()->email)->send(new \App\Mail\QuoteMail($quote));
            } catch (\Exception $e) {
                logger('Error al enviar correo en API: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Cotización creada con éxito.',
                'quote' => [
                    'id' => $quote->id,
                    'subtotal' => number_format($quote->subtotal, 2),
                    'iva' => number_format($quote->iva, 2),
                    'total' => number_format($quote->total, 2),
                    'created_at' => $quote->created_at->toISOString(),
                    'components' => $quote->components->map(fn($c) => [
                        'id' => $c->id,
                        'nombre' => $c->nombre,
                        'marca' => $c->marca,
                        'modelo' => $c->modelo,
                        'precio' => number_format($c->precio, 2),
                    ])
                ]
            ], 21);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la cotización: ' . $e->getMessage()
            ], 500);
        }
    }
}
