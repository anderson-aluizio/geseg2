<?php

namespace App\Domain\Apis;

use App\Contracts\ApiModelToImportInterface;
use App\Models\Cargo;

class CargoApi extends AtributosApi implements ApiModelToImportInterface
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
                'columnName' => 'nome',
                'rules' => ['required', 'string'],
            ],
        ]);
    }

    public function handle(): bool
    {
        $model = new Cargo();
        $token = config('geseg.VALORIZAR_TOKEN');
        $url = config('geseg.VALORIZAR_BASE_URL') . '/api/cargos';
        $importarApi = new ImportarApi($this, $model, $token, $url);
        return $importarApi->importar();
    }
}
