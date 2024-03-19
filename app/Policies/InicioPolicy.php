<?php

namespace App\Policies;

use App\Enum\RoleGroupEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InicioPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        return $user->hasPermissionTo('inicio');
    }
}
