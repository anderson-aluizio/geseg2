<?php

namespace App\Policies;

use App\Enum\RoleGroupEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('gestao.cadastro_usuarios');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('gestao.cadastro_usuarios');
    }

    public function update(User $user, User $model)
    {
        $userModel = $model->loadCount(['centroCustos' => function ($query) {
            $query->whereIn('centro_custo_id', function ($query) {
                $query->selectRaw('centro_custo_user.centro_custo_id')
                    ->from('centro_custo_user')
                    ->where('centro_custo_user.user_id', auth()->id());
            });
        }]);
        $userHasAdminRole = $user->roles->pluck('name')->contains(RoleGroupEnum::Admin->name);
        $userModelHasAdminRole = $model->roles->pluck('name')->contains(RoleGroupEnum::Admin->name);
        $userCanUpdateModel = $userHasAdminRole || !$userModelHasAdminRole;

        return $user->hasPermissionTo('gestao.cadastro_usuarios') && (bool) $userModel->centro_custos_count && $userCanUpdateModel;
    }
}
