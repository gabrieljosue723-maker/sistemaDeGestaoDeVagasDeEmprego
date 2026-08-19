<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CurriculoController extends Controller
{
    /**
     * Permite o download do currículo em PDF de um candidato.
     *
     * Só pode aceder: o próprio candidato, ou uma empresa que tenha
     * pelo menos uma vaga à qual esse candidato se candidatou.
     */
    public function download(User $candidato): StreamedResponse
    {
        $this->authorize('verCurriculo', $candidato);

        abort_unless($candidato->temCurriculo(), 404, 'Este candidato ainda não enviou um currículo.');
        abort_unless(Storage::disk('public')->exists($candidato->curriculo_path), 404, 'Ficheiro não encontrado.');

        $nome = $candidato->curriculo_nome_original ?: 'curriculo.pdf';

        return Storage::disk('public')->download($candidato->curriculo_path, $nome);
    }

    /**
     * Remove o currículo atual do candidato autenticado.
     */
    public function destroy()
    {
        $user = auth()->user();

        abort_unless($user->isCandidato(), 403);

        if ($user->curriculo_path) {
            Storage::disk('public')->delete($user->curriculo_path);
            $user->update([
                'curriculo_path' => null,
                'curriculo_nome_original' => null,
            ]);
        }

        return back()->with('sucesso', 'Currículo removido.');
    }
}
