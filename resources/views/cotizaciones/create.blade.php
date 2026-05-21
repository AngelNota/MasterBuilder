<x-layouts::app :title="__('Nuevo Ensamble')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-4xl mx-auto w-full">

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-white tracking-tight">Configurador de Hardware</h1>
                <p class="text-sm text-zinc-400">Selecciona las piezas para tu nuevo ensamble. Se validarán automáticamente.</p>
            </div>

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('cotizaciones.store') }}" method="POST" @submit="validateForm">
                @csrf
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm p-6 mb-6 space-y-6" x-data="quoteBuilder()">

                    @foreach($categories as $mainCategory)
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center py-4 border-b border-zinc-800/50 last:border-0">
                            <div class="w-full md:w-1/3">
                                <h3 class="text-sm font-bold text-zinc-300 uppercase tracking-widest">{{ $mainCategory->name }}</h3>
                            </div>
                            <div class="w-full md:w-2/3 space-y-2">
                                <select
                                    name="components[]"
                                    @change="onComponentChange($event, {{ $mainCategory->id }})"
                                    class="w-full bg-zinc-950 border border-zinc-800 text-zinc-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                    <option value="">-- Sin seleccionar --</option>
                                    @foreach($mainCategory->children as $subCategory)
                                        <optgroup label="{{ $subCategory->name }}">
                                            @foreach($subCategory->components as $component)
                                                <option value="{{ $component->id }}" data-price="{{ $component->precio }}">
                                                    {{ $component->nombre }} - ${{ number_format($component->precio, 2) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div x-show="errors['{{ $mainCategory->id }}']" class="text-red-400 text-xs mt-1">
                                    <span x-text="errors['{{ $mainCategory->id }}']"></span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Resumen de Cotización -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 mb-6">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-zinc-400">
                            <span>Subtotal:</span>
                            <span>${{ number_format(0, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-400">
                            <span>IVA (16%):</span>
                            <span>${{ number_format(0, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-white border-t border-zinc-700 pt-2 mt-2">
                            <span>Total:</span>
                            <span>${{ number_format(0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 sticky bottom-6">
                    <a href="{{ route('cotizaciones.index') }}" class="px-6 py-3 bg-zinc-800 text-zinc-300 rounded-lg hover:bg-zinc-700 transition-colors">Cancelar</a>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-500 shadow-lg transition-colors">
                        Generar Cotización
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function quoteBuilder() {
            return {
                selectedComponents: [],
                errors: {},

                onComponentChange(event, categoryId) {
                    const componentId = event.target.value;

                    if (componentId) {
                        this.selectedComponents[categoryId] = parseInt(componentId);
                    } else {
                        delete this.selectedComponents[categoryId];
                    }

                    this.validateCompatibility();
                },

                validateCompatibility() {
                    const ids = Object.values(this.selectedComponents).filter(Boolean);

                    if (ids.length === 0) {
                        this.errors = {};
                        return;
                    }

                    fetch(`/api/components/validate?selected_ids=${ids.join(',')}`, {
                        headers: {
                            'Authorization': `Bearer {{ auth()->user()->currentAccessToken() ?? '' }}`,
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.errors = data.errors || {};
                    })
                    .catch(e => console.error('Error:', e));
                }
            };
        }
    </script>
</x-layouts::app>