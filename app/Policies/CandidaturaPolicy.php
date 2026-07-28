<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Candidatura;

class CandidaturaPolicy
{
    public function atualizar(User $user, Candidatura $candidatura): bool
    {
        return $user->isEmpresa()
            && $user->id === $candidatura->vaga->user_id;
    }

    public function verMinhas(User $user): bool
    {
        return $user->isCandidato();
    }
}