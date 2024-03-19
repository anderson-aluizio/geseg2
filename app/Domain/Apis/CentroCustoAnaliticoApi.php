<?php

namespace App\Domain\Apis;

use App\Contracts\ApiModelToImportInterface;
use App\Models\CentroCustoAnalitico;

class CentroCustoAnaliticoApi extends AtributosApi implements ApiModelToImportInterface
{

    public function __construct()
    {
        parent::__construct([
            [
                'isPrimaryKey' => true,
                'columnName' => 'id',
                'rules' => ['required'],
            ],
            [
                'columnName' => 'centro_custo_id',
                'rules' => ['required', 'string'],
            ],
            [
                'columnName' => 'nome',
                'rules' => ['required', 'string'],
            ],
        ]);
    }

    public function handle(): bool
    {
        $model = new CentroCustoAnalitico();
        $token = config('geseg.VALORIZAR_TOKEN');
        $url = config('geseg.VALORIZAR_BASE_URL') . '/api/centro-custo-analiticos';
        $importarApi = new ImportarApi($this, $model, $token, $url);
        return $importarApi->importar();
    }
}
