<x-layouts::app :title="__('Inventario')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-7xl mx-auto w-full">
            
            <!-- ENCABEZADO -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Gestión de Inventario</h1>
                    <p class="text-sm text-zinc-400 mt-1">Control de hardware por categorías y marcas</p>
                </div>
                <a href="{{ route('inventario.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Nuevo Componente
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

            <!-- MENÚ DE PESTAÑAS (TABS) -->
            <div class="border-b border-zinc-800 mb-8">
                <nav class="flex space-x-1 overflow-x-auto pb-px" aria-label="Tabs">
                    @foreach($categories as $category)
                        <a href="{{ route('inventario.index', ['tab' => $category->name]) }}" 
                           class="whitespace-nowrap px-4 py-3 border-b-2 font-medium text-sm transition-colors 
                           {{ $activeTab == $category->name 
                                ? 'border-blue-500 text-blue-400 bg-blue-500/5' 
                                : 'border-transparent text-zinc-400 hover:text-zinc-200 hover:border-zinc-700 hover:bg-zinc-800/50' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- CONTENIDO DE LA PESTAÑA ACTIVA AGRUPADO POR MARCA -->
            @forelse ($components as $marca => $items)
                <div class="mb-10">
                    
                    <!-- Etiqueta de la Marca -->
                    <div class="flex items-center gap-3 mb-4 pl-2">
                        <div class="w-2 h-6 bg-blue-500 rounded-full"></div>
                        <h2 class="text-xl font-bold text-white tracking-wider">{{ $marca }}</h2>
                        <span class="bg-zinc-800 text-zinc-400 text-xs px-2 py-0.5 rounded-full border border-zinc-700">{{ $items->count() }} items</span>
                    </div>

                    <!-- Tabla de Componentes por Marca -->
                    <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl overflow-hidden">
                        <table class="w-full text-left text-sm text-zinc-300">
                            <thead class="bg-zinc-950/50 border-b border-zinc-800 text-xs uppercase text-zinc-500 font-semibold">
                                <tr>
                                    <th class="px-6 py-4 w-1/3">Modelo y Nombre</th>
                                    <th class="px-6 py-4 w-1/3">Especificaciones Técnicas</th>
                                    <th class="px-6 py-4 text-center">Stock</th>
                                    <th class="px-6 py-4 text-right">Precio</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/50">
                                @foreach ($items as $item)
                                    <tr class="hover:bg-zinc-800/30 transition-colors group">
                                        
                                        <!-- INFO PRINCIPAL -->
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-white text-base">{{ $item->nombre }}</span>
                                                <div class="text-xs text-zinc-500 mt-1">{{ $item->modelo ?? 'Sin modelo específico' }}</div>
                                            </div>
                                        </td>

                                        <!-- ESPECIFICACIONES DINÁMICAS (JSON) -->
                                        <td class="px-6 py-4">
                                            @php
                                                $specs = is_string($item->especificaciones) ? json_decode($item->especificaciones, true) : $item->especificaciones;
                                            @endphp
                                            
                                            <div class="flex flex-wrap gap-2">
                                                @if($specs)
                                                    @if(!empty($specs['socket'])) 
                                                        <span class="bg-blue-500/10 text-blue-400 text-xs px-2.5 py-1 rounded-md border border-blue-500/20 font-medium">Socket: {{ $specs['socket'] }}</span> 
                                                    @endif
                                                    
                                                    @if(!empty($specs['tipo_memoria'])) 
                                                        <span class="bg-purple-500/10 text-purple-400 text-xs px-2.5 py-1 rounded-md border border-purple-500/20 font-medium">RAM: {{ $specs['tipo_memoria'] }}</span> 
                                                    @endif
                                                    
                                                    @if(!empty($specs['vram'])) 
                                                        <span class="bg-green-500/10 text-green-400 text-xs px-2.5 py-1 rounded-md border border-green-500/20 font-medium">{{ $specs['vram'] }} GB VRAM</span> 
                                                    @endif
                                                    
                                                    @if(!empty($specs['watts'])) 
                                                        <span class="bg-red-500/10 text-red-400 text-xs px-2.5 py-1 rounded-md border border-red-500/20 font-medium">{{ $specs['watts'] }}W</span> 
                                                    @endif
                                                    
                                                    @if(!empty($specs['certificacion'])) 
                                                        <span class="bg-yellow-500/10 text-yellow-500 text-xs px-2.5 py-1 rounded-md border border-yellow-500/20 font-medium">{{ $specs['certificacion'] }}</span> 
                                                    @endif
                                                    
                                                    @if(!empty($specs['tipo_almacenamiento'])) 
                                                        <span class="bg-zinc-800 text-zinc-300 text-xs px-2.5 py-1 rounded-md border border-zinc-700 font-medium">{{ $specs['tipo_almacenamiento'] }}</span> 
                                                    @endif
                                                    
                                                    @if(!empty($specs['tipo_ventilacion'])) 
                                                        <span class="bg-cyan-500/10 text-cyan-400 text-xs px-2.5 py-1 rounded-md border border-cyan-500/20 font-medium">{{ $specs['tipo_ventilacion'] }}</span> 
                                                    @endif
                                                @else
                                                    <span class="text-zinc-600 text-xs italic">Sin especificaciones extra</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- STOCK -->
                                        <td class="px-6 py-4 text-center">
                                            @if($item->stock > 5)
                                                <span class="inline-flex items-center gap-1 text-green-400 font-mono bg-green-400/10 px-2 py-1 rounded-md border border-green-400/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                                    {{ $item->stock }} u.
                                                </span>
                                            @elseif($item->stock > 0)
                                                <span class="inline-flex items-center gap-1 text-yellow-400 font-mono bg-yellow-400/10 px-2 py-1 rounded-md border border-yellow-400/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                                                    {{ $item->stock }} u.
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-red-400 font-mono bg-red-400/10 px-2 py-1 rounded-md border border-red-400/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                                    Agotado
                                                </span>
                                            @endif
                                        </td>

                                        <!-- PRECIO -->
                                        <td class="px-6 py-4 text-right">
                                            <span class="font-mono text-white font-bold text-base">
                                                ${{ number_format($item->precio, 2) }}
                                            </span>
                                            <div class="text-[10px] text-zinc-500 uppercase tracking-widest mt-0.5">MXN</div>
                                        </td>

                                        <!-- ACCIONES -->
                                        <td class="px-6 py-4 text-right">
    <div class="flex items-center justify-end gap-1">
        <!-- EDITAR -->
        <a href="{{ route('inventario.edit', $item) }}" 
           class="text-zinc-500 hover:text-blue-400 transition-colors p-2 hover:bg-blue-500/10 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </a>

        <!-- ELIMINAR (el que ya tienes) -->
        <form action="{{ route('inventario.destroy', $item) }}" method="POST" 
              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este componente?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-zinc-500 hover:text-red-500 transition-colors p-2 hover:bg-red-500/10 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </form>
    </div>
</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <!-- ESTADO VACÍO DE LA PESTAÑA -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-12 text-center flex flex-col items-center justify-center mt-8">
                    <div class="bg-zinc-800/50 p-4 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">No hay hardware registrado</h3>
                    <p class="text-zinc-400 text-sm max-w-sm mb-6">Aún no tienes ningún componente en la categoría de <strong>{{ $activeTab }}</strong>. Agrégalos desde el botón superior.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-layouts::app>