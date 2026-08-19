<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Quem pode ver/descarregar o currículo de um candidato:
     * - o próprio candidato;
     * - uma empresa que tenha recebido pelo menos uma candidatura desse candidato.
     */
    public function verCurriculo(User $utilizador, User $candidato): bool
    {
        if ($utilizador->id === $candidato->id) {
            return true;
        }

        if (! $utilizador->isEmpresa()) {
            return false;
        }

        return $candidato->candidaturas()
            ->whereHas('vaga', fn ($q) => $q->where('user_id', $utilizador->id))
            ->exists();
    }

    /**
     * Quem pode pesquisar/consultar a listagem de candidatos: só empresas.
     */
    public function pesquisarCandidatos(User $utilizador): bool
    {
        return $utilizador->isEmpresa();
    }

    /**
     * Quem pode ver o perfil detalhado de um candidato: qualquer empresa autenticada.
     */
    public function verPerfil(User $utilizador, User $candidato): bool
    {
        return $utilizador->isEmpresa() && $candidato->isCandidato();
    }
}
