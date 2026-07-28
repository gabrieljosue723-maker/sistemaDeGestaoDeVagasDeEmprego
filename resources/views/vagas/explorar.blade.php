<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaVagas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-bg {
            background: linear-gradient(135deg, #164e63 0%, #0891b2 60%, #0e7490 100%);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen">


    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('vagas.explorar') }}" class="text-xl font-bold" style="color:#0891b2">sistemaVagas</a>

            <nav class="flex items-center gap-5 text-sm font-medium">
                <a href="{{ route('vagas.explorar') }}" class="font-semibold pb-0.5 border-b-2"
                    style="color:#0891b2; border-color:#0891b2">
                    Explorar Vagas
                </a>
                @auth
                    @if(auth()->user()->isEmpresa())
                        <a href="{{ route('vagas.create') }}"
                            class="text-gray-500 hover:text-cyan-600 transition-colors">Publicar Vaga</a>
                    @endif
                    @if(auth()->user()->isCandidato())
                        <a href="{{ route('candidaturas.minhas') }}"
                            class="text-gray-500 hover:text-cyan-600 transition-colors">Candidaturas</a>
                    @endif
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 hover:text-cyan-600 transition-colors">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-400 hover:text-red-500 transition-colors">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-cyan-600 transition-colors">Entrar</a>
                    <a href="{{ route('register') }}" class="px-4 py-1.5 rounded-lg text-white text-sm font-semibold"
                        style="background:#0891b2">
                        Registar
                    </a>
                @endauth
            </nav>
        </div>
    </header>


    <section class="hero-bg px-4 py-20">
        <div class="max-w-2xl mx-auto text-center">
            <p class="text-cyan-300 text-xs font-bold uppercase tracking-widest mb-3">Todas as oportunidades</p>
            <h1 class="text-4xl sm:text-5xl font-bold text-white leading-tight mb-4">
                Encontre a vaga certa para si
            </h1>
            <p class="text-cyan-100 text-base mb-10">
                Pesquise entre todas as vagas publicadas pelas melhores empresas.
            </p>

            <form method="GET" action="{{ route('vagas.explorar') }}"
                class="flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">
                <input type="text" name="busca" value="{{ request('busca') }}"
                    placeholder="Cargo, empresa ou localização..."
                    class="flex-1 px-5 py-3.5 rounded-xl text-sm text-gray-800 bg-white border-2 border-transparent focus:border-yellow-400 focus:outline-none">
                <button type="submit" class="px-7 py-3.5 rounded-xl font-bold text-sm text-gray-900"
                    style="background:#facc15">
                    Pesquisar
                </button>
            </form>
        </div>
    </section>


    @if(session('sucesso'))
        <div class="max-w-5xl mx-auto px-4 mt-5">
            <div class="rounded-xl px-4 py-3 text-sm" style="background:#f0fdff; border:1px solid #a5f3fc; color:#0e7490">
                {{ session('sucesso') }}
            </div>
        </div>
    @endif


    <main class="max-w-5xl mx-auto px-4 pt-8 pb-16">


        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                @if(request('busca'))
                    <p class="text-sm text-gray-600">
                        Resultados para: <strong class="text-gray-900">{{ request('busca') }}</strong>
                        &ensp;<a href="{{ route('vagas.explorar') }}" class="text-xs font-medium"
                            style="color:#0891b2">limpar</a>
                    </p>
                @else
                    <p class="text-sm text-gray-400 font-medium">
                        {{ $vagas->total() }} {{ $vagas->total() === 1 ? 'vaga disponível' : 'vagas disponíveis' }}
                    </p>
                @endif
            </div>

            @auth
                @if(auth()->user()->isEmpresa())
                    <a href="{{ route('vagas.create') }}" class="shrink-0 text-sm font-bold px-5 py-2 rounded-xl text-gray-900"
                        style="background:#facc15">
                        Publicar Vaga
                    </a>
                @endif
            @endauth
        </div>


        @forelse($vagas as $vaga)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 mb-4 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-base font-bold text-gray-900 mb-0.5">
                            <a href="{{ route('vagas.show', $vaga) }}" class="hover:text-cyan-700 transition-colors">
                                {{ $vaga->titulo }}
                            </a>
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ $vaga->empresa->name }}
                            <span class="mx-1 text-gray-300">&middot;</span>
                            {{ $vaga->localizacao }}
                        </p>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed line-clamp-2">{{ $vaga->descricao }}</p>
                    </div>
                    <span class="shrink-0 text-xs text-gray-400 whitespace-nowrap pt-0.5">
                        {{ $vaga->created_at->diffForHumans() }}
                    </span>
                </div>

                <div class="mt-5 pt-4 border-t border-gray-50 flex items-center gap-4">
                    <a href="{{ route('vagas.show', $vaga) }}" class="text-sm font-semibold transition-colors"
                        style="color:#0891b2">
                        Ver detalhes
                    </a>

                    @auth
                        @if(auth()->user()->isEmpresa() && auth()->id() === $vaga->user_id)
                            <span class="text-gray-200">|</span>
                            <a href="{{ route('vagas.edit', $vaga) }}" class="text-sm font-medium transition-colors"
                                style="color:#ca8a04">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('vagas.destroy', $vaga) }}"
                                onsubmit="return confirm('Remover esta vaga?')">
                                @csrf @method('DELETE')
                                <button
                                    class="text-sm font-medium text-red-400 hover:text-red-600 transition-colors">Remover</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-gray-200 rounded-2xl px-6 py-24 text-center">
                <p class="text-lg font-semibold text-gray-700 mb-1">Nenhuma vaga encontrada</p>
                <p class="text-sm text-gray-400 mb-6">Experimente pesquisar por outro termo</p>
                @auth
                    @if(auth()->user()->isEmpresa())
                        <a href="{{ route('vagas.create') }}" class="text-sm font-bold px-6 py-2.5 rounded-xl text-white"
                            style="background:#0891b2">
                            Publicar a primeira vaga
                        </a>
                    @endif
                @endauth
            </div>
        @endforelse

        <div class="mt-8">
            {{ $vagas->withQueryString()->links() }}
        </div>
    </main>

</body>

</html>