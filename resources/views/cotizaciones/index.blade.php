<x-layouts::app :title="__('Mis Cotizaciones')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-7xl mx-auto w-full">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-white">Centro de Cotizaciones</h1>
                    <p class="text-sm text-zinc-400">Historial de ensambles generados</p>
                </div>
                <a href="{{ route('cotizaciones.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    + Nuevo Ensamble
                </a>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 shadow-sm rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm text-zinc-300">
                    <thead class="bg-zinc-900/50 border-b border-zinc-800 text-xs uppercase text-zinc-500">
                        <tr>
                            <th class="px-6 py-4">ID Folio</th>
                            <th class="px-6 py-4">Fecha</th>
                            @if(auth()->user()->hasRole('admin'))
                                <th class="px-6 py-4">Cliente</th>
                            @endif
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse ($quotes as $quote)
                            <tr class="hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-white">#{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4">{{ $quote->created_at->format('d M Y') }}</td>
                                @if(auth()->user()->hasRole('admin'))
                                    <td class="px-6 py-4">{{ $quote->user->name }}</td>
                                @endif
                                <td class="px-6 py-4 font-mono text-blue-400 font-bold">${{ number_format($quote->total, 2) }}</td>
                                <td class="px-6 py-4 text-right flex justify-end gap-3">
                                    <a href="{{ route('cotizaciones.show', $quote) }}" class="text-blue-500 hover:text-blue-400 font-medium">Ver Detalle</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('admin') ? 5 : 4 }}" class="px-6 py-12 text-center text-zinc-500">No hay cotizaciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-zinc-800">
                    {{ $quotes->links() }}
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>