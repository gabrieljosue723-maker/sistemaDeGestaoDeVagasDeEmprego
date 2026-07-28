<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vaga;

class VagaPolicy
{

    public function create(User $user): bool
    {
        return $user->isEmpresa();
    }

    public function update(User $user, Vaga $vaga): bool
    {
        return $user->isEmpresa() && $user->id === $vaga->user_id;
    }


    public function delete(User $user, Vaga $vaga): bool
    {
        return $user->isEmpresa() && $user->id === $vaga->user_id;
    }

    public function candidatar(User $user, Vaga $vaga): bool
    {
        return $user->isCandidato();
    }
}