<x-layouts::app :title="__('Nuevo Componente')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-3xl mx-auto w-full">

            <div class="mb-6">
                <a href="{{ route('inventario.index') }}"
                    class="text-blue-500 hover:text-blue-400 text-sm mb-2 inline-block">← Volver al inventario</a>
                <h1 class="text-2xl font-bold text-white">Nuevo Componente</h1>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm p-6">
                <!-- Cambia la línea de tu form actual por esta: -->
                <form action="{{ route('inventario.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf

                    <!-- PASO 1: TIPO DE COMPONENTE -->
                    <div class="bg-blue-900/10 border border-blue-500/30 p-5 rounded-xl">
                        <h3 class="text-blue-400 font-bold mb-3 uppercase tracking-wider text-sm">Paso 1: ¿Qué pieza
                            vamos a registrar?</h3>

                        <flux:select name="category_id" id="tipo-componente" label="Categoría de Hardware" required>
                            <option value="">Selecciona el tipo de componente...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-tipo="{{ strtolower($category->name) }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- CONTENEDOR DE PASOS 2 Y 3 -->
                    <div id="pasos-detalles" class="hidden space-y-8">

                        <!-- PASO 2: MARCA Y DATOS BASE -->
                        <!-- PASO 2: MARCA Y DATOS BASE -->
                        <div>
                            <h3
                                class="text-zinc-400 font-bold mb-4 uppercase tracking-wider text-sm border-b border-zinc-800 pb-2">
                                Paso 2: Datos Comerciales</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <flux:select name="marca" id="marca-select" label="Marca" required>
                                    <option value="">Esperando tipo de componente...</option>
                                </flux:select>

                                <flux:input name="nombre" label="Nombre Comercial" placeholder="Ej. Ryzen 5 5500"
                                    required />
                                <flux:input name="modelo" label="Modelo Técnico (Opcional)"
                                    placeholder="Ej. 100-100000158BOX" />
                                <flux:input type="number" step="0.01" name="precio" label="Precio (MXN)" required />
                                <flux:input type="number" name="stock" label="Stock Inicial" required />

                                <!-- NUEVO CAMPO DE IMAGEN -->
                                <div class="col-span-1 md:col-span-2 mt-2">
                                    <label class="block text-sm font-medium text-white mb-2">Fotografía del
                                        Componente</label>
                                    <input type="file" name="imagen" accept="image/png, image/jpeg, image/webp"
                                        class="block w-full text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500/10 file:text-blue-400 hover:file:bg-blue-500/20 cursor-pointer border border-zinc-800 rounded-lg bg-zinc-900 p-1">
                                </div>

                            </div>
                        </div>

                        <!-- PASO 3: ESPECIFICACIONES DINÁMICAS -->
                        <div id="seccion-especificaciones">
                            <h3
                                class="text-blue-400 font-bold mb-4 uppercase tracking-wider text-sm border-b border-zinc-800 pb-2">
                                Paso 3: Detalles de Compatibilidad</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- BLOQUE: PROCESADOR Y TARJETA MADRE -->
                                <div id="campo-socket" class="hidden">
                                    <flux:select name="spec_socket" label="Socket">
                                        <option value="">Selecciona un socket...</option>
                                        <option value="AM4" data-brand="AMD">AMD - AM4</option>
                                        <option value="AM5" data-brand="AMD">AMD - AM5</option>
                                        <option value="LGA1700" data-brand="Intel">Intel - LGA1700</option>
                                        <option value="LGA1851" data-brand="Intel">Intel - LGA1851</option>
                                    </flux:select>
                                </div>

                                <!-- BLOQUE: RAM Y TARJETA MADRE -->
                                <div id="campo-ram" class="hidden">
                                    <div class="grid grid-cols-2 gap-2">
                                        <flux:select name="spec_memory_type" label="Tipo de RAM">
                                            <option value="">Generación...</option>
                                            <option value="DDR4">DDR4</option>
                                            <option value="DDR5">DDR5</option>
                                        </flux:select>

                                        <!-- NUEVO: SLOTS DE RAM (Solo visible para Tarjetas Madre mediante JS si queremos, o general) -->
                                        <div id="wrapper-ram-slots" class="hidden">
                                            <flux:select name="spec_ram_slots" label="Ranuras RAM (Motherboard)">
                                                <option value="">Cantidad...</option>
                                                <option value="2">2 ranuras (Dual Channel base)</option>
                                                <option value="4">4 ranuras (Expansible)</option>
                                                <option value="8">8 ranuras (Workstation)</option>
                                            </flux:select>
                                        </div>
                                    </div>
                                </div>

                                <!-- BLOQUE: ALMACENAMIENTO -->
                                <div id="campo-almacenamiento" class="hidden">
                                    <flux:select name="spec_almacenamiento" label="Tipo de Almacenamiento">
                                        <option value="">Selecciona el formato...</option>
                                        <option value="HDD">Disco Duro (HDD)</option>
                                        <option value="SSD">SSD SATA 2.5"</option>
                                        <option value="NVME">NVMe M.2</option>
                                    </flux:select>
                                </div>

                                <!-- BLOQUE: VENTILACIÓN -->
                                <div id="campo-ventilacion" class="hidden">
                                    <flux:select name="spec_ventilacion" label="Tipo de Enfriamiento">
                                        <option value="">Selecciona el tipo...</option>
                                        <option value="Aire">Disipador por Aire</option>
                                        <option value="Liquida">Refrigeración Líquida (AIO)</option>
                                    </flux:select>
                                </div>

                                <!-- BLOQUE: FUENTE DE PODER -->
                                <div id="campo-fuente-watts" class="hidden">
                                    <flux:input name="spec_watts" type="number" label="Capacidad (Watts)"
                                        placeholder="Ej. 650" />
                                </div>
                                <div id="campo-fuente-cert" class="hidden">
                                    <flux:select name="spec_certificacion" label="Certificación">
                                        <option value="">Ninguna / Genérica</option>
                                        <option value="80+ White">80+ White</option>
                                        <option value="80+ Bronze">80+ Bronze</option>
                                        <option value="80+ Gold">80+ Gold</option>
                                    </flux:select>
                                </div>

                                <!-- NUEVO BLOQUE: TARJETA DE VIDEO (VRAM) -->
                                <div id="campo-vram" class="hidden">
                                    <flux:input name="spec_vram" type="number" label="Memoria VRAM (GB)"
                                        placeholder="Ej. 8, 12, 16" />
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <flux:button type="submit"
                                class="bg-blue-600 hover:bg-blue-500 text-white border-none w-full md:w-auto">
                                Guardar Componente
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EL SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectTipo = document.getElementById('tipo-componente');
            const contenedorPasos = document.getElementById('pasos-detalles');
            const selectMarca = document.getElementById('marca-select');
            const seccionSpecs = document.getElementById('seccion-especificaciones');

            // Campos específicos
            const selectSocket = document.querySelector('select[name="spec_socket"]');
            const cSocket = document.getElementById('campo-socket');
            const cRam = document.getElementById('campo-ram');
            const cAlmacenamiento = document.getElementById('campo-almacenamiento');
            const cVentilacion = document.getElementById('campo-ventilacion');
            const cFuenteWatts = document.getElementById('campo-fuente-watts');
            const cFuenteCert = document.getElementById('campo-fuente-cert');
            const cVram = document.getElementById('campo-vram'); // El nuevo div de VRAM

            // Diccionario exacto de Marcas por Componente
            const marcasPorComponente = {
                'procesador': ['AMD', 'Intel'],
                'tarjeta madre': ['ASUS', 'Gigabyte', 'MSI', 'ASRock'],
                'ram': ['Corsair', 'Kingston', 'XPG', 'G.Skill', 'TeamGroup'],
                'almacenamiento': ['Western Digital', 'Seagate', 'Samsung', 'Kingston', 'Crucial'],
                'fuente': ['Corsair', 'EVGA', 'Seasonic', 'Cooler Master', 'XPG'],
                'ventilacion': ['Noctua', 'Corsair', 'NZXT', 'Cooler Master', 'Thermalright'],
                'grafica': ['NVIDIA', 'AMD', 'ASUS', 'MSI', 'Gigabyte', 'Zotac'],
                'gabinete': ['NZXT', 'Corsair', 'Lian Li', 'Fractal Design', 'Cooler Master']
            };

            // EVENTO 1: CAMBIO DE CATEGORÍA
            selectTipo.addEventListener('change', function () {
                if (this.value === '') {
                    contenedorPasos.classList.add('hidden');
                    return;
                }

                contenedorPasos.classList.remove('hidden');

                const opcionSeleccionada = this.options[this.selectedIndex];
                const tipo = opcionSeleccionada.getAttribute('data-tipo');

                let categoriaKey = 'general';
                if (tipo.includes('procesador')) categoriaKey = 'procesador';
                else if (tipo.includes('madre') || tipo.includes('mother')) categoriaKey = 'tarjeta madre';
                else if (tipo.includes('ram') || tipo.includes('memoria')) categoriaKey = 'ram';
                else if (tipo.includes('almacenamiento') || tipo.includes('disco')) categoriaKey = 'almacenamiento';
                else if (tipo.includes('fuente') || tipo.includes('poder')) categoriaKey = 'fuente';
                else if (tipo.includes('ventilacion') || tipo.includes('enfriamiento')) categoriaKey = 'ventilacion';
                else if (tipo.includes('grafica') || tipo.includes('video')) categoriaKey = 'grafica';
                else if (tipo.includes('gabinete') || tipo.includes('case')) categoriaKey = 'gabinete';

                // Llenar select de Marcas
                selectMarca.innerHTML = '<option value="">Selecciona la marca...</option>';
                const marcas = marcasPorComponente[categoriaKey] || ['Generica'];
                marcas.forEach(marca => {
                    const opt = document.createElement('option');
                    opt.value = marca;
                    opt.textContent = marca;
                    selectMarca.appendChild(opt);
                });

                // Ocultar todos los campos dinámicos primero
                seccionSpecs.classList.add('hidden');
                cSocket.classList.add('hidden');
                cRam.classList.add('hidden');
                cAlmacenamiento.classList.add('hidden');
                cVentilacion.classList.add('hidden');
                cFuenteWatts.classList.add('hidden');
                cFuenteCert.classList.add('hidden');
                cVram.classList.add('hidden'); // Ocultar VRAM por defecto

                // Mostrar SOLO lo que corresponde
                if (categoriaKey === 'procesador') {
                    seccionSpecs.classList.remove('hidden');
                    cSocket.classList.remove('hidden');
                } 
                else if (categoriaKey === 'tarjeta madre') {
                    seccionSpecs.classList.remove('hidden');
                    cSocket.classList.remove('hidden');
                    cRam.classList.remove('hidden');
                    document.getElementById('wrapper-ram-slots').classList.remove('hidden'); 
                }
                else if (categoriaKey === 'ram') {
                    seccionSpecs.classList.remove('hidden');
                    cRam.classList.remove('hidden');
                    document.getElementById('wrapper-ram-slots').classList.add('hidden');
                }
                else if (categoriaKey === 'almacenamiento') {
                    seccionSpecs.classList.remove('hidden');
                    cAlmacenamiento.classList.remove('hidden');
                }
                else if (categoriaKey === 'fuente') {
                    seccionSpecs.classList.remove('hidden');
                    cFuenteWatts.classList.remove('hidden');
                    cFuenteCert.classList.remove('hidden');
                }
                else if (categoriaKey === 'ventilacion') {
                    seccionSpecs.classList.remove('hidden');
                    cVentilacion.classList.remove('hidden');
                }
                else if (categoriaKey === 'grafica') {
                    // Si es Tarjeta de Video, mostramos la VRAM
                    seccionSpecs.classList.remove('hidden');
                    cVram.classList.remove('hidden');
                }

                // Reiniciamos los sockets al cambiar de categoría
                if (selectSocket) {
                    Array.from(selectSocket.options).forEach(opt => {
                        opt.style.display = 'block';
                        opt.disabled = false;
                        opt.removeAttribute('hidden');
                    });
                    selectSocket.value = "";
                }
            });

            // EVENTO 2: FILTRO DE SOCKETS
            selectMarca.addEventListener('change', function () {
                const marcaSeleccionada = this.value;
                if (!selectSocket) return;

                selectSocket.value = "";

                Array.from(selectSocket.options).forEach(opcion => {
                    if (opcion.value === "") return;

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
            });
        });
    </script>
</x-layouts::app>