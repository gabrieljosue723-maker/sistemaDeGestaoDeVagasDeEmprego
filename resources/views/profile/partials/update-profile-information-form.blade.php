<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informação do Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Atualize os dados da sua conta e, se for candidato, o seu perfil profissional.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('O seu endereço de email não está verificado.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-600">
                            {{ __('Clique aqui para reenviar o email de verificação.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-gray-700">
                            {{ __('Um novo link de verificação foi enviado para o seu email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($user->isCandidato())
            <div class="pt-6 border-t border-neutral-200">
                <h3 class="text-sm font-bold text-neutral-800 uppercase tracking-wide mb-4">Perfil Profissional</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="anos_experiencia" value="Anos de experiência" />
                        <x-text-input id="anos_experiencia" name="anos_experiencia" type="number" min="0" max="60"
                            class="mt-1 block w-full" :value="old('anos_experiencia', $user->anos_experiencia)" />
                        <x-input-error class="mt-2" :messages="$errors->get('anos_experiencia')" />
                    </div>

                    <div>
                        <x-input-label for="localizacao" value="Localização" />
                        <x-text-input id="localizacao" name="localizacao" type="text" placeholder="Cidade, país"
                            class="mt-1 block w-full" :value="old('localizacao', $user->localizacao)" />
                        <x-input-error class="mt-2" :messages="$errors->get('localizacao')" />
                    </div>

                    <div>
                        <x-input-label for="formacao" value="Formação" />
                        <x-text-input id="formacao" name="formacao" type="text" placeholder="Ex: Licenciatura em Informática"
                            class="mt-1 block w-full" :value="old('formacao', $user->formacao)" />
                        <x-input-error class="mt-2" :messages="$errors->get('formacao')" />
                    </div>

                    <div>
                        <x-input-label for="disponibilidade" value="Disponibilidade" />
                        <select id="disponibilidade" name="disponibilidade"
                            class="mt-1 block w-full border-neutral-300 focus:border-neutral-600 focus:ring-neutral-600 rounded-md shadow-sm text-sm">
                            <option value="">Selecione...</option>
                            @php $atual = old('disponibilidade', $user->disponibilidade); @endphp
                            <option value="imediata" {{ $atual === 'imediata' ? 'selected' : '' }}>Imediata</option>
                            <option value="a_combinar" {{ $atual === 'a_combinar' ? 'selected' : '' }}>A combinar</option>
                            <option value="part_time" {{ $atual === 'part_time' ? 'selected' : '' }}>Part-time</option>
                            <option value="full_time" {{ $atual === 'full_time' ? 'selected' : '' }}>Full-time</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('disponibilidade')" />
                    </div>
                </div>

                <div class="mt-5">
                    <x-input-label for="bio" value="Resumo profissional" />
                    <textarea id="bio" name="bio" rows="4"
                        class="mt-1 block w-full border-neutral-300 focus:border-neutral-600 focus:ring-neutral-600 rounded-md shadow-sm text-sm"
                        placeholder="Fale um pouco sobre a sua experiência e objetivos...">{{ old('bio', $user->bio) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>

                <div class="mt-5">
                    <x-input-label value="Habilidades" />
                    <p class="text-xs text-neutral-400 mb-2">Selecione todas as que se aplicam.</p>
                    <div class="flex flex-wrap gap-2">
                        @php $skillsAtuais = old('skills', $user->skills->pluck('id')->toArray()); @endphp
                        @foreach($skills as $skill)
                            <label class="inline-flex items-center gap-1.5 border border-neutral-300 rounded-full px-3 py-1.5 text-xs font-medium text-neutral-700 has-[:checked]:bg-neutral-900 has-[:checked]:text-white has-[:checked]:border-neutral-900 cursor-pointer transition-colors">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="hidden"
                                    {{ in_array($skill->id, $skillsAtuais) ? 'checked' : '' }}>
                                {{ $skill->nome }}
                            </label>
                        @endforeach
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('skills')" />
                </div>

                <div class="mt-5">
                    <x-input-label for="curriculo" value="Currículo (PDF, máx. 5MB)" />

                    @if($user->temCurriculo())
                        <div class="mt-1 flex items-center gap-3 text-sm">
                            <a href="{{ route('curriculo.download', $user) }}" class="text-neutral-900 underline underline-offset-2 hover:text-neutral-600">
                                {{ $user->curriculo_nome_original ?? 'Ver currículo atual' }}
                            </a>
                            <form method="POST" action="{{ route('curriculo.destroy') }}"
                                onsubmit="return confirm('Remover o currículo atual?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-ripple btn-ripple--light text-xs text-neutral-500 hover:text-neutral-900">Remover</button>
                            </form>
                        </div>
                        <p class="text-xs text-neutral-400 mt-1">Escolha um novo ficheiro abaixo para substituir.</p>
                    @endif

                    <input id="curriculo" name="curriculo" type="file" accept="application/pdf"
                        class="mt-2 block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-neutral-900 file:text-white hover:file:bg-black file:cursor-pointer" />
                    <x-input-error class="mt-2" :messages="$errors->get('curriculo')" />
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
