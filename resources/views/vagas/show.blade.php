@extends('layouts.app')
@section('title', $vaga->titulo)

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-sm font-medium text-neutral-600 hover:text-neutral-900 transition-colors">
                &larr; Voltar
            </a>
        </div>

        <div class="bg-white border border-neutral-200 rounded-2xl shadow-sm overflow-hidden mb-5">

            <div class="px-7 py-6 bg-neutral-900 flex items-start gap-4">
                <x-avatar :user="$vaga->empresa" size="lg" class="border-neutral-700" />
                <div class="min-w-0">
                    <p class="text-neutral-400 text-xs font-semibold uppercase tracking-wider mb-1">
                        {{ $vaga->localizacao }}
                    </p>
                    <h1 class="text-2xl font-bold text-white leading-snug">{{ $vaga->titulo }}</h1>
                    <p class="text-neutral-300 text-sm mt-1">{{ $vaga->empresa->name }}</p>
                </div>
            </div>

            <div class="px-7 py-2 bg-neutral-100 border-b border-neutral-200 flex items-center justify-between">
                <p class="text-xs text-neutral-500 font-medium">
                    Publicada {{ $vaga->created_at->diffForHumans() }}
                </p>
                @auth
                    @if(auth()->user()->isEmpresa() && auth()->id() === $vaga->user_id)
                        <div class="flex gap-4">
                            <a href="{{ route('vagas.edit', $vaga) }}" class="text-xs font-semibold text-neutral-700 hover:text-neutral-900 transition-colors">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('vagas.destroy', $vaga) }}"
                                onsubmit="return confirm('Remover esta vaga?')">
                                @csrf @method('DELETE')
                                <button class="btn-ripple btn-ripple--light text-xs font-semibold text-neutral-500 hover:text-neutral-900 transition-colors">Remover</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="px-7 py-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-3">Descrição da vaga</h2>
                <div class="text-neutral-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $vaga->descricao }}</div>
            </div>
        </div>

        @auth
            @if(auth()->user()->isCandidato())
                <div class="bg-white border border-neutral-200 rounded-2xl px-7 py-6 shadow-sm mb-5">
                    @if($jaCandidatou)
                        <div class="flex items-center gap-3 bg-neutral-100 border border-neutral-300 rounded-xl px-4 py-3.5">
                            <span class="text-neutral-800 font-semibold text-sm">Candidatura enviada com sucesso.</span>
                        </div>
                    @else
                        <h2 class="text-sm font-bold text-neutral-800 mb-1">Tem interesse nesta vaga?</h2>
                        @unless(auth()->user()->temCurriculo())
                            <p class="text-xs text-neutral-500 mb-4">
                                Recomendamos enviar o seu currículo em PDF antes de se candidatar — assim a empresa
                                pode consultá-lo. <a href="{{ route('profile.edit') }}" class="underline underline-offset-2 hover:text-neutral-900">Adicionar currículo</a>.
                            </p>
                        @else
                            <p class="text-xs text-neutral-400 mb-4">A candidatura é enviada imediatamente após clicar no botão.</p>
                        @endunless
                        <form method="POST" action="{{ route('candidaturas.store', $vaga) }}">
                            @csrf
                            <button type="submit" class="btn-ripple px-6 py-2.5 rounded-xl text-white text-sm font-semibold bg-neutral-900 hover:bg-black transition-colors">
                                Candidatar-me
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        @else
            <div class="rounded-2xl px-6 py-5 mb-5 text-sm bg-neutral-100 border border-neutral-300">
                <strong><a href="{{ route('login') }}" class="text-neutral-900 underline underline-offset-2">Inicie sessão</a></strong>
                ou
                <strong><a href="{{ route('register') }}" class="text-neutral-900 underline underline-offset-2">registe-se</a></strong>
                para se candidatar a esta vaga.
            </div>
        @endauth

        @auth
            @if(auth()->user()->isEmpresa() && auth()->id() === $vaga->user_id)
                <div class="bg-white border border-neutral-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-neutral-800">
                            Candidaturas
                        </h2>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-neutral-200 text-neutral-800">
                            {{ $vaga->candidaturas->count() }}
                        </span>
                    </div>

                    @forelse($vaga->candidaturas as $cand)
                        <div class="px-6 py-4 border-b border-neutral-100 last:border-0 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <x-avatar :user="$cand->candidato" size="sm" />
                                <div class="min-w-0">
                                    <a href="{{ route('candidatos.show', $cand->candidato) }}" class="text-sm font-semibold text-neutral-900 hover:underline">
                                        {{ $cand->candidato->name }}
                                    </a>
                                    <p class="text-xs text-neutral-400">{{ $cand->candidato->email }}</p>
                                    @if($cand->candidato->temCurriculo())
                                        <a href="{{ route('curriculo.download', $cand->candidato) }}"
                                            class="text-xs text-neutral-600 underline underline-offset-2 hover:text-neutral-900">
                                            Ver currículo (PDF)
                                        </a>
                                    @else
                                        <span class="text-xs text-neutral-300">Sem currículo enviado</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <x-status-badge :status="$cand->status" />
                                <form method="POST" action="{{ route('candidaturas.atualizar', $cand) }}" class="flex gap-2">
                                    @csrf @method('PATCH')
                                    <select name="status"
                                        class="text-xs border border-neutral-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-neutral-500 bg-white">
                                        <option value="pendente" {{ $cand->status === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="aceite" {{ $cand->status === 'aceite' ? 'selected' : '' }}>Aceitar</option>
                                        <option value="rejeitado" {{ $cand->status === 'rejeitado' ? 'selected' : '' }}>Recusar</option>
                                    </select>
                                    <button type="submit" class="btn-ripple text-xs px-3 py-1.5 rounded-lg text-white font-semibold bg-neutral-900 hover:bg-black transition-colors">
                                        Guardar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-neutral-400 text-sm">
                            Nenhuma candidatura ainda.
                        </div>
                    @endforelse
                </div>
            @endif
        @endauth

    </div>
@endsection
