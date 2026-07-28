@extends('layouts.app')
@section('title', $vaga->titulo)

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-sm font-medium transition-colors" style="color:#0891b2">
                &larr; Voltar
            </a>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-5">

            <div class="px-7 py-6" style="background:linear-gradient(135deg,#0891b2 0%,#0e7490 100%)">
                <p class="text-cyan-200 text-xs font-semibold uppercase tracking-wider mb-1">
                    {{ $vaga->localizacao }}
                </p>
                <h1 class="text-2xl font-bold text-white leading-snug">{{ $vaga->titulo }}</h1>
                <p class="text-cyan-100 text-sm mt-1">{{ $vaga->empresa->name }}</p>
            </div>

            <div class="px-7 py-2 bg-cyan-50 border-b border-cyan-100 flex items-center justify-between">
                <p class="text-xs text-cyan-700 font-medium">
                    Publicada {{ $vaga->created_at->diffForHumans() }}
                </p>
                @auth
                    @if(auth()->user()->isEmpresa() && auth()->id() === $vaga->user_id)
                        <div class="flex gap-4">
                            <a href="{{ route('vagas.edit', $vaga) }}" class="text-xs font-semibold transition-colors"
                                style="color:#ca8a04">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('vagas.destroy', $vaga) }}"
                                onsubmit="return confirm('Remover esta vaga?')">
                                @csrf @method('DELETE')
                                <button
                                    class="text-xs font-semibold text-red-400 hover:text-red-600 transition-colors">Remover</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="px-7 py-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Descrição da vaga</h2>
                <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $vaga->descricao }}</div>
            </div>
        </div>

        @auth
            @if(auth()->user()->isCandidato())
                <div class="bg-white border border-gray-100 rounded-2xl px-7 py-6 shadow-sm mb-5">
                    @if($jaCandidatou)
                        <div class="flex items-center gap-3"
                            style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:14px 18px">
                            <span style="color:#16a34a" class="font-semibold text-sm">Candidatura enviada com sucesso.</span>
                        </div>
                    @else
                        <h2 class="text-sm font-bold text-gray-700 mb-1">Tem interesse nesta vaga?</h2>
                        <p class="text-xs text-gray-400 mb-4">A candidatura é enviada imediatamente após clicar no botão.</p>
                        <form method="POST" action="{{ route('candidaturas.store', $vaga) }}">
                            @csrf
                            <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold"
                                style="background:#0891b2">
                                Candidatar-me
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        @else
            <div class="rounded-2xl px-6 py-5 mb-5 text-sm" style="background:#f0fdff; border:1px solid #a5f3fc">
                <strong><a href="{{ route('login') }}" style="color:#0891b2">Inicie sessão</a></strong>
                ou
                <strong><a href="{{ route('register') }}" style="color:#0891b2">registe-se</a></strong>
                para se candidatar a esta vaga.
            </div>
        @endauth

        @auth
            @if(auth()->user()->isEmpresa() && auth()->id() === $vaga->user_id)
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-gray-700">
                            Candidaturas
                        </h2>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#e0f2fe; color:#0891b2">
                            {{ $vaga->candidaturas->count() }}
                        </span>
                    </div>

                    @forelse($vaga->candidaturas as $cand)
                        <div class="px-6 py-4 border-b border-gray-50 last:border-0 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800">{{ $cand->candidato->name }}</p>
                                <p class="text-xs text-gray-400">{{ $cand->candidato->email }}</p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                @php
                                    $st = $cand->status;
                                    $badge = match ($st) {
                                        'aceite' => 'background:#dcfce7; color:#166534',
                                        'rejeitado' => 'background:#fee2e2; color:#991b1b',
                                        default => 'background:#fef9c3; color:#854d0e',
                                    };
                                @endphp
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="{{ $badge }}">
                                    {{ ucfirst($st) }}
                                </span>
                                <form method="POST" action="{{ route('candidaturas.atualizar', $cand) }}" class="flex gap-2">
                                    @csrf @method('PATCH')
                                    <select name="status"
                                        class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-cyan-400 bg-white">
                                        <option value="pendente" {{ $st === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="aceite" {{ $st === 'aceite' ? 'selected' : '' }}>Aceitar</option>
                                        <option value="rejeitado" {{ $st === 'rejeitado' ? 'selected' : '' }}>Rejeitar</option>
                                    </select>
                                    <button type="submit" class="text-xs px-3 py-1.5 rounded-lg text-white font-semibold"
                                        style="background:#0891b2">
                                        Guardar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-400 text-sm">
                            Nenhuma candidatura ainda.
                        </div>
                    @endforelse
                </div>
            @endif
        @endauth

    </div>
@endsection