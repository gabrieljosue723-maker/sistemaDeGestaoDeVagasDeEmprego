@extends('layouts.app')
@section('title', $candidato->name)

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('candidatos.index') }}" class="text-sm font-medium text-neutral-600 hover:text-neutral-900 transition-colors">
                &larr; Voltar à pesquisa
            </a>
        </div>

        <div class="bg-white border border-neutral-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-7 py-6 bg-neutral-900 flex items-center gap-4">
                <x-avatar :user="$candidato" size="lg" class="border-neutral-700" />
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-white leading-snug">{{ $candidato->name }}</h1>
                    <p class="text-neutral-300 text-sm mt-1">{{ $candidato->email }}</p>
                </div>
            </div>

            <div class="px-7 py-6 space-y-5">

                <div class="flex flex-wrap gap-2">
                    @if(!is_null($candidato->anos_experiencia))
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-neutral-200 text-neutral-800">
                            {{ $candidato->anos_experiencia }} {{ Str::plural('ano', $candidato->anos_experiencia) }} de experiência
                        </span>
                    @endif
                    @if($candidato->localizacao)
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-neutral-100 text-neutral-700 border border-neutral-200">
                            {{ $candidato->localizacao }}
                        </span>
                    @endif
                    @if($candidato->disponibilidade)
                        @php
                            $rotulos = [
                                'imediata' => 'Disponibilidade imediata',
                                'a_combinar' => 'Disponibilidade a combinar',
                                'part_time' => 'Part-time',
                                'full_time' => 'Full-time',
                            ];
                        @endphp
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-neutral-100 text-neutral-700 border border-neutral-200">
                            {{ $rotulos[$candidato->disponibilidade] ?? $candidato->disponibilidade }}
                        </span>
                    @endif
                    @if($candidato->formacao)
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-neutral-100 text-neutral-700 border border-neutral-200">
                            {{ $candidato->formacao }}
                        </span>
                    @endif
                </div>

                @if($candidato->bio)
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-2">Resumo profissional</h2>
                        <p class="text-sm text-neutral-700 leading-relaxed whitespace-pre-wrap">{{ $candidato->bio }}</p>
                    </div>
                @endif

                @if($candidato->skills->isNotEmpty())
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-2">Habilidades</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($candidato->skills as $skill)
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-neutral-100 text-neutral-700 border border-neutral-200">
                                    {{ $skill->nome }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="pt-4 border-t border-neutral-100">
                    @if($podeVerCurriculo && $candidato->temCurriculo())
                        <a href="{{ route('curriculo.download', $candidato) }}"
                            class="btn-ripple inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold bg-neutral-900 hover:bg-black transition-colors">
                            Descarregar Currículo (PDF)
                        </a>
                    @elseif(! $candidato->temCurriculo())
                        <p class="text-sm text-neutral-400">Este candidato ainda não enviou um currículo.</p>
                    @else
                        <p class="text-sm text-neutral-400">
                            Só é possível aceder ao currículo depois de este candidato se candidatar a uma das suas vagas.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
