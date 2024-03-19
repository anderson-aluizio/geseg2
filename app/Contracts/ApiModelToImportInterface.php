<?php

namespace App\Contracts;


interface ApiModelToImportInterface extends ApiAtributosPaiInterface
{
    public function handle(): bool;
}
