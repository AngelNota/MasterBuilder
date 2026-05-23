<div class="bg-surface border border-surface-accent rounded-xl shadow-xl p-6 relative overflow-hidden group">
    <div class="absolute top-0 left-0 w-full h-[2px] bg-cyber-cyan glow-cyan"></div>

    @if (session()->has('error'))
        <div class="mb-4 bg-laser-red/10 border border-laser-red/20 text-laser-red px-4 py-3 rounded-lg font-mono text-xs uppercase tracking-wider">
            [ERROR] // {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-8 border-b border-surface-accent pb-4">
        <h2 class="text-xl font-bold text-white font-heading tracking-wider">
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
        <div class="text-xs text-zinc-500 font-mono">ESTADO: PASO_0{{ $pasoActual }}_DE_08</div>
    </div>

    <!-- PASO 1: CPU -->
    @if($pasoActual == 1)
        <div class="space-y-6">
            <p class="text-zinc-400 text-sm font-sans">El cerebro de la computadora. La marca y modelo definirán qué tarjetas madre puedes usar.</p>
            <flux:select wire:model.live="cpu_id" label="Selecciona el Procesador">
                <option value="">Elige un procesador...</option>
                @foreach($procesadores as $cpu)
                    <option value="{{ $cpu->id }}">{{ $cpu->marca }} {{ $cpu->nombre }} - ${{ number_format($cpu->precio, 2) }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    <!-- PASO 2: MOTHERBOARD -->
    @if($pasoActual == 2)
        <div class="space-y-6">
            <p class="text-zinc-400 text-sm">Placas base compatibles con el socket <strong class="text-cyber-cyan">{{ $cpuSeleccionado->especificaciones['socket'] ?? '' }}</strong>.</p>
            @if($tarjetasMadre->isEmpty())
                <div class="p-4 bg-laser-red/10 border border-laser-red/20 rounded-lg text-laser-red text-sm font-mono uppercase tracking-wider">[INCOMPATIBILIDAD] No hay placas base compatibles en inventario.</div>
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

    <!-- PASO 3: RAM -->
    @if($pasoActual == 3)
        <div class="space-y-6">
            <p class="text-zinc-400 text-sm">Módulos de tipo <strong class="text-cyber-cyan">{{ $mbSeleccionada->especificaciones['tipo_memoria'] ?? '' }}</strong>.</p>
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

    <!-- PASO 4: ALMACENAMIENTO -->
    @if($pasoActual == 4)
        <div class="space-y-6">
            <p class="text-zinc-400 text-sm">Selecciona el almacenamiento (SSD NVMe recomendado para OS).</p>
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

    <!-- PASO 5: GPU -->
    @if($pasoActual == 5)
        <div class="space-y-6">
            <p class="text-zinc-400 text-sm">La potencia gráfica para juegos o renderizado.</p>
            <flux:select wire:model.live="gpu_id" label="Tarjeta Gráfica">
                <option value="">Elige una gráfica...</option>
                @foreach($graficas as $gpu)
                    <option value="{{ $gpu->id }}">{{ $gpu->marca }} {{ $gpu->nombre }} ({{ $gpu->especificaciones['vram'] ?? 'N/A' }}GB) - ${{ number_format($gpu->precio, 2) }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    <!-- PASO 6: CASE -->
    @if($pasoActual == 6)
        <div class="space-y-6">
            <p class="text-zinc-400 text-sm">El chasis donde irá montado todo el hardware.</p>
            <flux:select wire:model.live="case_id" label="Gabinete">
                <option value="">Elige un gabinete...</option>
                @foreach($gabinetes as $case)
                    <option value="{{ $case->id }}">{{ $case->marca }} {{ $case->nombre }} - ${{ number_format($case->precio, 2) }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    <!-- PASO 7: PSU -->
    @if($pasoActual == 7)
        <div class="space-y-6">
            <p class="text-zinc-400 text-sm">Elige una fuente capaz de alimentar tu procesador y tarjeta gráfica.</p>
            <flux:select wire:model.live="psu_id" label="Fuente de Poder">
                <option value="">Elige una fuente...</option>
                @foreach($fuentes as $psu)
                    <option value="{{ $psu->id }}">{{ $psu->marca }} {{ $psu->nombre }} ({{ $psu->especificaciones['watts'] ?? '' }}W {{ $psu->especificaciones['certificacion'] ?? '' }}) - ${{ number_format($psu->precio, 2) }}</option>
                @endforeach
            </flux:select>
        </div>
    @endif

    <!-- PASO 8: RESUMEN -->
    @if($pasoActual == 8)
        <div class="space-y-6">
            <div class="bg-zinc-950 rounded-xl border border-surface-accent overflow-hidden">
                <table class="w-full text-left text-sm text-zinc-300">
                    <thead class="bg-zinc-900/50 border-b border-surface-accent text-xs uppercase text-zinc-500 font-mono">
                        <tr>
                            <th class="px-4 py-3">Componente</th>
                            <th class="px-4 py-3 text-center">Cant.</th>
                            <th class="px-4 py-3 text-right">Precio Unit.</th>
                            <th class="px-4 py-3 text-right">Importe</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        @foreach($resumenComponentes as $item)
                            @if($item['modelo'])
                                <tr class="hover:bg-zinc-900/30 transition-colors">
                                    <td class="px-4 py-3 flex items-center gap-3">
                                        <div class="w-10 h-10 rounded bg-zinc-900 border border-surface-accent flex items-center justify-center overflow-hidden shrink-0">
                                            @if($item['modelo']->imagen)
                                                <img src="{{ Storage::url($item['modelo']->imagen) }}" class="w-full h-full object-cover">
                                            @else
                                                <x-flux::icon.wrench class="w-4 h-4 text-zinc-600" />
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-white">{{ $item['modelo']->nombre }}</div>
                                            <div class="text-[10px] text-zinc-500 font-mono uppercase">{{ $item['modelo']->category->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono">{{ $item['cantidad'] }}</td>
                                    <td class="px-4 py-3 text-right font-mono">${{ number_format($item['modelo']->precio, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-cyber-cyan font-bold">${{ number_format($item['modelo']->precio * $item['cantidad'], 2) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="p-6 bg-surface border-t border-surface-accent flex justify-end">
                    <div class="w-64 space-y-2 text-sm">
                        <div class="flex justify-between text-zinc-400">
                            <span class="font-mono text-xs uppercase">Subtotal:</span>
                            <span class="font-mono font-bold text-white">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-400">
                            <span class="font-mono text-xs uppercase">IVA (16%):</span>
                            <span class="font-mono font-bold text-white">${{ number_format($iva, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-white font-bold text-xl pt-3 border-t border-surface-accent mt-2">
                            <span class="font-heading tracking-wider">TOTAL MXN:</span>
                            <span class="font-mono text-cyber-cyan glow-cyan">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- CONTROLES NAVEGACIÓN -->
    <div class="mt-8 pt-6 border-t border-surface-accent flex justify-between">
        @if($pasoActual > 1)
            <button 
                type="button" 
                wire:click="pasoAnterior" 
                class="bg-transparent border border-surface-accent text-zinc-400 hover:text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all cursor-pointer">
                &larr; ATRÁS
            </button>
        @else
            <div></div>
        @endif

        @if($pasoActual < 8)
            <button 
                type="button" 
                wire:click="siguientePaso" 
                class="bg-cyber-cyan text-background font-bold px-5 py-2.5 rounded-lg text-sm flex items-center gap-2 cursor-pointer shadow-lg shadow-cyber-cyan/20 transition-all font-heading hover:bg-white hover:scale-[1.02]"
                :disabled="!$this->puedeAvanzar">
                @if($pasoActual == 7) GENERAR RESUMEN @else SIGUIENTE &rarr; @endif
            </button>
        @else
            <div class="flex items-center">
                <button 
                    type="button" 
                    onclick="lanzarChequeoIa()"
                    class="bg-cyber-magenta hover:bg-white text-background font-bold px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 cursor-pointer shadow-lg shadow-cyber-magenta/20 mr-2 transition-all font-heading">
                    <x-flux::icon.sparkles class="w-4 h-4" />
                    VALIDAR_CON_IA
                </button>
                <button 
                    type="button" 
                    wire:click="guardarCotizacion" 
                    class="bg-toxic-green hover:bg-white text-background font-bold px-5 py-2.5 rounded-lg text-sm flex items-center gap-2 cursor-pointer shadow-lg shadow-toxic-green/20 transition-all font-heading">
                    GUARDAR_ENSAMBLE
                </button>
            </div>
        @endif
    </div>

    <!-- MODAL DE LA IA -->
    <div id="modal-ia" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="bg-surface border border-surface-accent rounded-xl max-w-2xl w-full p-6 m-4 relative shadow-2xl">
            <div class="absolute top-0 left-0 w-full h-[2px] bg-cyber-magenta glow-magenta"></div>
            
            <div class="flex justify-between items-center mb-4 border-b border-surface-accent pb-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2 font-heading tracking-wider">
                    <x-flux::icon.sparkles class="w-5 h-5 text-cyber-cyan glow-cyan" />
                    ASISTENTE DE COMPATIBILIDAD IA
                </h3>
                <button onclick="cerrarModalIa()" class="text-zinc-400 hover:text-white cursor-pointer bg-transparent border-none p-0">
                    <x-flux::icon.x-mark class="w-5 h-5" />
                </button>
            </div>
            
            <div id="modal-ia-cargando" class="py-12 text-center text-zinc-400 flex flex-col items-center justify-center gap-3">
                <div class="w-8 h-8 border-4 border-cyber-cyan border-t-transparent rounded-full animate-spin glow-cyan"></div>
                <p class="font-mono text-xs uppercase tracking-widest text-cyber-cyan">EJECUTANDO_DIAGNÓSTICO_TÉCNICO...</p>
            </div>
            
            <div id="modal-ia-resultado" class="hidden text-zinc-300 text-sm overflow-y-auto max-h-[400px] pr-2 space-y-4 prose prose-invert font-sans">
                <!-- El contenido parseado irá aquí -->
            </div>
            
            <div class="mt-6 border-t border-surface-accent pt-4 flex justify-end">
                <button onclick="cerrarModalIa()" class="bg-zinc-950 border border-surface-accent hover:bg-zinc-900 text-zinc-300 px-5 py-2 rounded-lg text-sm cursor-pointer font-mono text-xs uppercase tracking-wider">
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
            
            const componentIds = [
                @this.get('cpu_id'),
                @this.get('motherboard_id'),
                @this.get('ram_id'),
                @this.get('storage_id'),
                @this.get('gpu_id'),
                @this.get('case_id'),
                @this.get('psu_id')
            ].filter(id => id);

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
                    resultado.innerHTML = marked.parse(data.analysis);
                } else {
                    resultado.innerHTML = `<p class="text-laser-red font-mono uppercase text-xs">[ERROR] No se pudo completar el análisis: ${data.message || 'Error desconocido'}</p>`;
                }
            })
            .catch(err => {
                cargando.classList.add('hidden');
                resultado.classList.remove('hidden');
                resultado.innerHTML = `<p class="text-laser-red font-mono uppercase text-xs">[ERROR_CONEXIÓN] ${err.message}</p>`;
            });
        }
        
        function cerrarModalIa() {
            document.getElementById('modal-ia').classList.add('hidden');
        }
    </script>
</div>