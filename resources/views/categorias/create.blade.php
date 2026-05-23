<x-layouts::app :title="__('Nueva Categoría')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-3xl mx-auto w-full">

            <div class="mb-6">
                <a href="{{ route('categorias.index') }}" class="text-blue-500 hover:text-blue-400 text-sm mb-2 inline-block">← Volver a categorías</a>
                <h1 class="text-2xl font-bold text-white">Nueva Categoría</h1>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm p-6">
                <form action="{{ route('categorias.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <flux:input 
                            name="name" 
                            label="Nombre de la Categoría" 
                            placeholder="Ej. Enfriamiento Líquido" 
                            value="{{ old('name') }}" 
                            required 
                        />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:select name="parent_id" label="Categoría Padre (Opcional)">
                            <option value="">Ninguna (Nivel Superior)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </flux:select>
                        @error('parent_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                        <a href="{{ route('categorias.index') }}" class="px-4 py-2 border border-zinc-700 text-zinc-400 rounded-lg text-sm font-semibold hover:bg-zinc-800 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-lg shadow-blue-500/20 transition-all">
                            Guardar Categoría
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts::app>
