<div class="bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm p-6 relative">

    @if (session()->has('error'))
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-8 border-b border-zinc-800 pb-4">
        <h2 class="text-xl font-bold text-white">
            Paso {{ $pasoActual }}:
            @if($pasoActual == 1) Procesador
            @elseif($pasoActual == 2) Tarjeta Madre
            @elseif($pasoActual == 3) Memoria RAM
            @elseif($pasoActual == 4) Almacenamiento
            @elseif($pasoActual == 5) Tarjeta de Video
            @elseif($pasoActual == 6) Gabinete
            @elseif($pasoActual == 7) Fuente de Poder
            @elseif($pasoActual == 8) Resumen del Ensamble
            @endif
        </h2>
        <div class="text-sm text-zinc-500 font-mono">Paso {{ $pasoActual }} de 8</div>
    </div>

    @if($pasoActual == 1)
        <div class="space-y-6">
            <p class="text-zinc-400">El cerebro de la computadora. La marca y modelo definirán qué tarjetas madre puedes usar.</p>
            <flux:select wire:model.live="cpu_id" label="Selecciona el Procesador">
                <option value="">Elige un procesador...</option>
                @foreach($procesadores as $cpu)
                    <option value="{{ $cpu->id }}">{{ $cpu->marca }} {{ $cpu->nombre }} - ${{ number_format($cpu->precio, 2) }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    @if($pasoActual == 2)
        <div class="space-y-6">
            <p class="text-zinc-400">Placas base compatibles con el socket <strong>{{ $cpuSeleccionado->especificaciones['socket'] ?? '' }}</strong>.</p>
            @if($tarjetasMadre->isEmpty())
                <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm">No hay placas base compatibles en inventario.</div>
            @else
                <flux:select wire:model.live="motherboard_id" label="Tarjetas Madre">
                    <option value="">Elige una placa base...</option>
                    @foreach($tarjetasMadre as $mb)
                        <option value="{{ $mb->id }}">{{ $mb->marca }} {{ $mb->nombre }} ({{ $mb->especificaciones['tipo_memoria'] ?? '' }}) - ${{ number_format($mb->precio, 2) }}</option>
                    @endforeach
                </flux:select>
            @endif
        </div>
    @endif

    @if($pasoActual == 3)
        <div class="space-y-6">
            <p class="text-zinc-400">Módulos de tipo <strong>{{ $mbSeleccionada->especificaciones['tipo_memoria'] ?? '' }}</strong>.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <flux:select wire:model="ram_id" label="Módulos de RAM">
                        <option value="">Elige la memoria...</option>
                        @foreach($memoriasRam as $ram)
                            <option value="{{ $ram->id }}">{{ $ram->marca }} {{ $ram->nombre }} - ${{ number_format($ram->precio, 2) }}</option>
                        @endforeach
                    </flux:select>
                </div>
                <div class="md:col-span-1">
                    <flux:select wire:model.live="ram_cantidad" label="Cantidad">
                        <option value="1">1 Unidad</option>
                        @if(($mbSeleccionada->especificaciones['ram_slots'] ?? 2) >= 2)
                            <option value="2">2 Unidades</option>
                        @endif
                        @if(($mbSeleccionada->especificaciones['ram_slots'] ?? 2) >= 4)
                            <option value="4">4 Unidades</option>
                        @endif
                    </flux:select>
                </div>
            </div>
        </div>
    @endif

    @if($pasoActual == 4)
        <div class="space-y-6">
            <p class="text-zinc-400">Selecciona el almacenamiento (SSD NVMe recomendado para OS).</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <flux:select wire:model.live="storage_id" label="Unidad de Almacenamiento">
                        <option value="">Elige el almacenamiento...</option>
                        @foreach($almacenamientos as $storage)
                            <option value="{{ $storage->id }}">{{ $storage->marca }} {{ $storage->nombre }} ({{ $storage->especificaciones['tipo_almacenamiento'] ?? 'SSD' }}) - ${{ number_format($storage->precio, 2) }}</option>
                        @endforeach
                    </flux:select>
                </div>
                <div class="md:col-span-1">
                    <flux:select wire:model.live="storage_cantidad" label="Cantidad">
                        <option value="1">1 Unidad</option>
                        <option value="2">2 Unidades</option>
                    </flux:select>
                </div>
            </div>
        </div>
    @endif

    @if($pasoActual == 5)
        <div class="space-y-6">
            <p class="text-zinc-400">La potencia gráfica para juegos o renderizado.</p>
            <flux:select wire:model.live="gpu_id" label="Tarjeta Gráfica">
                <option value="">Elige una gráfica...</option>
                @foreach($graficas as $gpu)
                    <option value="{{ $gpu->id }}">{{ $gpu->marca }} {{ $gpu->nombre }} ({{ $gpu->especificaciones['vram'] ?? 'N/A' }}GB) - ${{ number_format($gpu->precio, 2) }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    @if($pasoActual == 6)
        <div class="space-y-6">
            <p class="text-zinc-400">El chasis donde irá montado todo el hardware.</p>
            <flux:select wire:model.live="case_id" label="Gabinete">
                <option value="">Elige un gabinete...</option>
                @foreach($gabinetes as $case)
                    <option value="{{ $case->id }}">{{ $case->marca }} {{ $case->nombre }} - ${{ number_format($case->precio, 2) }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    @if($pasoActual == 7)
        <div class="space-y-6">
            <p class="text-zinc-400">Elige una fuente capaz de alimentar tu procesador y tarjeta gráfica.</p>
            <flux:select wire:model.live="psu_id" label="Fuente de Poder">
                <option value="">Elige una fuente...</option>
                @foreach($fuentes as $psu)
                    <option value="{{ $psu->id }}">{{ $psu->marca }} {{ $psu->nombre }} ({{ $psu->especificaciones['watts'] ?? '' }}W {{ $psu->especificaciones['certificacion'] ?? '' }}) - ${{ number_format($psu->precio, 2) }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    @if($pasoActual == 8)
        <div class="space-y-6">
            <div class="bg-zinc-950 rounded-xl border border-zinc-800 overflow-hidden">
                <table class="w-full text-left text-sm text-zinc-300">
                    <thead class="bg-zinc-900/50 border-b border-zinc-800 text-xs uppercase text-zinc-500">
                        <tr>
                            <th class="px-4 py-3">Componente</th>
                            <th class="px-4 py-3 text-center">Cant.</th>
                            <th class="px-4 py-3 text-right">Precio Unit.</th>
                            <th class="px-4 py-3 text-right">Importe</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50">
                        @foreach($resumenComponentes as $item)
                            @if($item['modelo'])
                                <tr class="hover:bg-zinc-900 transition-colors">
                                    <td class="px-4 py-3 flex items-center gap-3">
                                        <div class="w-10 h-10 rounded bg-zinc-800 border border-zinc-700 flex items-center justify-center overflow-hidden shrink-0">
                                            @if($item['modelo']->imagen)
                                                <img src="{{ Storage::url($item['modelo']->imagen) }}" class="w-full h-full object-cover">
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-white">{{ $item['modelo']->nombre }}</div>
                                            <div class="text-xs text-zinc-500">{{ $item['modelo']->category->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ $item['cantidad'] }}</td>
                                    <td class="px-4 py-3 text-right font-mono">${{ number_format($item['modelo']->precio, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-blue-400 font-bold">${{ number_format($item['modelo']->precio * $item['cantidad'], 2) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="p-6 bg-zinc-900 border-t border-zinc-800 flex justify-end">
                    <div class="w-64 space-y-2 text-sm">
                        <div class="flex justify-between text-zinc-400">
                            <span>Subtotal:</span>
                            <span class="font-mono">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-400">
                            <span>IVA (16%):</span>
                            <span class="font-mono">${{ number_format($iva, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-white font-bold text-xl pt-3 border-t border-zinc-700 mt-2">
                            <span>Total:</span>
                            <span class="font-mono">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-8 pt-6 border-t border-zinc-800 flex justify-between">
        @if($pasoActual > 1)
            <flux:button wire:click="pasoAnterior" variant="subtle" class="text-zinc-400 hover:text-white">
                &larr; Atrás
            </flux:button>
        @else
            <div></div>
        @endif

        @if($pasoActual < 8)
            @php
                $deshabilitado = ($pasoActual == 1 && empty($cpu_id))
                    || ($pasoActual == 2 && empty($motherboard_id))
                    || ($pasoActual == 3 && empty($ram_id))
                    || ($pasoActual == 4 && empty($storage_id))
                    || ($pasoActual == 5 && empty($gpu_id))
                    || ($pasoActual == 6 && empty($case_id))
                    || ($pasoActual == 7 && empty($psu_id));
            @endphp
            <flux:button
    wire:click="siguientePaso"
    variant="primary"
    class="bg-blue-600 hover:bg-blue-500 text-white"
    :disabled="!$this->puedeAvanzar">
    @if($pasoActual == 7) Generar Resumen @else Siguiente &rarr; @endif
</flux:button>
        @else
            <div class="flex items-center">
                <button 
                    type="button" 
                    onclick="lanzarChequeoIa()"
                    class="bg-purple-600 hover:bg-purple-500 text-white font-bold px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 cursor-pointer shadow-lg shadow-purple-600/20 mr-2 transition-all">
                    <x-flux::icon.sparkles class="w-4 h-4" />
                    Validar con IA
                </button>
                <flux:button wire:click="guardarCotizacion" class="bg-green-600 hover:bg-green-500 text-white font-bold border-none shadow-lg shadow-green-600/20">
                    Confirmar y Guardar Cotización
                </flux:button>
            </div>
        @endif
    </div>

    <!-- MODAL DE LA IA -->
    <div id="modal-ia" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl max-w-2xl w-full p-6 m-4 relative shadow-2xl">
            <div class="flex justify-between items-center mb-4 border-b border-zinc-800 pb-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <x-flux::icon.sparkles class="w-5 h-5 text-blue-400" />
                    Asistente de Compatibilidad IA
                </h3>
                <button onclick="cerrarModalIa()" class="text-zinc-400 hover:text-white cursor-pointer bg-transparent border-none p-0">
                    <x-flux::icon.x-mark class="w-5 h-5" />
                </button>
            </div>
            
            <div id="modal-ia-cargando" class="py-8 text-center text-zinc-400 flex flex-col items-center justify-center gap-3">
                <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="font-mono text-xs uppercase tracking-wider">Iniciando diagnóstico técnico...</p>
            </div>
            
            <div id="modal-ia-resultado" class="hidden text-zinc-300 text-sm overflow-y-auto max-h-[400px] pr-2 space-y-4 prose prose-invert">
                <!-- El contenido parseado irá aquí -->
            </div>
            
            <div class="mt-6 border-t border-zinc-800 pt-4 flex justify-end">
                <button onclick="cerrarModalIa()" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 px-4 py-2 rounded-lg text-sm cursor-pointer">
                    Cerrar Diagnóstico
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        function lanzarChequeoIa() {
            const modal = document.getElementById('modal-ia');
            const cargando = document.getElementById('modal-ia-cargando');
            const resultado = document.getElementById('modal-ia-resultado');
            
            modal.classList.remove('hidden');
            cargando.classList.remove('hidden');
            resultado.classList.add('hidden');
            resultado.innerHTML = '';
            
            // Obtener IDs de Livewire
            const componentIds = [
                @this.get('cpu_id'),
                @this.get('motherboard_id'),
                @this.get('ram_id'),
                @this.get('storage_id'),
                @this.get('gpu_id'),
                @this.get('case_id'),
                @this.get('psu_id')
            ].filter(id => id); // filtrar vacíos

            fetch("{{ route('ai.compatibility') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ components: componentIds })
            })
            .then(res => res.json())
            .then(data => {
                cargando.classList.add('hidden');
                resultado.classList.remove('hidden');
                
                if (data.success) {
                    // Parsear Markdown a HTML con marked
                    resultado.innerHTML = marked.parse(data.analysis);
                } else {
                    resultado.innerHTML = `<p class="text-red-400">Error: ${data.message || 'No se pudo completar el análisis.'}</p>`;
                }
            })
            .catch(err => {
                cargando.classList.add('hidden');
                resultado.classList.remove('hidden');
                resultado.innerHTML = `<p class="text-red-400">Error de conexión: ${err.message}</p>`;
            });
        }
        
        function cerrarModalIa() {
            document.getElementById('modal-ia').classList.add('hidden');
        }
    </script>
</div>