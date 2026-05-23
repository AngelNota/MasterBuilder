<x-layouts::app :title="auth()->user()->hasRole('admin') ? __('Panel de Administración') : __('Panel de Control')">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="flex h-full w-full flex-1 flex-col overflow-y-auto p-4 md:p-8 bg-zinc-950">
        <div class="max-w-7xl mx-auto flex flex-col gap-6 w-full">
            
            <!-- STATUS HEADER -->
            <div class="flex flex-wrap items-center justify-between gap-4 bg-surface border border-surface-accent shadow-lg rounded-xl p-4 px-6 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-cyber-cyan via-cyber-magenta to-transparent"></div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-cyber-cyan animate-pulse glow-cyan"></div>
                    <span class="text-xs uppercase text-zinc-300 font-mono tracking-wider font-semibold">
                        SISTEMA_OPERATIVO // {{ auth()->user()->hasRole('admin') ? 'ADMIN' : 'CLIENTE' }}: <span class="text-white">{{ strtoupper(auth()->user()->name) }}</span>
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-zinc-500 font-mono uppercase">Estado_Red:</span>
                    <span class="font-mono text-xs text-toxic-green font-bold glow-green">ONLINE // SECURE</span>
                </div>
            </div>

            <!-- TARJETAS SUPERIORES -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tarjeta 1: Cotizaciones -->
                <div class="bg-surface border border-surface-accent shadow-md rounded-xl p-6 flex flex-col justify-between min-h-[140px] relative overflow-hidden group hover:border-cyber-cyan/40 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-cyber-cyan/30 group-hover:bg-cyber-cyan transition-all duration-300"></div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-zinc-400 uppercase tracking-widest font-mono font-semibold">> {{ auth()->user()->hasRole('admin') ? 'COTIZACIONES_TOTALES' : 'MIS_COTIZACIONES' }}</span>
                        <x-flux::icon.document-text class="w-5 h-5 text-cyber-cyan glow-cyan" />
                    </div>
                    <span class="text-4xl font-bold text-white mt-auto tracking-tight font-heading">{{ $activeQuotes }}</span>
                </div>

                @if(auth()->user()->hasRole('admin'))
                    <!-- Tarjeta 2: Carga de Inventario (Admin) -->
                    <div class="bg-surface border border-surface-accent shadow-md rounded-xl p-6 flex flex-col justify-between min-h-[140px] relative overflow-hidden group hover:border-cyber-magenta/40 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-[2px] bg-cyber-magenta/30 group-hover:bg-cyber-magenta transition-all duration-300"></div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs text-zinc-400 uppercase tracking-widest font-mono font-semibold">> CARGA_ALMACÉN</span>
                            <x-flux::icon.cube class="w-5 h-5 text-cyber-magenta glow-magenta" />
                        </div>
                        <div class="mt-auto">
                            <div class="flex justify-between items-end mb-2">
                                <span class="font-mono text-white font-bold text-xl">{{ $inventoryLoad }}%</span>
                                <span class="text-[10px] font-mono font-bold text-toxic-green uppercase tracking-widest">ÓPTIMO</span>
                            </div>
                            <div class="w-full bg-zinc-950 h-2 rounded-full overflow-hidden border border-surface-accent">
                                <div class="bg-gradient-to-r from-cyber-magenta to-cyber-cyan h-full rounded-full transition-all duration-1000" style="width: {{ $inventoryLoad }}%;"></div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Tarjeta 2: Total Invertido (Cliente) -->
                    <div class="bg-surface border border-surface-accent shadow-md rounded-xl p-6 flex flex-col justify-between min-h-[140px] relative overflow-hidden group hover:border-cyber-magenta/40 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-[2px] bg-cyber-magenta/30 group-hover:bg-cyber-magenta transition-all duration-300"></div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs text-zinc-400 uppercase tracking-widest font-mono font-semibold">> TOTAL_INVERTIDO</span>
                            <x-flux::icon.banknotes class="w-5 h-5 text-cyber-magenta glow-magenta" />
                        </div>
                        <div class="mt-auto">
                            <span class="text-3xl font-bold text-white font-mono tracking-tight">${{ number_format($totalSales, 2) }}</span>
                            <span class="text-[10px] text-zinc-500 font-mono block mt-1">MXN (IVA INCLUIDO)</span>
                        </div>
                    </div>
                @endif

                <!-- Tarjeta 3: Validaciones IA -->
                <div class="bg-surface border border-surface-accent shadow-md rounded-xl p-6 flex flex-col justify-between min-h-[140px] relative overflow-hidden group hover:border-toxic-green/40 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-toxic-green/30 group-hover:bg-toxic-green transition-all duration-300"></div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-zinc-400 uppercase tracking-widest font-mono font-semibold">> ANÁLISIS_IA</span>
                        <x-flux::icon.sparkles class="w-5 h-5 text-toxic-green glow-green" />
                    </div>
                    <div class="flex items-end gap-2 mt-auto">
                        <span class="text-4xl font-bold text-white tracking-tight font-heading">{{ $aiValidations }}</span>
                    </div>
                </div>
            </div>

            <!-- CONTENIDO ESPECÍFICO POR ROL -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 flex-1">
                
                @if(auth()->user()->hasRole('admin'))
                    <!-- VISTA ADMIN: GRÁFICO E INVENTARIO -->
                    <div class="lg:col-span-8 bg-surface border border-surface-accent shadow-lg rounded-xl p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-[1px] bg-cyber-cyan/20"></div>
                        <div class="mb-6">
                            <h3 class="text-lg text-white font-bold tracking-wide font-heading">Componentes Más Populares</h3>
                            <p class="text-xs text-zinc-400 font-mono uppercase mt-1">> Demanda según ensambles generados</p>
                        </div>
                        <div class="relative w-full h-64 flex items-center justify-center">
                            @if(count($chartLabels) > 0)
                                <canvas id="popularComponentsChart"></canvas>
                            @else
                                <div class="text-center">
                                    <x-flux::icon.chart-bar class="w-12 h-12 text-zinc-700 mx-auto mb-2" />
                                    <p class="text-sm text-zinc-500 font-mono uppercase">Esperando datos de catálogo...</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-6">
                        <!-- Alertas de Stock Bajo -->
                        <div class="bg-surface border border-surface-accent shadow-lg rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-[1px] bg-laser-red/30"></div>
                            <div>
                                <h3 class="text-sm text-white font-bold font-heading tracking-wider flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-laser-red animate-pulse"></span>
                                    ALERTAS_DE_INVENTARIO
                                </h3>
                                <p class="text-[10px] text-zinc-500 font-mono uppercase mt-1">> Stock crítico menor a 3 piezas</p>
                            </div>
                            <div class="divide-y divide-zinc-800/50">
                                @forelse($lowStockComponents as $item)
                                    <div class="py-3 flex justify-between items-center text-xs">
                                        <div>
                                            <div class="font-bold text-white">{{ $item->nombre }}</div>
                                            <div class="text-zinc-500 font-mono mt-0.5 text-[10px]">{{ $item->marca }} - {{ $item->modelo ?? 'N/A' }}</div>
                                        </div>
                                        <span class="bg-laser-red/10 text-laser-red border border-laser-red/20 px-2 py-0.5 rounded font-mono font-bold text-[10px]">
                                            {{ $item->stock }} PIEZAS
                                        </span>
                                    </div>
                                @empty
                                    <div class="py-6 text-center text-zinc-600 text-xs font-mono uppercase">
                                        Catálogo estable // sin alertas
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Acciones Rápidas Admin -->
                        <div class="bg-surface border border-surface-accent shadow-lg rounded-xl p-6 flex flex-col gap-3 relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-[1px] bg-cyber-cyan/20"></div>
                            <span class="text-xs text-zinc-500 uppercase tracking-widest font-mono font-semibold mb-2">> ADMINISTRACIÓN</span>
                            
                            <a href="{{ route('inventario.index') }}" class="flex items-center justify-center gap-2 w-full bg-cyber-cyan text-background px-4 py-2.5 rounded-lg text-sm font-bold font-heading hover:bg-white transition-all glow-cyan cursor-pointer">
                                <x-flux::icon.archive-box class="w-4 h-4" />
                                INVENTARIO_CRUD
                            </a>

                            <a href="{{ route('categorias.index') }}" class="flex items-center justify-center gap-2 w-full bg-zinc-950 border border-surface-accent text-zinc-300 px-4 py-2.5 rounded-lg text-sm font-bold font-heading hover:bg-zinc-900 transition-all cursor-pointer">
                                <x-flux::icon.tag class="w-4 h-4" />
                                CATEGORÍAS_CRUD
                            </a>
                            
                            <a href="{{ route('cotizaciones.index') }}" class="flex items-center justify-center gap-2 w-full bg-zinc-950 border border-surface-accent text-zinc-300 px-4 py-2.5 rounded-lg text-sm font-bold font-heading hover:bg-zinc-900 transition-all cursor-pointer">
                                <x-flux::icon.clipboard-document-list class="w-4 h-4" />
                                VER_COTIZACIONES
                            </a>
                        </div>
                    </div>
                @else
                    <!-- VISTA CLIENTE: HISTORIAL DE COTIZACIONES -->
                    <div class="lg:col-span-8 bg-surface border border-surface-accent shadow-lg rounded-xl p-6 flex flex-col relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-[1px] bg-cyber-cyan/20"></div>
                        <div class="mb-6 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg text-white font-bold font-heading tracking-wide">Mis Cotizaciones Recientes</h3>
                                <p class="text-xs text-zinc-400 font-mono uppercase mt-1">> Historial técnico de ensamble</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-zinc-300">
                                <thead class="bg-zinc-950/60 border-b border-surface-accent text-zinc-500 font-mono font-semibold uppercase">
                                    <tr>
                                        <th class="px-4 py-3">Folio</th>
                                        <th class="px-4 py-3">Especificación Hardware</th>
                                        <th class="px-4 py-3 text-right">Monto Total</th>
                                        <th class="px-4 py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800/40">
                                    @forelse($myQuotes as $quote)
                                        <tr class="hover:bg-zinc-900/30 transition-colors">
                                            <td class="px-4 py-3 font-mono text-zinc-400">
                                                #{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}
                                            </td>
                                            <td class="px-4 py-3 max-w-[280px] truncate text-zinc-300 font-mono text-[11px]">
                                                {{ $quote->components->pluck('nombre')->implode(' + ') }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono font-bold text-white text-sm">
                                                ${{ number_format($quote->total, 2) }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-center gap-3">
                                                    <a href="{{ route('cotizaciones.show', $quote->id) }}" class="text-zinc-400 hover:text-white transition-colors" title="Detalles">
                                                        <x-flux::icon.eye class="w-4 h-4" />
                                                    </a>
                                                    <a href="{{ route('cotizaciones.pdf', $quote->id) }}" class="text-zinc-400 hover:text-cyber-cyan transition-colors" title="Descargar PDF">
                                                        <x-flux::icon.document-arrow-down class="w-4 h-4" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-12 text-center text-zinc-500 font-mono uppercase tracking-wide">
                                                No se han detectado ensambles en la estación.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-6">
                        <!-- Constructor de Ensamble Directo -->
                        <div class="bg-surface border border-surface-accent shadow-lg rounded-xl p-6 flex flex-col justify-between min-h-[180px] relative overflow-hidden group">
                            <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <x-flux::icon.wrench class="w-32 h-32 text-cyber-cyan" />
                            </div>
                            <div class="absolute top-0 left-0 w-full h-[2px] bg-cyber-cyan/30 group-hover:bg-cyber-cyan transition-all duration-300"></div>
                            <div class="z-10 mb-4">
                                <span class="text-xs text-cyber-cyan uppercase tracking-widest font-mono font-semibold">> ENSAMBLADOR_IA</span>
                                <h3 class="text-xl font-bold font-heading text-white mt-1">Arma tu PC Gamer</h3>
                                <p class="text-xs text-zinc-400 mt-2 font-sans font-light leading-relaxed">Selecciona piezas compatibles validadas en tiempo real por el asistente de inteligencia artificial.</p>
                            </div>
                            <a href="{{ route('cotizaciones.create') }}" class="z-10 flex items-center justify-center gap-2 bg-cyber-cyan text-background px-4 py-2.5 rounded-lg text-sm font-bold font-heading hover:bg-white hover:scale-[1.02] transition-all glow-cyan cursor-pointer">
                                <x-flux::icon.plus class="w-4 h-4" />
                                INICIAR_CONFIGURACIÓN
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
                        backgroundColor: '#00F0FF', // cyber-cyan
                        borderColor: '#00F0FF',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                        hoverBackgroundColor: '#BD00FF' // cyber-magenta
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#1A1A24' }, // surface-accent
                            border: { display: false },
                            ticks: { color: '#a1a1aa', font: { family: 'JetBrains Mono', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { color: '#a1a1aa', font: { family: 'JetBrains Mono', size: 10 } }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F0F13', // surface
                            titleColor: '#ffffff',
                            bodyColor: '#d4d4d8',
                            borderColor: '#1A1A24',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { family: 'Rajdhani', weight: 'bold' },
                            bodyFont: { family: 'JetBrains Mono' }
                        }
                    }
                }
            });
        });
    </script>
    @endif
</x-layouts::app>