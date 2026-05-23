<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-zinc-300 antialiased selection:bg-cyber-magenta selection:text-white flex flex-col overflow-x-hidden">
        <div class="grain-overlay"></div>
        
        <div class="fixed inset-0 pointer-events-none opacity-20">
            <div class="absolute inset-0" style="background-image: linear-gradient(rgba(0, 240, 255, 0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 240, 255, 0.1) 1px, transparent 1px); background-size: 100px 100px;"></div>
        </div>

        <header class="relative z-10 w-full px-6 py-8 flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 border-2 border-cyber-cyan clip-chamfer flex items-center justify-center glow-cyan">
                    <x-app-logo-icon class="size-6 fill-current text-cyber-cyan" />
                </div>
                <span class="font-heading text-xl font-bold tracking-tighter text-white uppercase">{{ config('app.name', 'PC Master Builder') }}</span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-6 font-mono text-sm uppercase">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-cyber-cyan hover:text-white transition-colors">> PANEL_CONTROL</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-cyber-cyan transition-colors text-zinc-500">_LOGIN</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-1.5 border border-cyber-magenta text-cyber-magenta clip-chamfer-sm glow-magenta hover:bg-cyber-magenta hover:text-white transition-all">_REGISTRO</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="relative z-10 flex-1 flex flex-col items-center justify-center px-6 py-20 text-center max-w-5xl mx-auto">
            <div class="mb-4 inline-block px-3 py-1 border border-toxic-green/30 text-toxic-green font-mono text-[10px] tracking-widest clip-chamfer-sm uppercase">
                Sistema_v1.0_ESTABLE // Núcleo_Gemini_Conectado
            </div>
            
            <h1 class="text-6xl md:text-8xl font-heading font-black leading-none mb-6 tracking-tighter text-white">
                ARMA TU <span class="text-cyber-cyan glow-cyan">ESTACIÓN</span><br>DE COMANDO
            </h1>
            
            <p class="text-lg md:text-xl text-zinc-400 max-w-2xl mb-12 font-sans font-light leading-relaxed">
                El configurador de hardware más avanzado. Validación de compatibilidad en tiempo real impulsada por <span class="text-cyber-magenta font-mono">Gemini AI</span>. Diseña tu próximo ensamble con precisión absoluta.
            </p>

            <div class="flex flex-col md:flex-row gap-6 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-10 py-4 bg-cyber-cyan text-background font-heading font-bold text-xl clip-chamfer hover:bg-white hover:scale-105 transition-all glow-cyan">
                        ACCEDER_AL_SISTEMA
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-10 py-4 bg-cyber-cyan text-background font-heading font-bold text-xl clip-chamfer hover:bg-white hover:scale-105 transition-all glow-cyan">
                        INICIAR_ENSAMBLE
                    </a>
                @endauth
            </div>

            <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-12 w-full border-t border-surface-accent pt-12 text-left font-mono">
                <div>
                    <p class="text-[10px] text-zinc-600 uppercase mb-2">> INVENTARIO</p>
                    <p class="text-2xl text-white">Stock_Real</p>
                </div>
                <div>
                    <p class="text-[10px] text-zinc-600 uppercase mb-2">> VALIDACIÓN</p>
                    <p class="text-2xl text-white">Gemini_API</p>
                </div>
                <div>
                    <p class="text-[10px] text-zinc-600 uppercase mb-2">> EXPORTACIÓN</p>
                    <p class="text-2xl text-white">Formatos_PDF</p>
                </div>
                <div>
                    <p class="text-[10px] text-zinc-600 uppercase mb-2">> SEGURIDAD</p>
                    <p class="text-2xl text-toxic-green">Sanctum_Auth</p>
                </div>
            </div>
        </main>

        <footer class="relative z-10 w-full px-6 py-12 border-t border-surface-accent mt-20">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8 text-zinc-600 font-mono text-[10px]">
                <p>&copy; {{ date('Y') }} PC_MASTER_BUILDER // TODOS_LOS_DERECHOS_RESERVADOS</p>
                <div class="flex gap-8">
                    <span class="hover:text-cyber-cyan transition-colors cursor-default">MODO_ADMINISTRADOR</span>
                    <span class="hover:text-cyber-cyan transition-colors cursor-default">ESTADO_RED</span>
                </div>
            </div>
        </footer>
    </body>
</html>