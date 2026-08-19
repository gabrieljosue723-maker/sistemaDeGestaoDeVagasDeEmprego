@props(['variant' => 'dark'])

@php
    $base = 'btn-ripple inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors duration-150';

    $estilos = [
        'dark' => 'bg-neutral-900 text-white hover:bg-black',
        'light' => 'btn-ripple--light bg-white text-neutral-800 border border-neutral-300 hover:border-neutral-500',
        'outline' => 'btn-ripple--light bg-transparent text-neutral-100 border border-neutral-500 hover:border-neutral-300',
        'ghost' => 'btn-ripple--light bg-transparent text-neutral-600 hover:text-neutral-900',
    ];

    $classes = $base . ' ' . ($estilos[$variant] ?? $estilos['dark']);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
