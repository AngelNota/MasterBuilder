<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Component;
use App\Models\Category;
use App\Services\ComponentCompatibilityService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteMail;

class QuoteController extends Controller
{
    public function __construct(private ComponentCompatibilityService $compatibilityService) {}

    public function index()
    {
        // Si es admin ve todas, si es usuario solo ve las suyas
        if (auth()->user()->hasRole('admin')) {
            $quotes = Quote::with('user')->latest()->paginate(10);
        } else {
            $quotes = Quote::where('user_id', auth()->id())->latest()->paginate(10);
        }

        return view('cotizaciones.index', compact('quotes'));
    }

    public function create()
    {
        // Agrupamos los componentes disponibles por su categoría principal
        // Para crear los campos del formulario (Procesador, RAM, Tarjeta Madre, etc.)
        $categories = Category::whereNull('parent_id')->with('children.components')->get();

        return view('cotizaciones.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'components' => 'required|array',
            'components.*' => 'nullable|exists:components,id'
        ]);

        $selectedIds = array_filter($request->components);

        // 1. Validar compatibilidad
        $errors = $this->compatibilityService->validateQuote($selectedIds);
        if (!empty($errors)) {
            return back()->withErrors(['error' => implode(' | ', $errors)]);
        }

        $subtotal = 0;
        $attachData = [];

        // 2. Validar Stock y preparar datos
        foreach ($selectedIds as $id) {
            $component = Component::findOrFail($id);

            // Validación de negocio (¡No vender lo que no hay!)
            if ($component->stock < 1) {
                return back()->withErrors(['error' => "El componente {$component->nombre} no tiene stock suficiente."]);
            }

            $subtotal += $component->precio;
            $attachData[$id] = [
                'cantidad' => 1,
                'precio_unitario' => $component->precio
            ];

            // 3. Restar el stock en tiempo real
            $component->decrement('stock', 1);
        }

        $total = $subtotal * 1.16; // Subtotal + IVA

        // 4. Crear cotización
        $quote = Quote::create([
            'user_id' => auth()->id(),
            'subtotal' => $subtotal,
            'iva' => $subtotal * 0.16,
            'total' => $total,
        ]);

        $quote->components()->attach($attachData);

        // Enviar correo automático con la cotización adjunta
        try {
            Mail::to(auth()->user()->email)->send(new QuoteMail($quote));
        } catch (\Exception $e) {
            // Continuar si falla el correo en local
            logger('Error al enviar correo: ' . $e->getMessage());
        }

        return redirect()->route('cotizaciones.show', $quote)->with('success', 'Cotización generada y stock actualizado.');
    }

    public function show(Quote $cotizacione)
    {
        // Nos aseguramos de que el cliente solo vea sus propias cotizaciones
        if (!auth()->user()->hasRole('admin') && $cotizacione->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta cotización.');
        }

        // Cargamos la cotización con sus componentes de la tabla pivote
        $cotizacione->load('components', 'user');

        return view('cotizaciones.show', compact('cotizacione'));
    }

    public function downloadPdf(Quote $cotizacione)
    {
        if (!auth()->user()->hasRole('admin') && $cotizacione->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para descargar este PDF.');
        }

        $cotizacione->load('components', 'user');
        $pdf = Pdf::loadView('cotizaciones.pdf', ['cotizacione' => $cotizacione]);
        return $pdf->download("Presupuesto_PCMB_{$cotizacione->id}.pdf");
    }

    public function resendEmail(Quote $cotizacione)
    {
        if (!auth()->user()->hasRole('admin') && $cotizacione->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para reenviar este correo.');
        }

        $cotizacione->load('components', 'user');

        try {
            Mail::to($cotizacione->user->email)->send(new QuoteMail($cotizacione));
            return back()->with('success', 'El correo de la cotización ha sido reenviado con éxito.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo enviar el correo: ' . $e->getMessage());
        }
    }
}