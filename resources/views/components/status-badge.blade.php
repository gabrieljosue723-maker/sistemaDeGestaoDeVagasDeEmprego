@props(['status'])

@php
    $estilos = match ($status) {
        'aceite' => 'bg-neutral-900 text-white',
        'rejeitado' => 'bg-white text-neutral-500 border border-neutral-300 line-through',
        default => 'bg-neutral-200 text-neutral-700',
    };

    $rotulo = match ($status) {
        'aceite' => 'Aceite',
        'rejeitado' => 'Recusado',
        default => 'Pendente',
    };
@endphp

<span {{ $attributes->merge(['class' => "shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {$estilos}"]) }}>
    {{ $rotulo }}
</span>
