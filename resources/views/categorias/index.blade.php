<x-layouts::app :title="__('Categorías')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-7xl mx-auto w-full">
            
            <!-- ENCABEZADO -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Gestión de Categorías</h1>
                    <p class="text-sm text-zinc-400 mt-1">Administración de la taxonomía del catálogo de hardware</p>
                </div>
                <a href="{{ route('categorias.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Nueva Categoría
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- TABLA DE CATEGORÍAS -->
            <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm text-zinc-300">
                    <thead class="bg-zinc-950/50 border-b border-zinc-800 text-xs uppercase text-zinc-500 font-semibold">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nombre de Categoría</th>
                            <th class="px-6 py-4">Categoría Padre</th>
                            <th class="px-6 py-4 text-center">Componentes</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-zinc-800/30 transition-colors group">
                                <td class="px-6 py-4 font-mono text-zinc-500">
                                    #{{ $category->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-white text-base">{{ $category->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($category->parent)
                                        <span class="bg-zinc-800 text-zinc-400 text-xs px-2.5 py-1 rounded-md border border-zinc-700 font-medium">
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-zinc-600 text-xs font-mono">Nivel Superior</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-500/10 text-blue-400 text-xs px-2.5 py-1 rounded-full border border-blue-500/20 font-bold">
                                        {{ $category->components_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('categorias.edit', $category->id) }}" class="text-zinc-400 hover:text-blue-400 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('categorias.destroy', $category->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-zinc-400 hover:text-red-500 transition-colors bg-transparent border-none p-0 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-zinc-500">
                                    No hay categorías registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÓN -->
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
            
        </div>
    </div>
</x-layouts::app>
