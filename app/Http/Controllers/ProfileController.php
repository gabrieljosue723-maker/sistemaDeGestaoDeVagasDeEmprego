<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $skills = Skill::orderBy('nome')->get();

        return view('profile.edit', [
            'user' => $request->user(),
            'skills' => $skills,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $dados = $request->validated();
        $user = $request->user();

        // O upload e as skills são tratados à parte, não fazem parte do fill direto.
        unset($dados['curriculo'], $dados['skills'], $dados['foto']);

        $user->fill($dados);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Foto de perfil (candidato) ou logótipo (empresa) — comum aos dois tipos de conta.
        if ($request->hasFile('foto')) {
            if ($user->foto_path) {
                Storage::disk('public')->delete($user->foto_path);
            }

            $user->foto_path = $request->file('foto')->store('fotos', 'public');
        }

        if ($user->isCandidato()) {
            if ($request->hasFile('curriculo')) {
                // Remove o currículo antigo antes de guardar o novo.
                if ($user->curriculo_path) {
                    Storage::disk('public')->delete($user->curriculo_path);
                }

                $ficheiro = $request->file('curriculo');
                $caminho = $ficheiro->store('curriculos', 'public');

                $user->curriculo_path = $caminho;
                $user->curriculo_nome_original = $ficheiro->getClientOriginalName();
            }

            $user->save();

            $user->skills()->sync($request->input('skills', []));
        } else {
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Remove a foto de perfil / logótipo do utilizador autenticado.
     */
    public function destroyFoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->foto_path) {
            Storage::disk('public')->delete($user->foto_path);
            $user->update(['foto_path' => null]);
        }

        return back()->with('status', 'foto-removida');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
