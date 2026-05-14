<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-zinc-300 antialiased selection:bg-cyber-magenta selection:text-white">
        <div class="grain-overlay"></div>
        
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <div class="flex h-12 w-12 mb-2 items-center justify-center border-2 border-cyber-cyan glow-cyan clip-chamfer">
                        <x-app-logo-icon class="size-8 fill-current text-cyber-cyan" />
                    </div>
                    <span class="font-heading text-xl tracking-tighter text-white uppercase">{{ config('app.name', 'Laravel') }}</span>
                </a>
                
                <div class="flex flex-col gap-6 p-8 bg-surface border border-surface-accent clip-chamfer relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-cyber-cyan glow-cyan"></div>
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
