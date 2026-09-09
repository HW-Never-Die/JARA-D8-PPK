@props(['size' => 'md', 'showText' => true])

@php
    $dimensions = match($size) {
        'sm' => 'w-6 h-6',
        'lg' => 'w-10 h-10',
        default => 'w-8 h-8',
    };
    $textClass = match($size) {
        'sm' => 'text-sm font-semibold tracking-wider',
        'lg' => 'text-xl font-bold tracking-wider',
        default => 'text-base font-bold tracking-wider',
    };
@endphp

<div class="inline-flex items-center gap-3 select-none">
    <!-- Complex Octagonal Optic Lens Casing + Iris Aperture + Prism -->
    <div class="{{ $dimensions }} relative flex items-center justify-center shrink-0">
        <svg viewBox="0 0 100 100" class="w-full h-full drop-shadow-[0_0_12px_rgba(144,59,238,0.35)]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="irisGrad" x1="15" y1="15" x2="85" y2="85" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#903BEE"/>
                    <stop offset="50%" stop-color="#4F46E5"/>
                    <stop offset="100%" stop-color="#2DD4BF"/>
                </linearGradient>
                <linearGradient id="lensRim" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#3B4254"/>
                    <stop offset="50%" stop-color="#1E2230"/>
                    <stop offset="100%" stop-color="#0E1017"/>
                </linearGradient>
            </defs>

            <!-- Octagonal Optic Outer Casing (chamfered octagon) -->
            <polygon points="30,5 70,5 95,30 95,70 70,95 30,95 5,70 5,30" 
                     stroke="url(#lensRim)" stroke-width="2.5" fill="#12141C"/>
            
            <!-- Secondary Inner Octagon Bevel Guide -->
            <polygon points="32,12 68,12 88,32 88,68 68,88 32,88 12,68 12,32" 
                     stroke="#2A2E3D" stroke-width="1.2" fill="none" stroke-dasharray="3 2"/>

            <!-- Circular Optic Barrel -->
            <circle cx="50" cy="50" r="30" stroke="#3A3F55" stroke-width="1.5" fill="#0C0D12"/>

            <!-- Iris Aperture Blades (Mechanical Overlap) -->
            <path d="M 50 20 L 76 35 L 65 60 Z" fill="#903BEE" fill-opacity="0.22" stroke="#903BEE" stroke-width="0.8"/>
            <path d="M 76 35 L 70 70 L 45 68 Z" fill="#7E28E2" fill-opacity="0.25" stroke="#903BEE" stroke-width="0.8"/>
            <path d="M 70 70 L 40 78 L 32 55 Z" fill="#4F46E5" fill-opacity="0.25" stroke="#4F46E5" stroke-width="0.8"/>
            <path d="M 40 78 L 24 55 L 38 35 Z" fill="#3B82F6" fill-opacity="0.22" stroke="#3B82F6" stroke-width="0.8"/>
            <path d="M 24 55 L 30 25 L 55 32 Z" fill="#2DD4BF" fill-opacity="0.22" stroke="#2DD4BF" stroke-width="0.8"/>

            <!-- Prism Core Refraction -->
            <polygon points="50,33 65,60 35,60" stroke="url(#irisGrad)" stroke-width="2" fill="url(#irisGrad)" fill-opacity="0.45"/>
            <circle cx="50" cy="50" r="4.5" fill="#FFFFFF" fill-opacity="0.9"/>
            
            <!-- Cardinal Alignment Marks -->
            <line x1="50" y1="2" x2="50" y2="7" stroke="#903BEE" stroke-width="1.8"/>
            <line x1="50" y1="93" x2="50" y2="98" stroke="#903BEE" stroke-width="1.8"/>
            <line x1="2" y1="50" x2="7" y2="50" stroke="#903BEE" stroke-width="1.8"/>
            <line x1="93" y1="50" x2="98" y2="50" stroke="#903BEE" stroke-width="1.8"/>
        </svg>
    </div>

    @if($showText)
        <div class="flex flex-col leading-tight">
            <span class="{{ $textClass }} text-zinc-100 uppercase font-mono tracking-widest flex items-center gap-1.5">
                JARA
                <span class="text-[9px] font-mono text-violet-400/80 px-1 py-0.5 rounded border border-violet-500/30 bg-violet-500/10 tracking-normal font-normal">v1.0</span>
            </span>
            <span class="text-[10px] tracking-wider uppercase text-zinc-500 font-mono">Task Intelligence</span>
        </div>
    @endif
</div>
