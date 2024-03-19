<?php

namespace App\Policies;

use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FuncionarioPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('colaborador.visualizar');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('colaborador.visualizar');
    }
}
