<?php

namespace App\Policies;

use App\Models\GrupoVoluntario;
use App\Models\User;

class GrupoVoluntarioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('grupo_voluntarios.cadastro');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('grupo_voluntarios.cadastro');
    }

    public function update(User $user, GrupoVoluntario $grupo_voluntario): bool
    {
        return $user->hasPermissionTo('grupo_voluntarios.cadastro');
    }
}
