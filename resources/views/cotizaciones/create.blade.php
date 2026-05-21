<x-layouts::app :title="__('Nuevo Ensamble')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-4xl mx-auto w-full">
            
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-white tracking-tight">Configurador de Hardware</h1>
                <p class="text-sm text-zinc-400">Selecciona las piezas para tu nuevo ensamble.</p>
            </div>

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('cotizaciones.store') }}" method="POST">
                @csrf
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm p-6 mb-6 space-y-6">
                    
                    @foreach($categories as $mainCategory)
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center py-4 border-b border-zinc-800/50 last:border-0">
                            <div class="w-full md:w-1/3">
                                <h3 class="text-sm font-bold text-zinc-300 uppercase tracking-widest">{{ $mainCategory->name }}</h3>
                            </div>
                            <div class="w-full md:w-2/3">
                                <select name="components[]" class="w-full bg-zinc-950 border border-zinc-800 text-zinc-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                    <option value="">-- Sin seleccionar --</option>
                                    @foreach($mainCategory->children as $subCategory)
                                        <optgroup label="{{ $subCategory->name }}">
                                            @foreach($subCategory->components as $component)
                                                <option value="{{ $component->id }}">
                                                    {{ $component->nombre }} - ${{ number_format($component->precio, 2) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach

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
</x-layouts::app>