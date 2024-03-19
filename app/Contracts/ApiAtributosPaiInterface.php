<?php

namespace App\Contracts;

interface ApiAtributosPaiInterface
{
    public function listarPrimaryKeys(): array;
    public function listarColumnNames(): array;
    public function listarAtributosParaCustomizar(): array;
    public function listarRules(): array;
    public function possuiColunaParaRenomear(): bool;
    public function possuiCallback(): bool;
}
