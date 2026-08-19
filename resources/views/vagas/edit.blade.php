@extends('layouts.app')
@section('title', 'Editar Vaga')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-neutral-900">Editar Vaga</h1>

        <div class="bg-white border border-neutral-200 rounded-xl p-6 shadow-sm">
            <form method="POST" action="{{ route('vagas.update', $vaga) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Título da vaga</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $vaga->titulo) }}"
                        class="w-full border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 @error('titulo') border-neutral-800 @enderror">
                    @error('titulo')
                        <p class="text-neutral-900 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Localização</label>
                    <input type="text" name="localizacao" value="{{ old('localizacao', $vaga->localizacao) }}"
                        class="w-full border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 @error('localizacao') border-neutral-800 @enderror">
                    @error('localizacao')
                        <p class="text-neutral-900 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Descrição</label>
                    <textarea name="descricao" rows="6"
                        class="w-full border border-neutral-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 @error('descricao') border-neutral-800 @enderror">{{ old('descricao', $vaga->descricao) }}</textarea>
                    @error('descricao')
                        <p class="text-neutral-900 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-ripple px-5 py-2 rounded-md text-white text-sm font-semibold bg-neutral-900 hover:bg-black transition-colors">
                        Guardar Alterações
                    </button>
                    <a href="{{ route('vagas.show', $vaga) }}" class="text-sm text-neutral-500 hover:text-neutral-800 self-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
