@extends('layouts.app')
@section('title', 'Pesquisar Candidatos')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-neutral-900 mb-1">Pesquisar Candidatos</h1>
        <p class="text-sm text-neutral-500 mb-5">Filtre por experiência, habilidades, localização e mais.</p>

        <form method="GET" action="{{ route('candidatos.index') }}" class="bg-white border border-neutral-200 rounded-2xl p-6 shadow-sm space-y-5">

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Pesquisa livre</label>
                <input type="text" name="texto" value="{{ $filtros['texto'] ?? '' }}"
                    placeholder="Nome ou palavra-chave no resumo profissional..."
                    class="w-full border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Anos de experiência (mín.)</label>
                    <input type="number" name="experiencia_min" min="0" max="60" value="{{ $filtros['experiencia_min'] ?? '' }}"
                        class="w-full border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Localização</label>
                    <input type="text" name="localizacao" value="{{ $filtros['localizacao'] ?? '' }}" placeholder="Cidade"
                        class="w-full border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Formação</label>
                    <input type="text" name="formacao" value="{{ $filtros['formacao'] ?? '' }}" placeholder="Ex: Informática"
                        class="w-full border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Disponibilidade</label>
                    <select name="disponibilidade"
                        class="w-full border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500">
                        <option value="">Qualquer</option>
                        @php $dispAtual = $filtros['disponibilidade'] ?? ''; @endphp
                        <option value="imediata" {{ $dispAtual === 'imediata' ? 'selected' : '' }}>Imediata</option>
                        <option value="a_combinar" {{ $dispAtual === 'a_combinar' ? 'selected' : '' }}>A combinar</option>
                        <option value="part_time" {{ $dispAtual === 'part_time' ? 'selected' : '' }}>Part-time</option>
                        <option value="full_time" {{ $dispAtual === 'full_time' ? 'selected' : '' }}>Full-time</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Habilidades</label>
                <div class="flex flex-wrap gap-2">
                    @php $skillsAtuais = $filtros['skills'] ?? []; @endphp
                    @foreach($skills as $skill)
                        <label class="inline-flex items-center gap-1.5 border border-neutral-300 rounded-full px-3 py-1.5 text-xs font-medium text-neutral-700 has-[:checked]:bg-neutral-900 has-[:checked]:text-white has-[:checked]:border-neutral-900 cursor-pointer transition-colors">
                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="hidden"
                                {{ in_array($skill->id, $skillsAtuais) ? 'checked' : '' }}>
                            {{ $skill->nome }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-1">
                <button type="submit" class="btn-ripple px-5 py-2.5 rounded-xl text-white text-sm font-semibold bg-neutral-900 hover:bg-black transition-colors">
                    Pesquisar
                </button>
                <a href="{{ route('candidatos.index') }}" class="text-sm text-neutral-500 hover:text-neutral-800 self-center">
                    Limpar filtros
                </a>
            </div>
        </form>
    </div>

    <p class="text-sm text-neutral-500 mb-4">{{ $candidatos->total() }} candidato(s) encontrado(s)</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @forelse($candidatos as $candidato)
            <div class="bg-white border border-neutral-200 rounded-2xl p-5 shadow-sm flex flex-col">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <x-avatar :user="$candidato" size="sm" class="mt-0.5" />
                        <div class="min-w-0">
                            <a href="{{ route('candidatos.show', $candidato) }}" class="text-base font-bold text-neutral-900 hover:underline">
                                {{ $candidato->name }}
                            </a>
                            <p class="text-xs text-neutral-400">
                                {{ $candidato->localizacao ?? 'Localização não indicada' }}
                            </p>
                        </div>
                    </div>
                    @if(!is_null($candidato->anos_experiencia))
                        <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full bg-neutral-200 text-neutral-800">
                            {{ $candidato->anos_experiencia }} {{ Str::plural('ano', $candidato->anos_experiencia) }}
                        </span>
                    @endif
                </div>

                @if($candidato->bio)
                    <p class="text-sm text-neutral-600 mt-3 leading-relaxed line-clamp-2">{{ $candidato->bio }}</p>
                @endif

                @if($candidato->skills->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach($candidato->skills->take(5) as $skill)
                            <span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-neutral-100 text-neutral-600 border border-neutral-200">
                                {{ $skill->nome }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4 pt-3 border-t border-neutral-100">
                    <a href="{{ route('candidatos.show', $candidato) }}" class="text-sm font-semibold text-neutral-900 hover:underline">
                        Ver perfil completo
                    </a>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 bg-white border border-dashed border-neutral-300 rounded-2xl px-6 py-16 text-center">
                <p class="text-lg font-semibold text-neutral-700 mb-1">Nenhum candidato encontrado</p>
                <p class="text-sm text-neutral-400">Tente ajustar os filtros de pesquisa</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $candidatos->links() }}
    </div>
@endsection
