<x-layouts::app :title="auth()->user()->hasRole('admin') ? __('Panel de Administración') : __('Panel de Control')">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="flex h-full w-full flex-1 flex-col overflow-y-auto p-4 md:p-8 bg-zinc-950">
        <div class="max-w-7xl mx-auto flex flex-col gap-6 w-full">
            
            <div class="flex flex-wrap items-center justify-between gap-4 bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-4 px-6">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                    <span class="text-xs uppercase text-zinc-300 font-semibold tracking-wider">
                        Sistema Operativo // {{ auth()->user()->hasRole('admin') ? 'ADMIN' : 'CLIENTE' }}: {{ strtoupper(auth()->user()->name) }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-zinc-500 font-medium">Estado del Sistema</span>
                    <span class="font-mono text-sm text-blue-400 font-bold">ONLINE</span>
                </div>
            </div>

            <!-- TARJETAS SUPERIORES -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tarjeta 1: Cotizaciones -->
                <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between min-h-[140px]">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">
                            {{ auth()->user()->hasRole('admin') ? 'Cotizaciones Totales' : 'Mis Cotizaciones' }}
                        </span>
                        <x-flux::icon.document-text class="w-5 h-5 text-zinc-600" />
                    </div>
                    <span class="text-4xl font-bold text-white mt-auto">{{ $activeQuotes }}</span>
                </div>

                @if(auth()->user()->hasRole('admin'))
                    <!-- Tarjeta 2: Carga de Inventario (Admin) -->
                    <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between min-h-[140px]">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Carga de Inventario</span>
                            <x-flux::icon.cube class="w-5 h-5 text-zinc-600" />
                        </div>
                        <div class="mt-auto">
                            <div class="flex justify-between items-end mb-2">
                                <span class="font-mono text-white font-bold text-xl">{{ $inventoryLoad }}%</span>
                                <span class="text-[10px] font-bold text-green-400 uppercase">Óptimo</span>
                            </div>
                            <div class="w-full bg-zinc-800 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full transition-all duration-1000" style="width: {{ $inventoryLoad }}%;"></div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Tarjeta 2: Total Invertido (Cliente) -->
                    <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between min-h-[140px]">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Total Invertido</span>
                            <x-flux::icon.banknotes class="w-5 h-5 text-zinc-600" />
                        </div>
                        <div class="mt-auto">
                            <span class="text-3xl font-bold text-white font-mono">${{ number_format($totalSales, 2) }}</span>
                            <span class="text-xs text-zinc-500 block mt-1">MXN (IVA Incluido)</span>
                        </div>
                    </div>
                @endif

                <!-- Tarjeta 3: Validaciones IA -->
                <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between min-h-[140px]">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Análisis de IA</span>
                        <x-flux::icon.sparkles class="w-5 h-5 text-blue-500" />
                    </div>
                    <div class="flex items-end gap-2 mt-auto">
                        <span class="text-4xl font-bold text-white">{{ $aiValidations }}</span>
                    </div>
                </div>
            </div>

            <!-- CONTENIDO ESPECÍFICO POR ROL -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 flex-1">
                
                @if(auth()->user()->hasRole('admin'))
                    <!-- VISTA ADMIN: GRÁFICO E INVENTARIO -->
                    <div class="lg:col-span-8 bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between">
                        <div class="mb-6">
                            <h3 class="text-lg text-white font-bold">Componentes Más Populares</h3>
                            <p class="text-sm text-zinc-400">Métrica de demanda según cotizaciones generadas</p>
                        </div>
                        <div class="relative w-full h-64 flex items-center justify-center">
                            @if(count($chartLabels) > 0)
                                <canvas id="popularComponentsChart"></canvas>
                            @else
                                <div class="text-center">
                                    <x-flux::icon.chart-bar class="w-12 h-12 text-zinc-700 mx-auto mb-2" />
                                    <p class="text-sm text-zinc-500">Esperando datos para generar gráfica...</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-6">
                        <!-- Alertas de Stock Bajo -->
                        <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col gap-4">
                            <div>
                                <h3 class="text-sm text-white font-bold uppercase tracking-wider">Alertas de Inventario Bajo</h3>
                                <p class="text-xs text-zinc-500 mt-1">Componentes con stock crítico (< 3 unidades)</p>
                            </div>
                            <div class="divide-y divide-zinc-800">
                                @forelse($lowStockComponents as $item)
                                    <div class="py-2.5 flex justify-between items-center text-xs">
                                        <div>
                                            <div class="font-bold text-white">{{ $item->nombre }}</div>
                                            <div class="text-zinc-500 font-mono">{{ $item->marca }} - {{ $item->modelo ?? 'N/A' }}</div>
                                        </div>
                                        <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-0.5 rounded font-mono font-bold">
                                            {{ $item->stock }} disp.
                                        </span>
                                    </div>
                                @empty
                                    <div class="py-4 text-center text-zinc-600 text-xs">
                                        Todo el catálogo cuenta con stock suficiente.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Acciones Rápidas Admin -->
                        <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col gap-3">
                            <span class="text-xs text-zinc-500 uppercase tracking-widest font-semibold mb-2">Administración rápida</span>
                            
                            <a href="{{ route('inventario.index') }}" class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-500 transition-colors">
                                <x-flux::icon.archive-box class="w-4 h-4" />
                                Gestionar Inventario
                            </a>

                            <a href="{{ route('categorias.index') }}" class="flex items-center justify-center gap-2 w-full bg-zinc-800 border border-zinc-700 text-zinc-300 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-zinc-700 transition-colors">
                                <x-flux::icon.tag class="w-4 h-4" />
                                Gestionar Categorías
                            </a>
                            
                            <a href="{{ route('cotizaciones.index') }}" class="flex items-center justify-center gap-2 w-full bg-zinc-800 border border-zinc-700 text-zinc-300 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-zinc-700 transition-colors">
                                <x-flux::icon.clipboard-document-list class="w-4 h-4" />
                                Ver Todas las Cotizaciones
                            </a>
                        </div>
                    </div>
                @else
                    <!-- VISTA CLIENTE: HISTORIAL DE COTIZACIONES -->
                    <div class="lg:col-span-8 bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col">
                        <div class="mb-6 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg text-white font-bold">Mis Cotizaciones Recientes</h3>
                                <p class="text-sm text-zinc-400">Historial de tus configuraciones y ensambles</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-zinc-300">
                                <thead class="bg-zinc-950/50 border-b border-zinc-800 text-zinc-500 font-semibold uppercase">
                                    <tr>
                                        <th class="px-4 py-3">ID</th>
                                        <th class="px-4 py-3">Componentes</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                        <th class="px-4 py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800/50">
                                    @forelse($myQuotes as $quote)
                                        <tr class="hover:bg-zinc-800/30 transition-colors">
                                            <td class="px-4 py-3 font-mono text-zinc-500">
                                                #{{ $quote->id }}
                                            </td>
                                            <td class="px-4 py-3 max-w-[250px] truncate">
                                                {{ $quote->components->pluck('nombre')->implode(', ') }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono font-bold text-white">
                                                ${{ number_format($quote->total, 2) }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('cotizaciones.show', $quote->id) }}" class="text-zinc-400 hover:text-white transition-colors" title="Ver Detalle">
                                                        <x-flux::icon.eye class="w-4 h-4" />
                                                    </a>
                                                    <a href="{{ route('cotizaciones.pdf', $quote->id) }}" class="text-zinc-400 hover:text-blue-400 transition-colors" title="Descargar PDF">
                                                        <x-flux::icon.document-arrow-down class="w-4 h-4" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-10 text-center text-zinc-500">
                                                Aún no has configurado ninguna PC.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-6">
                        <!-- Constructor de Ensamble Directo -->
                        <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between min-h-[160px] relative overflow-hidden group">
                            <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <x-flux::icon.wrench class="w-32 h-32 text-blue-500" />
                            </div>
                            <div class="z-10 mb-4">
                                <span class="text-xs text-blue-400 uppercase tracking-widest font-semibold">Ensamblador Inteligente</span>
                                <h3 class="text-lg font-bold text-white mt-1">Arma tu Computadora</h3>
                                <p class="text-xs text-zinc-400 mt-2">Usa nuestro constructor paso a paso y valida componentes con IA.</p>
                            </div>
                            <a href="{{ route('cotizaciones.create') }}" class="z-10 flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-500 transition-colors shadow-lg shadow-blue-600/20">
                                <x-flux::icon.plus class="w-4 h-4" />
                                Iniciar Ensamble
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->user()->hasRole('admin') && count($chartLabels) > 0)
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('popularComponentsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Unidades Requeridas',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: '#3b82f6', // blue-500
                        borderRadius: 6,
                        borderSkipped: false,
                        hoverBackgroundColor: '#60a5fa' // blue-400
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#27272a' }, // zinc-800
                            border: { display: false },
                            ticks: { color: '#a1a1aa' } // zinc-400
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { color: '#a1a1aa', font: { size: 11 } } // zinc-400
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#18181b', // zinc-900
                            titleColor: '#ffffff',
                            bodyColor: '#d4d4d8', // zinc-300
                            borderColor: '#27272a',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                        }
                    }
                }
            });
        });
    </script>
    @endif
</x-layouts::app>