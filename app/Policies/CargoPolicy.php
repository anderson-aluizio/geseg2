<?php

namespace App\Policies;

use App\Models\Cargo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CargoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('cargo.cadastro') || $user->hasPermissionTo('cargo.visualizar');
    }
    public function view(User $user)
    {
        return $user->hasPermissionTo('cargo.cadastro') || $user->hasPermissionTo('cargo.visualizar');
    }
}
