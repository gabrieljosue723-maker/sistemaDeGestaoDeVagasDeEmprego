<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VagasApp')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen">


    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">

            <a href="{{ route('vagas.index') }}" class="text-xl font-bold text-indigo-600">
                VagasApp
            </a>

            <div class="flex items-center gap-4 text-sm">

                <a href="{{ route('vagas.index') }}" class="text-gray-600 hover:text-indigo-600">
                    Vagas
                </a>

                @auth
                    @if(auth()->user()->isEmpresa())
                        <a href="{{ route('vagas.create') }}" class="text-gray-600 hover:text-indigo-600">
                            Publicar Vaga
                        </a>
                    @endif

                    @if(auth()->user()->isCandidato())
                        <a href="{{ route('candidaturas.minhas') }}" class="text-gray-600 hover:text-indigo-600">
                            Minhas Candidaturas
                        </a>
                    @endif

                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600">
                        Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            Sair
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-3 py-1.5 rounded-md hover:bg-indigo-700">
                        Registar
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 pt-4">
        @if(session('sucesso'))
            <div class="bg-green-100 border border-green-300 text-green-800 rounded-md px-4 py-3 mb-4">
                {{ session('sucesso') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 rounded-md px-4 py-3 mb-4">
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