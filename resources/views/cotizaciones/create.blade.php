<x-layouts::app :title="__('Nuevo Ensamble')">
    <div class="flex h-full w-full flex-col p-4 md:p-8 bg-zinc-950">
        <div class="max-w-4xl mx-auto w-full">
            
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Configurador de Hardware</h1>
                    <p class="text-sm text-zinc-400">Selecciona las piezas paso a paso. El sistema validará la compatibilidad.</p>
                </div>
                <a href="{{ route('cotizaciones.index') }}" class="text-zinc-400 hover:text-white transition-colors text-sm font-medium">
                    Cancelar
                </a>
            </div>

            <!-- INVOCAMOS AL ASISTENTE WIZARD DE LIVEWIRE -->
            @livewire('quote-wizard')
            
        </div>
    </div>
</x-layouts::app>