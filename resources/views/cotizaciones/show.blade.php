<x-layouts::app :title="__('Detalle de Cotización')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-3xl mx-auto w-full">
            
            <div class="flex justify-between items-end mb-6">
                <div>
                    <a href="{{ route('cotizaciones.index') }}" class="text-blue-500 hover:text-blue-400 text-sm mb-2 inline-block">← Volver a mis cotizaciones</a>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Cotización #{{ str_pad($cotizacione->id, 5, '0', STR_PAD_LEFT) }}</h1>
                    <p class="text-sm text-zinc-400">Generada el {{ $cotizacione->created_at->format('d M Y - H:i') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('cotizaciones.reenviar', $cotizacione) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-zinc-800 text-zinc-300 border border-zinc-700 px-4 py-2 rounded-lg text-sm hover:text-white transition-colors flex items-center gap-2 cursor-pointer">
                            <x-flux::icon.paper-airplane class="w-4 h-4"/>
                            Enviar por Correo
                        </button>
                    </form>
                    <a href="{{ route('cotizaciones.pdf', $cotizacione) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2 font-bold shadow-md shadow-blue-600/10">
                        <x-flux::icon.document-arrow-down class="w-4 h-4"/>
                        Exportar PDF
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-left text-sm text-zinc-300">
                    <thead class="bg-zinc-900/50 border-b border-zinc-800 text-xs uppercase text-zinc-500">
                        <tr>
                            <th class="px-6 py-4">Pieza Seleccionada</th>
                            <th class="px-6 py-4 text-center">Cant.</th>
                            <th class="px-6 py-4 text-right">Precio Unit.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @foreach($cotizacione->components as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $item->nombre }}</div>
                                    <div class="text-xs text-zinc-500">{{ $item->marca }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $item->pivot->cantidad }}</td>
                                <td class="px-6 py-4 text-right font-mono">${{ number_format($item->pivot->precio_unitario, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="bg-zinc-950/50 p-6 border-t border-zinc-800 flex justify-end">
                    <div class="w-64 space-y-3 text-sm">
                        <div class="flex justify-between text-zinc-400">
                            <span>Subtotal:</span>
                            <span class="font-mono">${{ number_format($cotizacione->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-400">
                            <span>IVA (16%):</span>
                            <span class="font-mono">${{ number_format($cotizacione->iva, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-white font-bold text-lg pt-3 border-t border-zinc-800">
                            <span>Total MXN:</span>
                            <span class="font-mono">${{ number_format($cotizacione->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>