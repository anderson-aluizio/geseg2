<?php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;

class DocumentoPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('documentos.cadastro');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('documentos.cadastro');
    }

    public function update(User $user, Documento $documento): bool
    {
        return $user->hasPermissionTo('documentos.cadastro');
    }
}
