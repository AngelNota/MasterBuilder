<x-layouts::app :title="__('Inventario')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-7xl mx-auto w-full">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Gestión de Inventario</h1>
                    <p class="text-sm text-zinc-400">Control de hardware y stock</p>
                </div>
                <a href="{{ route('inventario.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    + Agregar Componente
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm text-zinc-300">
                    <thead class="bg-zinc-900/50 border-b border-zinc-800 text-xs uppercase text-zinc-500">
                        <tr>
                            <th class="px-6 py-4">Componente</th>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4">Precio</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse ($components as $item)
                            <tr class="hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $item->nombre }}</div>
                                    <div class="text-xs text-zinc-500">{{ $item->marca }} - {{ $item->modelo }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-zinc-800 text-zinc-300 px-2.5 py-1 rounded-md text-xs border border-zinc-700">
                                        {{ $item->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-blue-400 font-bold">
                                    ${{ number_format($item->precio, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono {{ $item->stock > 5 ? 'text-green-400' : 'text-red-400' }}">{{ $item->stock }} u.</span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-3">
                                    <form action="{{ route('inventario.destroy', $item) }}" method="POST" onsubmit="return confirm('¿Borrar componente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-zinc-400 hover:text-red-500">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-zinc-500">Sin componentes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-zinc-800">
                    {{ $components->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>