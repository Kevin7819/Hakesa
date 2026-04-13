@props(['size' => 'default'])

@php
    $sizes = [
        'sm' => ['container' => 'w-full h-full', 'text' => 'text-3xl'],
        'default' => ['container' => 'w-full h-full', 'text' => 'text-5xl'],
        'lg' => ['container' => 'w-full h-full min-h-[400px]', 'text' => 'text-8xl'],
    ];
    $s = $sizes[$size] ?? $sizes['default'];
@endphp

<div class="{{ $s['container'] }} relative overflow-hidden bg-gradient-to-br from-gracia-primary via-gracia-secondary to-gracia-accent animate-gradient">
    <!-- Decorative circles -->
    <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-sm"></div>
    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white/10 rounded-full blur-sm"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 bg-white/5 rounded-full blur-md"></div>

    <!-- Center GC -->
    <div class="absolute inset-0 flex items-center justify-center">
        <span class="{{ $s['text'] }} font-extrabold text-white/80 drop-shadow-lg select-none">GC</span>
    </div>

    <!-- Subtle dot pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 20px 20px;"></div>
</div>

<style>
@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
.animate-gradient {
    background-size: 200% 200%;
    animation: gradient-shift 8s ease infinite;
}
</style>
