<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VagasApp')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-neutral-50 text-neutral-800 min-h-screen antialiased">

    <nav class="bg-neutral-900 border-b border-neutral-800">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">

            <a href="{{ route('vagas.index') }}" class="text-xl font-bold text-white tracking-tight">
                VagasApp
            </a>

            <div class="flex items-center gap-5 text-sm">

                <a href="{{ route('vagas.index') }}" class="text-neutral-300 hover:text-white transition-colors">
                    Vagas
                </a>

                @auth
                    @if(auth()->user()->isEmpresa())
                        <a href="{{ route('vagas.create') }}" class="text-neutral-300 hover:text-white transition-colors">
                            Publicar Vaga
                        </a>
                        <a href="{{ route('candidatos.index') }}" class="text-neutral-300 hover:text-white transition-colors">
                            Pesquisar Candidatos
                        </a>
                    @endif

                    @if(auth()->user()->isCandidato())
                        <a href="{{ route('candidaturas.minhas') }}" class="text-neutral-300 hover:text-white transition-colors">
                            Minhas Candidaturas
                        </a>
                    @endif

                    <a href="{{ route('dashboard') }}" class="text-neutral-300 hover:text-white transition-colors">
                        Dashboard
                    </a>

                    <a href="{{ route('profile.edit') }}" class="text-neutral-300 hover:text-white transition-colors">
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-ripple btn-ripple--light text-neutral-400 hover:text-white transition-colors">
                            Sair
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}" class="text-neutral-300 hover:text-white transition-colors">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                        class="btn-ripple bg-white text-neutral-900 px-3 py-1.5 rounded-md font-semibold hover:bg-neutral-200 transition-colors">
                        Registar
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 pt-4">
        @if(session('sucesso'))
            <div class="bg-neutral-100 border border-neutral-300 text-neutral-800 rounded-lg px-4 py-3 mb-4 flex items-center gap-2">
                <span class="font-semibold">✓</span> {{ session('sucesso') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-neutral-800 border border-neutral-700 text-white rounded-lg px-4 py-3 mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <main class="max-w-5xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>

</html>
