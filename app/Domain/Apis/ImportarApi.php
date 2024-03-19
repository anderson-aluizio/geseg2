<?php

namespace App\Domain\Apis;

use App\Contracts\ApiModelToImportInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Output\ConsoleOutput;

class ImportarApi
{
    protected ApiModelToImportInterface $apiModel;
    protected Model $model;
    protected string $token;
    protected string $url;
    protected array $params;

    public function __construct(ApiModelToImportInterface $apiModel, Model $model, string $token, string $url, array $params = [])
    {
        $this->apiModel = $apiModel;
        $this->model = $model;
        $this->token = $token;
        $this->url = $url;
        $this->params = $params;
    }


    public function importar(): bool
    {
        $output = new ConsoleOutput();
        $rules = $this->apiModel->listarRules();
        $primaryKeys = $this->apiModel->listarPrimaryKeys();
        $columnNames = $this->apiModel->listarColumnNames();
        $page = 0;
        do {
            $page++;

            $response = Http::acceptJson()->withToken($this->token)->get($this->url, Arr::add($this->params, 'page', $page));
            if ($response->status() != 200) {
                throw new \Exception($response->status());
            }
            if (!count($response->json('data'))) {
                throw new \Exception('Reponse Error: Nenhum registro encontrado');
            }

            $validatedData = $this->validarDados($response->json('data'), $rules);

            if (!count($validatedData)) {
                $output->writeln('Não há dados validados!');
                continue;
            }

            if ($this->apiModel->possuiColunaParaRenomear() || $this->apiModel->possuiCallback()) {
                $validatedData = $this->customizarDados($validatedData, $this->apiModel->listarAtributosParaCustomizar());
            }

            $this->model::upsert($validatedData, $primaryKeys, $columnNames);
            $output->writeln('Página ' . $page . ' atualizada!');
        } while ((isset($response['links']['next']) && $response['links']['next'] != NULL) || (isset($response['next_page_url']) && $response['next_page_url'] != NULL));
        $output->writeln('Finalizado');
        return true;
    }

    public function validarDados(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $keysWithError = Arr::map($validator->errors()->keys(), (fn ($item) => Str::of($item)->before('.')->value()));
            return $this->validarDados(Arr::except($data, $keysWithError), $rules);
        }
        return $validator->validated();
    }

    public function customizarDados(array $data, array $dataToCustom)
    {
        $res = [];
        foreach ($data as $obj) {
            foreach ($dataToCustom as $column) {
                if ($column->hasCallback) {
                    $row[$column->columnName] = ($column->callback)($obj[$column->apiColumnName] ?? null, $obj);
                    continue;
                }
                $row[$column->columnName] = $obj[$column->apiColumnName];
            }
            $res[] = $row;
            unset($row);
        }
        return $res;
    }
}
