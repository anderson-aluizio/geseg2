<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class AccessControlPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('gestao.controle_acessos');
    }

    public function storeRole(User $user)
    {
        return $user->hasPermissionTo('gestao.controle_acessos');
    }

    public function updateRole(User $user)
    {
        return $user->hasPermissionTo('gestao.controle_acessos');
    }
}
