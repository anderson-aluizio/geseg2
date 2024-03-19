<?php

namespace App\Domain\Apis;

use App\Contracts\ApiModelToImportInterface;
use App\Models\CentroCusto;

class CentroCustoApi extends AtributosApi implements ApiModelToImportInterface
{
    public function __construct()
    {
        parent::__construct([
            ['isPrimaryKey' => true, 'columnName' => 'id', 'rules' => ['required']],
            ['columnName' => 'localidade_estado_id', 'rules' => ['required', 'integer']],
            [
                'columnName' => 'nome', 'rules' => ['required', 'string'],
                'callback' => fn ($val) => mb_strtoupper($val)
            ],
        ]);
    }

    public function handle(): bool
    {
        $model = new CentroCusto();
        $token = config('geseg.JUPITER_TOKEN');
        $url = config('geseg.JUPITER_BASE_URL') . '/api/centro-custos';
        $importarApi = new ImportarApi($this, $model, $token, $url);
        return $importarApi->importar();
    }
}
