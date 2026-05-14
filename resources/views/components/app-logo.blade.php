@props([
    'sidebar' => false,
])

@php
    $brandName = config('app.name', 'PC Master Builder');
@endphp

@if($sidebar)
    <flux:sidebar.brand :name="$brandName" {{ $attributes }} class="font-heading tracking-tighter uppercase text-white">
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center clip-chamfer bg-cyber-cyan glow-cyan">
            <x-app-logo-icon class="size-5 fill-current text-background" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$brandName" {{ $attributes }} class="font-heading tracking-tighter uppercase text-white">
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center clip-chamfer bg-cyber-cyan glow-cyan">
            <x-app-logo-icon class="size-5 fill-current text-background" />
        </x-slot>
    </flux:brand>
@endif
