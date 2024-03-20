<?php

namespace App\Policies;

use App\Models\DocumentoFuncionario;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentoFuncionarioPolicy
{
    public function viewAny(User $user): bool
    {
        //
    }

    public function view(User $user, DocumentoFuncionario $documentoFuncionario): bool
    {
        //
    }

    public function create(User $user): bool
    {
        //
    }

    public function update(User $user, DocumentoFuncionario $documentoFuncionario): bool
    {
        //
    }

    public function delete(User $user, DocumentoFuncionario $documentoFuncionario): bool
    {
        //
    }

    public function restore(User $user, DocumentoFuncionario $documentoFuncionario): bool
    {
        //
    }

    public function forceDelete(User $user, DocumentoFuncionario $documentoFuncionario): bool
    {
        //
    }
}
