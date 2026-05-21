<x-layouts::app :title="__('Nuevo Componente')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-3xl mx-auto w-full">
            
            <div class="mb-6">
                <a href="{{ route('inventario.index') }}" class="text-blue-500 hover:text-blue-400 text-sm mb-2 inline-block">← Volver al inventario</a>
                <h1 class="text-2xl font-bold text-white">Nuevo Componente</h1>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm p-6">
                <form action="{{ route('inventario.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input name="nombre" label="Nombre Comercial" placeholder="Ej. Ryzen 5 5500X3D" required />
                        
                        <flux:select name="category_id" label="Subcategoría" required>
                            <option value="">Selecciona una opción...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->parent->name ?? '' }})</option>
                            @endforeach
                        </flux:select>

                        <flux:select name="marca" id="marca-select" label="Marca" required>
                            <option value="">Selecciona una marca...</option>
                            <option value="AMD">AMD</option>
                            <option value="Intel">Intel</option>
                            <option value="NVIDIA">NVIDIA</option>
                            <option value="ASUS">ASUS</option>
                            <option value="Gigabyte">Gigabyte</option>
                            <option value="MSI">MSI</option>
                            <option value="Corsair">Corsair</option>
                            <option value="Kingston">Kingston</option>
                            <option value="Crucial">Crucial</option>
                        </flux:select>

                        <flux:input name="modelo" label="Modelo Técnico" placeholder="Ej. 100-100000158BOX" required />
                        
                        <flux:input type="number" step="0.01" name="precio" label="Precio (MXN)" required />
                        <flux:input type="number" name="stock" label="Unidades en Stock" required />
                    </div>

                    <hr class="border-zinc-800">

                    <div>
                        <h4 class="text-sm font-bold text-blue-500 mb-1 uppercase tracking-wider">Reglas de Compatibilidad (Motor IA)</h4>
                        <p class="text-xs text-zinc-500 mb-4">Selecciona los parámetros técnicos exactos para evitar errores de comunicación con Gemini.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <flux:select name="spec_socket" id="socket-select" label="Socket">
                                <option value="">No aplica / Ninguno</option>
                                <option value="AM4" data-brand="AMD">AMD AM4</option>
                                <option value="AM5" data-brand="AMD">AMD AM5</option>
                                <option value="LGA1200" data-brand="Intel">Intel LGA1200</option>
                                <option value="LGA1700" data-brand="Intel">Intel LGA1700</option>
                                <option value="LGA1851" data-brand="Intel">Intel LGA1851</option>
                            </flux:select>

                            <flux:select name="spec_memory_type" label="Tipo de Memoria RAM">
                                <option value="">No aplica / Ninguno</option>
                                <option value="DDR4">DDR4</option>
                                <option value="DDR5">DDR5</option>
                            </flux:select>

                            <flux:input name="spec_watts" type="number" label="Consumo Estimado (Watts)" placeholder="Ej. 65 o 200" />
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <flux:button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white border-none w-full md:w-auto">
                            Guardar Componente
                        </flux:button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const marcaSelect = document.getElementById('marca-select');
            const socketSelect = document.getElementById('socket-select');
            const allSocketOptions = Array.from(socketSelect.options);

            marcaSelect.addEventListener('change', function () {
                const selectedBrand = this.value;

                // Limpiar el select de sockets y dejar la opción por defecto
                socketSelect.innerHTML = '';
                
                // Volver a añadir las opciones que correspondan a la marca
                allSocketOptions.forEach(option => {
                    const optionBrand = option.getAttribute('data-brand');
                    
                    // Mostrar si no tiene marca (opción vacía) o si coincide con la seleccionada
                    if (!optionBrand || optionBrand === selectedBrand) {
                        socketSelect.appendChild(option);
                    }
                });
                
                // Resetear al valor vacío cada que cambie la marca
                socketSelect.value = "";
            });
        });
    </script>
</x-layouts::app>