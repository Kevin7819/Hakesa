@props(['status', 'size' => 'sm'])

@php
    $colors = [
        'pending' => 'bg-hakesa-yellow/20 text-yellow-700',
        'confirmed' => 'bg-blue-100 text-blue-700',
        'in_progress' => 'bg-hakesa-teal/20 text-teal-700',
        'completed' => 'bg-green-100 text-green-700',
        'sent' => 'bg-hakesa-pink/20 text-pink-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];
    $labels = [
        'pending' => 'Pendiente',
        'confirmed' => 'Confirmado',
        'in_progress' => 'En Proceso',
        'completed' => 'Completado',
        'sent' => 'Enviado',
        'cancelled' => 'Cancelado',
    ];
    $sizeClasses = $size === 'lg'
        ? 'px-4 py-2 text-sm font-bold rounded-xl'
        : 'inline-block px-2.5 py-1 text-xs font-semibold rounded-full';
@endphp

<span {{ $attributes->merge(['class' => $sizeClasses . ' ' . ($colors[$status] ?? 'bg-gray-100 text-gray-600')]) }}>
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
