@extends('layouts.app')
@section('title', 'Editar Vaga')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">Editar Vaga</h1>

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <form method="POST" action="{{ route('vagas.update', $vaga) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título da vaga</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $vaga->titulo) }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('titulo') border-red-400 @enderror">
                    @error('titulo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                    <input type="text" name="localizacao" value="{{ old('localizacao', $vaga->localizacao) }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('localizacao') border-red-400 @enderror">
                    @error('localizacao')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea name="descricao" rows="6"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 @error('descricao') border-red-400 @enderror">{{ old('descricao', $vaga->descricao) }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="px-5 py-2 rounded-md text-white text-sm font-semibold"
                        style="background:#0891b2">
                        Guardar Alterações
                    </button>
                    <a href="{{ route('vagas.show', $vaga) }}" class="text-sm text-gray-500 hover:text-gray-700 self-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection