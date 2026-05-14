<x-layouts::app :title="__('Command Center')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header Info -->
        <div class="flex items-center justify-between border-b border-surface-accent pb-4">
            <div>
                <h1 class="text-3xl font-bold text-cyber-cyan glow-cyan">System Status</h1>
                <p class="font-mono text-xs opacity-60 mt-1">> SYSTEM_CORE_STABLE // USER: {{ strtoupper(auth()->user()->name) }}</p>
            </div>
            <div class="text-right font-mono">
                <p class="text-xs text-cyber-magenta uppercase tracking-tighter">Uptime</p>
                <p class="text-xl">99.98%</p>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid auto-rows-min gap-6 md:grid-cols-3">
            <div class="group relative aspect-video clip-chamfer bg-surface border border-surface-accent p-6 hover:border-cyber-cyan transition-colors">
                <div class="flex flex-col h-full justify-between">
                    <span class="text-xs font-mono text-cyber-cyan">ACTIVE_QUOTES</span>
                    <span class="text-5xl font-heading font-bold">12</span>
                    <div class="w-full bg-surface-accent h-1 mt-4">
                        <div class="bg-cyber-cyan h-full w-[65%] glow-cyan"></div>
                    </div>
                </div>
            </div>
            <div class="group relative aspect-video clip-chamfer bg-surface border border-surface-accent p-6 hover:border-cyber-magenta transition-colors">
                <div class="flex flex-col h-full justify-between">
                    <span class="text-xs font-mono text-cyber-magenta">INVENTORY_LOAD</span>
                    <span class="text-5xl font-heading font-bold">84%</span>
                    <div class="w-full bg-surface-accent h-1 mt-4">
                        <div class="bg-cyber-magenta h-full w-[84%] glow-magenta"></div>
                    </div>
                </div>
            </div>
            <div class="group relative aspect-video clip-chamfer bg-surface border border-surface-accent p-6 hover:border-toxic-green transition-colors">
                <div class="flex flex-col h-full justify-between">
                    <span class="text-xs font-mono text-toxic-green">AI_VALIDATIONS</span>
                    <span class="text-5xl font-heading font-bold">342</span>
                    <div class="flex gap-1 mt-4">
                        <div class="bg-toxic-green h-1 flex-1 glow-green"></div>
                        <div class="bg-toxic-green h-1 flex-1 glow-green"></div>
                        <div class="bg-surface-accent h-1 flex-1"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Workspace Area -->
        <div class="relative flex-1 clip-chamfer bg-surface border border-surface-accent p-8 overflow-hidden">
            <div class="absolute top-0 right-0 p-4 font-mono text-[10px] opacity-20 pointer-events-none">
                LOC_X: 40.7128<br>LOC_Y: -74.0060
            </div>
            
            <div class="h-full flex flex-col justify-center items-center text-center">
                <div class="w-24 h-24 mb-6 border-2 border-dashed border-cyber-cyan/30 rounded-full flex items-center justify-center animate-pulse">
                     <flux:icon.layout-grid class="w-10 h-10 text-cyber-cyan opacity-50" />
                </div>
                <h3 class="text-2xl mb-2">Initialize New Rig</h3>
                <p class="text-zinc-500 max-w-md mx-auto mb-8">Ready to assemble a new high-performance system. The Gemini AI core is standing by for compatibility scanning.</p>
                
                <flux:button class="clip-chamfer bg-cyber-cyan text-background font-bold px-8 py-3 hover:bg-white transition-all transform hover:scale-105">
                    START_CONSTRUCTION
                </flux:button>
            </div>
        </div>
    </div>
</x-layouts::app>
