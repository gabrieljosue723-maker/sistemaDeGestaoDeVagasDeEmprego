@extends('layouts.app')
@section('title', 'Perfil')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-neutral-900 mb-2">Perfil</h1>

        <div class="p-4 sm:p-8 bg-white border border-neutral-200 shadow-sm rounded-xl">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="p-4 sm:p-8 bg-white border border-neutral-200 shadow-sm rounded-xl">
            @include('profile.partials.update-password-form')
        </div>

        <div class="p-4 sm:p-8 bg-white border border-neutral-200 shadow-sm rounded-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
