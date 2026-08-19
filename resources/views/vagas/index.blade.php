@extends('layouts.app')
@section('title', 'Vagas Disponíveis')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-neutral-900 mb-1">Vagas Disponíveis</h1>
        <p class="text-sm text-neutral-500 mb-5">Pesquise por título, descrição, empresa ou localização</p>

        <div class="flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('vagas.index') }}" class="flex gap-2 flex-1">
                <input type="text" name="busca" value="{{ request('busca') }}"
                    placeholder="Pesquisar vagas... (ex: 'remoto', 'vendas', 'Lisboa')"
                    class="flex-1 border border-neutral-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 bg-white">
                <button type="submit" class="btn-ripple px-5 py-2.5 rounded-xl text-white text-sm font-semibold bg-neutral-900 hover:bg-black transition-colors">
                    Pesquisar
                </button>
            </form>

            @auth
                @if(auth()->user()->isEmpresa())
                    <x-button-link :href="route('vagas.create')" variant="dark" class="shrink-0">
                        Publicar Vaga
                    </x-button-link>
                @endif
            @endauth
        </div>

        @if(request('busca'))
            <div class="mt-3 flex items-center gap-2 text-sm text-neutral-500">
                <span>Resultados para: <strong class="text-neutral-800">{{ request('busca') }}</strong></span>
                <a href="{{ route('vagas.index') }}" class="text-neutral-900 underline underline-offset-2 hover:text-neutral-600">limpar</a>
            </div>
        @endif
    </div>

    @forelse($vagas as $vaga)
        <div class="bg-white border border-neutral-200 rounded-2xl p-6 mb-4 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-bold text-neutral-900 mb-0.5">
                        <a href="{{ route('vagas.show', $vaga) }}" class="hover:underline transition-colors">
                            {{ $vaga->titulo }}
                        </a>
                    </h2>
                    <p class="text-sm text-neutral-500">
                        {{ $vaga->empresa->name }}
                        <span class="mx-1 text-neutral-300">&middot;</span>
                        {{ $vaga->localizacao }}
                    </p>
                    <p class="text-neutral-600 mt-3 text-sm leading-relaxed line-clamp-2">{{ $vaga->descricao }}</p>
                </div>
                <span class="shrink-0 text-xs text-neutral-400 whitespace-nowrap pt-0.5">
                    {{ $vaga->created_at->diffForHumans() }}
                </span>
            </div>

            <div class="mt-5 pt-4 border-t border-neutral-100 flex items-center gap-4">
                <a href="{{ route('vagas.show', $vaga) }}" class="text-sm font-semibold text-neutral-900 hover:underline transition-colors">
                    Ver detalhes
                </a>

                @auth
                    @if(auth()->user()->isEmpresa() && auth()->id() === $vaga->user_id)
                        <span class="text-neutral-200">|</span>
                        <a href="{{ route('vagas.edit', $vaga) }}" class="text-sm font-medium text-neutral-600 hover:text-neutral-900 transition-colors">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('vagas.destroy', $vaga) }}"
                            onsubmit="return confirm('Remover esta vaga?')">
                            @csrf @method('DELETE')
                            <button class="btn-ripple btn-ripple--light text-sm font-medium text-neutral-500 hover:text-neutral-900 transition-colors">Remover</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <div class="bg-white border border-dashed border-neutral-300 rounded-2xl px-6 py-20 text-center">
            <p class="text-lg font-semibold text-neutral-700 mb-1">Nenhuma vaga encontrada</p>
            <p class="text-sm text-neutral-400 mb-5">Tente ajustar os termos de pesquisa</p>
            @auth
                @if(auth()->user()->isEmpresa())
                    <x-button-link :href="route('vagas.create')" variant="dark">
                        Publicar vaga
                    </x-button-link>
                @endif
            @endauth
        </div>
    @endforelse

    <div class="mt-6">
        {{ $vagas->withQueryString()->links() }}
    </div>

@endsection
