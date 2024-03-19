<?php

namespace App\Domain\Apis;

use App\Contracts\ApiModelToImportInterface;
use App\Models\LocalidadeCidade;

class LocalidadeCidadeApi extends AtributosApi implements ApiModelToImportInterface
{
    public function __construct()
    {
        parent::__construct([
            ['isPrimaryKey' => true, 'columnName' => 'id', 'rules' => ['required']],
            ['columnName' => 'nome', 'rules' => ['required', 'string']],
            ['columnName' => 'localidade_estado_id', 'rules' => ['required', 'integer']],
        ]);
    }

    public function handle(): bool
    {
        $model = new LocalidadeCidade();
        $token = config('geseg.JUPITER_TOKEN');
        $url = config('geseg.JUPITER_BASE_URL') . '/api/localidade-cidades';
        $importarApi = new ImportarApi($this, $model, $token, $url);
        return $importarApi->importar();
    }
}
