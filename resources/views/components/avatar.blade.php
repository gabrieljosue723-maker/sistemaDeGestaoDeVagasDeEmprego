@props(['user', 'size' => 'md'])

@php
    $tamanhos = [
        'sm' => 'w-9 h-9 text-xs',
        'md' => 'w-12 h-12 text-sm',
        'lg' => 'w-20 h-20 text-xl',
        'xl' => 'w-24 h-24 text-2xl',
    ];
    $classeBase = $tamanhos[$size] ?? $tamanhos['md'];
    $forma = $user->isEmpresa() ? 'rounded-xl' : 'rounded-full';

    $iniciais = collect(explode(' ', trim($user->name)))
        ->filter()
        ->map(fn ($parte) => mb_strtoupper(mb_substr($parte, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

@if($user->temFoto())
    <img src="{{ $user->fotoUrl() }}" alt="{{ $user->name }}"
        {{ $attributes->merge(['class' => "{$classeBase} {$forma} object-cover shrink-0 border border-neutral-200"]) }}>
@else
    <div {{ $attributes->merge(['class' => "{$classeBase} {$forma} shrink-0 bg-neutral-800 text-white font-semibold flex items-center justify-center"]) }}>
        {{ $iniciais ?: '?' }}
    </div>
@endif
