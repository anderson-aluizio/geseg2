<?php

namespace App\Domain\Apis;

use App\Contracts\ApiModelToImportInterface;
use App\Models\Funcionario;

class FuncionarioApi extends AtributosApi implements ApiModelToImportInterface
{
    public $funcionarios;

    public function __construct()
    {
        $this->funcionarios = Funcionario::withoutGlobalScope('centroCusto')->get();
        parent::__construct([
            ['isPrimaryKey' => true, 'columnName' => 'id', 'rules' => ['required']],
            ['isPrimaryKey' => true, 'columnName' => 'cpf', 'rules' => ['required']],
            ['columnName' => 'matricula', 'rules' => ['required', 'string']],
            ['columnName' => 'filial', 'rules' => ['required', 'string']],
            ['columnName' => 'codunic', 'rules' => ['nullable', 'string']],
            ['columnName' => 'centro_custo_id', 'rules' => ['required', 'string']],
            ['columnName' => 'centro_custo_analitico_id', 'rules' => ['required', 'string']],
            ['columnName' => 'nome', 'rules' => ['required', 'string']],
            ['columnName' => 'cargo_id', 'rules' => ['string']],
            ['columnName' => 'situacao', 'rules' => ['string']],
            ['columnName' => 'data_admissao', 'rules' => ['date']],
            ['columnName' => 'data_demissao', 'rules' => ['nullable', 'date']],
            ['columnName' => 'cadastro_manual', 'rules' => ['boolean']]
        ]);
    }

    public function handle(): bool
    {
        $model = new Funcionario();
        $token = config('geseg.VALORIZAR_TOKEN');
        $url = config('geseg.VALORIZAR_BASE_URL') . '/api/funcionarios';
        $model->truncate();
        $importarApi = new ImportarApi($this, $model, $token, $url);
        return $importarApi->importar();
    }
}
