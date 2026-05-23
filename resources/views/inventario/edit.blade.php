<x-layouts::app :title="__('Editar Componente')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-3xl mx-auto w-full">

            <div class="mb-6">
                <a href="{{ route('inventario.index') }}"
                    class="text-blue-500 hover:text-blue-400 text-sm mb-2 inline-block">← Volver al inventario</a>
                <h1 class="text-2xl font-bold text-white">Editar Componente</h1>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm p-6">
                <form action="{{ route('inventario.update', $inventario) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- PASO 1: TIPO DE COMPONENTE -->
                    <div class="bg-blue-900/10 border border-blue-500/30 p-5 rounded-xl">
                        <h3 class="text-blue-400 font-bold mb-3 uppercase tracking-wider text-sm">Paso 1: Categoría del componente</h3>

                        <flux:select name="category_id" id="tipo-componente" label="Categoría de Hardware" required>
                            <option value="">Selecciona el tipo de componente...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    data-tipo="{{ strtolower($category->name) }}"
                                    {{ $inventario->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- CONTENEDOR DE PASOS 2 Y 3 -->
                    <div id="pasos-detalles" class="space-y-8">

                        <!-- PASO 2: DATOS COMERCIALES -->
                        <div>
                            <h3 class="text-zinc-400 font-bold mb-4 uppercase tracking-wider text-sm border-b border-zinc-800 pb-2">
                                Paso 2: Datos Comerciales</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <flux:select name="marca" id="marca-select" label="Marca" required>
                                    <option value="">Esperando tipo de componente...</option>
                                </flux:select>

                                <flux:input name="nombre" label="Nombre Comercial" placeholder="Ej. Ryzen 5 5500"
                                    value="{{ $inventario->nombre }}" required />
                                <flux:input name="modelo" label="Modelo Técnico (Opcional)"
                                    placeholder="Ej. 100-100000158BOX" value="{{ $inventario->modelo }}" />
                                <flux:input type="number" step="0.01" name="precio" label="Precio (MXN)"
                                    value="{{ $inventario->precio }}" required />
                                <flux:input type="number" name="stock" label="Stock"
                                    value="{{ $inventario->stock }}" required />

                                <!-- IMAGEN -->
                                <div class="col-span-1 md:col-span-2 mt-2">
                                    <label class="block text-sm font-medium text-white mb-2">Fotografía del Componente</label>

                                    @if($inventario->imagen)
                                        <div class="mb-3 flex items-center gap-4">
                                            <img src="{{ Storage::url($inventario->imagen) }}"
                                                class="w-20 h-20 object-cover rounded-lg border border-zinc-700">
                                            <span class="text-xs text-zinc-400">Imagen actual. Sube una nueva para reemplazarla.</span>
                                        </div>
                                    @endif

                                    <input type="file" name="imagen" accept="image/png, image/jpeg, image/webp"
                                        class="block w-full text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500/10 file:text-blue-400 hover:file:bg-blue-500/20 cursor-pointer border border-zinc-800 rounded-lg bg-zinc-900 p-1">
                                </div>

                            </div>
                        </div>

                        <!-- PASO 3: ESPECIFICACIONES DINÁMICAS -->
                        <div id="seccion-especificaciones">
                            <h3 class="text-blue-400 font-bold mb-4 uppercase tracking-wider text-sm border-b border-zinc-800 pb-2">
                                Paso 3: Detalles de Compatibilidad</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div id="campo-socket" class="hidden">
                                    <flux:select name="spec_socket" label="Socket">
                                        <option value="">Selecciona un socket...</option>
                                        <option value="AM4"    data-brand="AMD"   {{ ($inventario->especificaciones['socket'] ?? '') == 'AM4'    ? 'selected' : '' }}>AMD - AM4</option>
                                        <option value="AM5"    data-brand="AMD"   {{ ($inventario->especificaciones['socket'] ?? '') == 'AM5'    ? 'selected' : '' }}>AMD - AM5</option>
                                        <option value="LGA1700" data-brand="Intel" {{ ($inventario->especificaciones['socket'] ?? '') == 'LGA1700' ? 'selected' : '' }}>Intel - LGA1700</option>
                                        <option value="LGA1851" data-brand="Intel" {{ ($inventario->especificaciones['socket'] ?? '') == 'LGA1851' ? 'selected' : '' }}>Intel - LGA1851</option>
                                    </flux:select>
                                </div>

                                <div id="campo-ram" class="hidden">
                                    <div class="grid grid-cols-2 gap-2">
                                        <flux:select name="spec_memory_type" label="Tipo de RAM">
                                            <option value="">Generación...</option>
                                            <option value="DDR4" {{ ($inventario->especificaciones['tipo_memoria'] ?? '') == 'DDR4' ? 'selected' : '' }}>DDR4</option>
                                            <option value="DDR5" {{ ($inventario->especificaciones['tipo_memoria'] ?? '') == 'DDR5' ? 'selected' : '' }}>DDR5</option>
                                        </flux:select>

                                        <div id="wrapper-ram-slots" class="hidden">
                                            <flux:select name="spec_ram_slots" label="Ranuras RAM (Motherboard)">
                                                <option value="">Cantidad...</option>
                                                <option value="2" {{ ($inventario->especificaciones['ram_slots'] ?? '') == '2' ? 'selected' : '' }}>2 ranuras</option>
                                                <option value="4" {{ ($inventario->especificaciones['ram_slots'] ?? '') == '4' ? 'selected' : '' }}>4 ranuras</option>
                                                <option value="8" {{ ($inventario->especificaciones['ram_slots'] ?? '') == '8' ? 'selected' : '' }}>8 ranuras</option>
                                            </flux:select>
                                        </div>
                                    </div>
                                </div>

                                <div id="campo-almacenamiento" class="hidden">
                                    <flux:select name="spec_almacenamiento" label="Tipo de Almacenamiento">
                                        <option value="">Selecciona el formato...</option>
                                        <option value="HDD"   {{ ($inventario->especificaciones['tipo_almacenamiento'] ?? '') == 'HDD'   ? 'selected' : '' }}>Disco Duro (HDD)</option>
                                        <option value="SSD"   {{ ($inventario->especificaciones['tipo_almacenamiento'] ?? '') == 'SSD'   ? 'selected' : '' }}>SSD SATA 2.5"</option>
                                        <option value="NVME"  {{ ($inventario->especificaciones['tipo_almacenamiento'] ?? '') == 'NVME'  ? 'selected' : '' }}>NVMe M.2</option>
                                    </flux:select>
                                </div>

                                <div id="campo-ventilacion" class="hidden">
                                    <flux:select name="spec_ventilacion" label="Tipo de Enfriamiento">
                                        <option value="">Selecciona el tipo...</option>
                                        <option value="Aire"    {{ ($inventario->especificaciones['tipo_ventilacion'] ?? '') == 'Aire'    ? 'selected' : '' }}>Disipador por Aire</option>
                                        <option value="Liquida" {{ ($inventario->especificaciones['tipo_ventilacion'] ?? '') == 'Liquida' ? 'selected' : '' }}>Refrigeración Líquida (AIO)</option>
                                    </flux:select>
                                </div>

                                <div id="campo-fuente-watts" class="hidden">
                                    <flux:input name="spec_watts" type="number" label="Capacidad (Watts)"
                                        placeholder="Ej. 650" value="{{ $inventario->especificaciones['watts'] ?? '' }}" />
                                </div>

                                <div id="campo-fuente-cert" class="hidden">
                                    <flux:select name="spec_certificacion" label="Certificación">
                                        <option value="">Ninguna / Genérica</option>
                                        <option value="80+ White"  {{ ($inventario->especificaciones['certificacion'] ?? '') == '80+ White'  ? 'selected' : '' }}>80+ White</option>
                                        <option value="80+ Bronze" {{ ($inventario->especificaciones['certificacion'] ?? '') == '80+ Bronze' ? 'selected' : '' }}>80+ Bronze</option>
                                        <option value="80+ Gold"   {{ ($inventario->especificaciones['certificacion'] ?? '') == '80+ Gold'   ? 'selected' : '' }}>80+ Gold</option>
                                    </flux:select>
                                </div>

                                <div id="campo-vram" class="hidden">
                                    <flux:input name="spec_vram" type="number" label="Memoria VRAM (GB)"
                                        placeholder="Ej. 8, 12, 16" value="{{ $inventario->especificaciones['vram'] ?? '' }}" />
                                </div>

                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <flux:button type="submit"
                                class="bg-blue-600 hover:bg-blue-500 text-white border-none w-full md:w-auto">
                                Guardar Cambios
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectTipo   = document.getElementById('tipo-componente');
            const selectMarca  = document.getElementById('marca-select');
            const seccionSpecs = document.getElementById('seccion-especificaciones');
            const selectSocket = document.querySelector('select[name="spec_socket"]');

            const cSocket        = document.getElementById('campo-socket');
            const cRam           = document.getElementById('campo-ram');
            const cAlmacenamiento= document.getElementById('campo-almacenamiento');
            const cVentilacion   = document.getElementById('campo-ventilacion');
            const cFuenteWatts   = document.getElementById('campo-fuente-watts');
            const cFuenteCert    = document.getElementById('campo-fuente-cert');
            const cVram          = document.getElementById('campo-vram');
            const wrapperSlots   = document.getElementById('wrapper-ram-slots');

            const marcasPorComponente = {
                'procesador':    ['AMD', 'Intel'],
                'tarjeta madre': ['ASUS', 'Gigabyte', 'MSI', 'ASRock'],
                'ram':           ['Corsair', 'Kingston', 'XPG', 'G.Skill', 'TeamGroup'],
                'almacenamiento':['Western Digital', 'Seagate', 'Samsung', 'Kingston', 'Crucial'],
                'fuente':        ['Corsair', 'EVGA', 'Seasonic', 'Cooler Master', 'XPG'],
                'ventilacion':   ['Noctua', 'Corsair', 'NZXT', 'Cooler Master', 'Thermalright'],
                'grafica':       ['NVIDIA', 'AMD', 'ASUS', 'MSI', 'Gigabyte', 'Zotac'],
                'gabinete':      ['NZXT', 'Corsair', 'Lian Li', 'Fractal Design', 'Cooler Master']
            };

            // Valor actual del componente para preseleccionar
            const marcaActual = "{{ $inventario->marca }}";

            function aplicarCategoria(tipo, marcaPreseleccionar) {
                let categoriaKey = 'general';
                if (tipo.includes('procesador'))                          categoriaKey = 'procesador';
                else if (tipo.includes('madre') || tipo.includes('mother')) categoriaKey = 'tarjeta madre';
                else if (tipo.includes('ram') || tipo.includes('memoria')) categoriaKey = 'ram';
                else if (tipo.includes('almacenamiento'))                  categoriaKey = 'almacenamiento';
                else if (tipo.includes('fuente') || tipo.includes('poder')) categoriaKey = 'fuente';
                else if (tipo.includes('ventilacion'))                     categoriaKey = 'ventilacion';
                else if (tipo.includes('grafica') || tipo.includes('video')) categoriaKey = 'grafica';
                else if (tipo.includes('gabinete'))                        categoriaKey = 'gabinete';

                // Llenar marcas
                selectMarca.innerHTML = '<option value="">Selecciona la marca...</option>';
                const marcas = marcasPorComponente[categoriaKey] || ['Genérica'];
                marcas.forEach(marca => {
                    const opt = document.createElement('option');
                    opt.value = marca;
                    opt.textContent = marca;
                    if (marca === marcaPreseleccionar) opt.selected = true;
                    selectMarca.appendChild(opt);
                });

                // Ocultar todo
                [cSocket, cRam, cAlmacenamiento, cVentilacion, cFuenteWatts, cFuenteCert, cVram].forEach(el => el.classList.add('hidden'));
                seccionSpecs.classList.add('hidden');
                wrapperSlots.classList.add('hidden');

                // Mostrar lo que corresponde
                if (categoriaKey === 'procesador') {
                    seccionSpecs.classList.remove('hidden');
                    cSocket.classList.remove('hidden');
                } else if (categoriaKey === 'tarjeta madre') {
                    seccionSpecs.classList.remove('hidden');
                    cSocket.classList.remove('hidden');
                    cRam.classList.remove('hidden');
                    wrapperSlots.classList.remove('hidden');
                } else if (categoriaKey === 'ram') {
                    seccionSpecs.classList.remove('hidden');
                    cRam.classList.remove('hidden');
                } else if (categoriaKey === 'almacenamiento') {
                    seccionSpecs.classList.remove('hidden');
                    cAlmacenamiento.classList.remove('hidden');
                } else if (categoriaKey === 'fuente') {
                    seccionSpecs.classList.remove('hidden');
                    cFuenteWatts.classList.remove('hidden');
                    cFuenteCert.classList.remove('hidden');
                } else if (categoriaKey === 'ventilacion') {
                    seccionSpecs.classList.remove('hidden');
                    cVentilacion.classList.remove('hidden');
                } else if (categoriaKey === 'grafica') {
                    seccionSpecs.classList.remove('hidden');
                    cVram.classList.remove('hidden');
                }

                // Filtrar sockets según marca
                if (selectSocket && marcaPreseleccionar) {
                    filtrarSockets(marcaPreseleccionar);
                }
            }

            function filtrarSockets(marcaSeleccionada) {
                if (!selectSocket) return;
                Array.from(selectSocket.options).forEach(opcion => {
                    if (opcion.value === '') return;
                    const socketBrand = opcion.getAttribute('data-brand');
                    if (marcaSeleccionada === socketBrand || (marcaSeleccionada !== 'AMD' && marcaSeleccionada !== 'Intel')) {
                        opcion.style.display = 'block';
                        opcion.disabled = false;
                        opcion.removeAttribute('hidden');
                    } else {
                        opcion.style.display = 'none';
                        opcion.disabled = true;
                        opcion.setAttribute('hidden', 'hidden');
                    }
                });
            }

            // Inicializar con la categoría actual al cargar la página
            const opcionActual = selectTipo.options[selectTipo.selectedIndex];
            if (opcionActual && opcionActual.value !== '') {
                aplicarCategoria(opcionActual.getAttribute('data-tipo') || '', marcaActual);
            }

            // Evento cambio de categoría
            selectTipo.addEventListener('change', function () {
                if (this.value === '') return;
                const tipo = this.options[this.selectedIndex].getAttribute('data-tipo') || '';
                aplicarCategoria(tipo, '');
            });

            // Evento cambio de marca
            selectMarca.addEventListener('change', function () {
                filtrarSockets(this.value);
            });
        });
    </script>
</x-layouts::app>