<x-layouts::app :title="__('Panel de Administración')">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="flex h-full w-full flex-1 flex-col overflow-y-auto p-4 md:p-8 bg-zinc-950">
        <div class="max-w-7xl mx-auto flex flex-col gap-6 w-full">
            
            <div class="flex flex-wrap items-center justify-between gap-4 bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-4 px-6">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                    <span class="text-xs uppercase text-zinc-300 font-semibold tracking-wider">
                        Sistema Operativo // ADMIN: {{ strtoupper(auth()->user()->name) }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-zinc-500 font-medium">Uptime</span>
                    <span class="font-mono text-sm text-blue-400 font-bold">99.9%</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between min-h-[140px]">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Cotizaciones</span>
                        <x-flux::icon.document-text class="w-5 h-5 text-zinc-600" />
                    </div>
                    <span class="text-4xl font-bold text-white mt-auto">{{ $activeQuotes }}</span>
                </div>

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

                <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between min-h-[140px]">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Validaciones IA</span>
                        <x-flux::icon.sparkles class="w-5 h-5 text-blue-500" />
                    </div>
                    <div class="flex items-end gap-2 mt-auto">
                        <span class="text-4xl font-bold text-white">{{ $aiValidations }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 flex-1">
                
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
                    <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col justify-between min-h-[160px] relative overflow-hidden group">
                        <div class="absolute right-0 bottom-0 translate-x-4 translate-y-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <x-flux::icon.banknotes class="w-32 h-32 text-blue-500" />
                        </div>
                        <div class="z-10">
                            <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Volumen Cotizado</span>
                            <h2 class="text-3xl font-bold text-white mt-2">
                                ${{ number_format($totalSales, 2) }} <span class="text-sm text-zinc-500 font-normal">MXN</span>
                            </h2>
                        </div>
                        <p class="text-xs text-zinc-500 mt-4 z-10">Incluye 16% de IVA</p>
                    </div>

                    <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl p-6 flex flex-col gap-3">
                        <span class="text-xs text-zinc-500 uppercase tracking-widest font-semibold mb-2">Acciones Rápidas</span>
                        
                        <a href="#" class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-500 transition-colors">
                            <x-flux::icon.archive-box class="w-4 h-4" />
                            Gestionar Inventario
                        </a>
                        
                        <a href="#" class="flex items-center justify-center gap-2 w-full bg-zinc-800 border border-zinc-700 text-zinc-300 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-zinc-700 transition-colors">
                            <x-flux::icon.clipboard-document-list class="w-4 h-4" />
                            Ver Cotizaciones
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($chartLabels) > 0)
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