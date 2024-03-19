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
        return $user->hasPermissionTo('cargo.visualizar');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('cargo.vincular_documento');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('cargo.vincular_documento');
    }
}
