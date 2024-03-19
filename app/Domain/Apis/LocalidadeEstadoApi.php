<?php

namespace App\Domain\Apis;

use App\Contracts\ApiModelToImportInterface;
use App\Models\LocalidadeEstado;

class LocalidadeEstadoApi extends AtributosApi implements ApiModelToImportInterface
{
    public function __construct()
    {
        parent::__construct([
            ['isPrimaryKey' => true, 'columnName' => 'id', 'rules' => ['required']],
            ['columnName' => 'nome', 'rules' => ['required', 'string']],
            ['columnName' => 'sigla', 'rules' => ['required', 'string']],
        ]);
    }

    public function handle(): bool
    {
        $model = new LocalidadeEstado();
        $token = config('geseg.JUPITER_TOKEN');
        $url = config('geseg.JUPITER_BASE_URL') . '/api/localidade-estados';
        $importarApi = new ImportarApi($this, $model, $token, $url);
        return $importarApi->importar();
    }
}
