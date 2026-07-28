<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

    
        <div>
            <x-input-label for="name" :value="__('Nome completo')" />
            <x-text-input id="name"
                          class="block mt-1 w-full"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required
                          autofocus
                          autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

  
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                          class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        
        <div class="mt-4">
            <x-input-label :value="__('Tipo de conta')" />

            <div class="mt-2 flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio"
                           name="tipo"
                           value="Candidato"
                           {{ old('tipo', 'Candidato') === 'Candidato' ? 'checked' : '' }}
                           class="text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Candidato</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio"
                           name="tipo"
                           value="Empresa"
                           {{ old('tipo') === 'Empresa' ? 'checked' : '' }}
                           class="text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-700">Empresa</span>
                </label>
            </div>

            <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
        </div>

     
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Password')" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('login') }}">
                {{ __('Já tem conta?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Criar conta') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>