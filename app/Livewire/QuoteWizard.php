<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Component as Hardware;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class QuoteWizard extends Component
{
    public $pasoActual = 1;

    // Variables de selección de cada paso
    public $cpu_id = '';
    public $motherboard_id = '';
    public $ram_id = '';
    public $ram_cantidad = 1;
    public $storage_id = '';
    public $storage_cantidad = 1;
    public $gpu_id = '';
    public $case_id = '';
    public $psu_id = '';

    // Modelos cacheados para leer especificaciones
    public $cpuSeleccionado;
    public $mbSeleccionada;
    
    // Variables para el Resumen Final (Paso 8)
    public $resumenComponentes = [];
    public $subtotal = 0;
    public $iva = 0;
    public $total = 0;

    public function updatedCpuId($value)
    {
        $this->cpuSeleccionado = Hardware::find($value);
        $this->motherboard_id = '';
        $this->mbSeleccionada = null;
        $this->ram_id = '';
    }

    public function updatedMotherboardId($value)
    {
        $this->mbSeleccionada = Hardware::find($value);
        $this->ram_id = '';
        $this->ram_cantidad = 1;
    }

    public function siguientePaso()
    {
        // Validaciones estrictas antes de avanzar
        if ($this->pasoActual == 1 && empty($this->cpu_id)) return;
        if ($this->pasoActual == 2 && empty($this->motherboard_id)) return;
        if ($this->pasoActual == 3 && empty($this->ram_id)) return;
        if ($this->pasoActual == 4 && empty($this->storage_id)) return;
        if ($this->pasoActual == 5 && empty($this->gpu_id)) return;
        if ($this->pasoActual == 6 && empty($this->case_id)) return;
        if ($this->pasoActual == 7 && empty($this->psu_id)) return;

        // Si pasamos de la Fuente de Poder al Resumen, calculamos todo
        if ($this->pasoActual == 7) {
            $this->generarResumen();
        }

        if ($this->pasoActual < 8) {
            $this->pasoActual++;
        }
    }

    public function pasoAnterior()
    {
        if ($this->pasoActual > 1) {
            $this->pasoActual--;
        }
    }

    public function generarResumen()
    {
        $this->resumenComponentes = [
            ['modelo' => Hardware::find($this->cpu_id), 'cantidad' => 1],
            ['modelo' => Hardware::find($this->motherboard_id), 'cantidad' => 1],
            ['modelo' => Hardware::find($this->ram_id), 'cantidad' => (int) $this->ram_cantidad],
            ['modelo' => Hardware::find($this->storage_id), 'cantidad' => (int) $this->storage_cantidad],
            ['modelo' => Hardware::find($this->gpu_id), 'cantidad' => 1],
            ['modelo' => Hardware::find($this->case_id), 'cantidad' => 1],
            ['modelo' => Hardware::find($this->psu_id), 'cantidad' => 1],
        ];

        $sub = 0;
        foreach ($this->resumenComponentes as $item) {
            if($item['modelo']) {
                $sub += $item['modelo']->precio * $item['cantidad'];
            }
        }

        $this->subtotal = $sub;
        $this->iva = $sub * 0.16;
        $this->total = $this->subtotal + $this->iva;
    }

    public function guardarCotizacion()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        DB::beginTransaction();
        try {
            // Crear el registro de la cotización
            $quote = Quote::create([
                'user_id' => auth()->id(),
                'subtotal' => $this->subtotal,
                'iva' => $this->iva,
                'total' => $this->total,
            ]);

            // Guardar los componentes asociados en la tabla pivote
            foreach ($this->resumenComponentes as $item) {
                if($item['modelo']) {
                    $quote->components()->attach($item['modelo']->id, [
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['modelo']->precio,
                    ]);
                }
            }

            DB::commit();

            // Enviar correo automático con la cotización adjunta
            try {
                \Illuminate\Support\Facades\Mail::to(auth()->user()->email)->send(new \App\Mail\QuoteMail($quote));
            } catch (\Exception $e) {
                logger('Error al enviar correo en Wizard: ' . $e->getMessage());
            }

            // Redirigir al detalle de la cotización creada
            return redirect()->route('cotizaciones.show', $quote->id)->with('success', '¡Ensamble guardado exitosamente!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Hubo un error al guardar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.quote-wizard', [
            'procesadores' => Hardware::whereHas('category', fn($q) => $q->where('name', 'Procesadores'))->where('stock', '>', 0)->get(),
            'tarjetasMadre' => $this->cpuSeleccionado ? Hardware::whereHas('category', fn($q) => $q->where('name', 'Tarjetas Madre'))->where('stock', '>', 0)->get()->filter(function ($item) {
                return isset($item->especificaciones['socket']) && $item->especificaciones['socket'] === ($this->cpuSeleccionado->especificaciones['socket'] ?? null);
            }) : collect(),
            'memoriasRam' => $this->mbSeleccionada ? Hardware::whereHas('category', fn($q) => $q->where('name', 'Memoria RAM'))->where('stock', '>', 0)->get()->filter(function ($item) {
                return isset($item->especificaciones['tipo_memoria']) && $item->especificaciones['tipo_memoria'] === ($this->mbSeleccionada->especificaciones['tipo_memoria'] ?? null);
            }) : collect(),
            'almacenamientos' => Hardware::whereHas('category', fn($q) => $q->where('name', 'Almacenamiento'))->where('stock', '>', 0)->get(),
            'graficas' => Hardware::whereHas('category', fn($q) => $q->where('name', 'Tarjetas de Video'))->where('stock', '>', 0)->get(),
            'gabinetes' => Hardware::whereHas('category', fn($q) => $q->where('name', 'Gabinetes'))->where('stock', '>', 0)->get(),
            'fuentes' => Hardware::whereHas('category', fn($q) => $q->where('name', 'Fuentes de Poder'))->where('stock', '>', 0)->get(),
        ]);
    }

    public function getPuedeAvanzarProperty(): bool
{
    return match($this->pasoActual) {
        1 => !empty($this->cpu_id),
        2 => !empty($this->motherboard_id),
        3 => !empty($this->ram_id),
        4 => !empty($this->storage_id),
        5 => !empty($this->gpu_id),
        6 => !empty($this->case_id),
        7 => !empty($this->psu_id),
        default => true,
    };
}
}