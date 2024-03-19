<?php

namespace App\Policies;

use App\Models\AcaoTipo;
use App\Models\User;

class AcaoTipoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('acao_tipos.cadastro');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('acao_tipos.cadastro');
    }

    public function update(User $user, AcaoTipo $acao_tipo): bool
    {
        return $user->hasPermissionTo('acao_tipos.cadastro');
    }
}
