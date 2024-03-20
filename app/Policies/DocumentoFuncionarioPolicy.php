<?php

namespace App\Policies;

use App\Models\User;

class DocumentoFuncionarioPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('colaborador.visualizar_documentos') || $user->hasPermissionTo('colaborador.cadastrar_documentos');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('colaborador.visualizar_documentos') || $user->hasPermissionTo('colaborador.cadastrar_documentos');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('colaborador.visualizar_documentos') || $user->hasPermissionTo('colaborador.cadastrar_documentos');
    }
}
