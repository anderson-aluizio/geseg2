<?php

namespace App\Domain\Apis;

use App\Contracts\ApiAtributosPaiInterface;
use Illuminate\Support\Arr;

class AtributosApi implements ApiAtributosPaiInterface
{
    protected array $atributos;

    public function __construct(array $atributos)
    {
        foreach ($atributos as $atributo) {
            try {
                $this->atributos[] = new AtributoApi(
                    $atributo['isPrimaryKey'] ?? false,
                    $atributo['columnName'],
                    $atributo['rules'],
                    $atributo['apiColumnName'] ?? null,
                    $atributo['callback'] ?? null,
                );
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function listarPrimaryKeys(): array
    {
        return Arr::pluck(Arr::where($this->atributos, fn (AtributoApi $atributo) => $atributo->isPrimaryKey), 'columnName');
    }

    public function listarColumnNames(): array
    {
        return Arr::map(($this->atributos), fn (AtributoApi $atributo) => $atributo->columnName);
    }

    public function listarAtributosParaCustomizar(): array
    {
        return Arr::collapse(Arr::map($this->atributos, fn (AtributoApi $atributo) => [$atributo->columnName => $atributo]));
    }

    public function listarRules(): array
    {
        return Arr::collapse(Arr::pluck(($this->atributos), 'rules'));
    }

    public function possuiColunaParaRenomear(): bool
    {
        $res = false;
        Arr::map($this->atributos, function (AtributoApi $atributo) use (&$res) {
            if ($atributo->hasCustomColumnName) return $res = true;
        });
        return $res;
    }

    public function possuiCallback(): bool
    {
        $res = false;
        Arr::map($this->atributos, function (AtributoApi $atributo) use (&$res) {
            if ($atributo->hasCallback) return $res = true;
        });
        return $res;
    }
}
