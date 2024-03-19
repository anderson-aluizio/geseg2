<?php

use App\Enum\RoleGroupEnum;
use App\Enum\TipoExibirEcluidosEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

if (!function_exists('loadUser')) {
    function loadUser()
    {
        return auth()->user()->load(['funcionario']);
    }
};

if (!function_exists('consoleOutput')) {
    function consoleOutput($value)
    {
        (new ConsoleOutput())->writeln($value);
    }
};

if (!function_exists('formatCnpjCpf')) {
    function formatCnpjCpf($value)
    {
        $cnpj_cpf = preg_replace("/\D/", '', $value);

        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
}

if (!function_exists('ajuste_competencia')) {
    function ajuste_competencia($ano, $mes)
    {
        return sprintf('%04d/%02d', $ano, $mes);
    }
}


if (!function_exists('onlyCompetencias')) {
    function onlyCompetencias(\Illuminate\Support\Collection $dados)
    {
        $ajusteCompetencia = function ($item) {
            return ajuste_competencia($item->ano, $item->mes);
        };
        return $dados->unique($ajusteCompetencia)
            ->sortBy($ajusteCompetencia)
            ->map(function ($item) {
                return [
                    'ano' => $item->ano,
                    'mes' => $item->mes,
                ];
            })
            ->values()
            ->all();
    }
}
if (!function_exists('sqlStrComprometido')) {
    function sqlStrComprometido($columnOrcado, $columnRealizado, $columReturn)
    {
        return "   CAST(
                         COALESCE(
                                sum($columnRealizado )/nullif(
                                    iif(
                                            sum($columnOrcado) > 0,
                                            sum($columnOrcado),
                                            sum($columnRealizado)- 0.1
                                        )
                                        ,0)
                                        ,0) AS float
                                        )  as $columReturn";
    }
}

if (!function_exists('onlyNaturezaId')) {
    function onlyNaturezaId(\Illuminate\Support\Collection $dados)
    {
        return $dados
            ->pluck('natureza_id')
            ->map(function ($item) {
                return trim($item);
            })
            ->values()
            ->all();
    }
}


if (!function_exists('dateYYYYMMDDtoDDMMYYYY')) {
    function dateYYYYMMDDtoDDMMYYYY($data)
    {
        $dia = substr($data, 6, 2);
        $mes = substr($data, 4, 2);
        $ano = substr($data, 0, 4);

        return sprintf("%s/%s/%s", $dia, $mes, $ano);
    }
}


if (!function_exists('checkPermission')) {

    function checkPermission($permissions, $noAbort = false)
    {
        if (!auth()->user()->can($permissions)) {
            if ($noAbort) {
                return false;
            }
            abort(403);
        }
        return true;
    }
}


if (!function_exists('checkRole')) {


    function checkRole($role, $noAbort = false)
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        if (!$user->hasRole($role)) {
            if ($noAbort) {
                return false;
            }
            abort(403);
        }
        return true;
    }
}


if (!function_exists('formataNumero')) {

    function formataNumero($valor)
    {
        return number_format($valor, 2, ',', '.');
    }
}


if (!function_exists('adicionaFiltros')) {

    function adicionaFiltros($query, $filterRequest, $sorts = [], $relaship = [], $extraFilters = [], $nomeTablePrincipal = '')
    {
        $operadores =   [
            'equal' => '=',
            'not_equal' => '<>',
            'like' => 'like',
            'greater_than' => '>=',
            'less_than' => '<=',
        ];
        $isDbQueryBuilder = !empty($nomeTablePrincipal);

        $filters = collect([]);
        foreach ($filterRequest as  $filter) {

            throw_unless(Arr::has($operadores, $filter['operator']), Exception::class, 'Invalid operator');

            if ($isDbQueryBuilder && !Str::contains($filter['field'], '.')) {
                $filter['field'] = $nomeTablePrincipal . '.' . $filter['field'];
            }

            if (!isset($filter['value'])) {
                $filter['value'] = '';
            }

            $filter['value'] =  is_array($filter['value']) ?
                $filter['value'] :
                addslashes(str_replace(',', '.', $filter['value']));
            $filters->add($filter);
        }
        $filtersGrouped = $filters->groupBy('field');

        $hasFilterExcluidos = false;
        foreach ($filtersGrouped as $filter) {

            $item = Arr::first($filter);
            if (Str::contains($item['field'], "filter_deleted")) {
                $hasFilterExcluidos = true;
                if ($isDbQueryBuilder) {
                    if ($item['value'] === TipoExibirEcluidosEnum::ApenasExcluidos->value) {
                        $query->whereNotNull($nomeTablePrincipal . '.' . 'deleted_at');
                    }
                    continue;
                }

                if ($item['value'] === TipoExibirEcluidosEnum::ApenasExcluidos->value) {
                    $query->onlyTrashed();
                    continue;
                }
                if ($item['value'] === TipoExibirEcluidosEnum::ComExcluidos->value) {
                    $query->withTrashed();
                    continue;
                }
            }

            if (Arr::has($relaship, $item['field'])) {
                $relashipItem = $relaship[$item['field']];

                if ($isDbQueryBuilder) {
                    $query->where(function ($q) use ($filter, $relashipItem, $operadores) {
                        foreach ($filter as $filtro) {
                            $filtro['value'] = $filtro['operator'] === 'like' ?    "%" . $filtro['value'] . "%" : $filtro['value'];
                            $q->orWhereRaw(
                                $relashipItem['relationship'] . '.' . $relashipItem['field'] . ' ' .  $operadores[$filtro['operator']] . ' ?',
                                $filtro['value']
                            );
                        }
                    });
                    continue;
                }

                $query->whereHas(
                    $relashipItem['relationship'],
                    function (Builder $queryHas) use ($filter,  $relashipItem, $operadores) {
                        $queryHas->where(function ($query) use ($filter, $relashipItem, $operadores) {
                            foreach ($filter as $filtro) {
                                $filtro['value'] = $filtro['operator'] === 'like' ?    "%" . $filtro['value'] . "%" : $filtro['value'];
                                $query->orWhereRaw(
                                    $relashipItem['field'] . ' ' .  $operadores[$filtro['operator']] . ' ?',
                                    $filtro['value']
                                );
                            }
                        });
                    }
                );
                continue;
            }

            $query->where(function ($query) use ($filter, $operadores) {
                foreach ($filter as $filtro) {
                    if (is_array($filtro['value'])) {
                        $query->orWhereRaw(
                            $filtro['field'] . '  between  ? and ? ',
                            [
                                $filtro['value'][0] . " 00:00:00",
                                $filtro['value'][1] . " 23:59:00"
                            ]
                        );
                        continue;
                    }

                    $strRaw = $filtro['field'] . ' ' . $operadores[$filtro['operator']] . ' ?';

                    $filtro['value'] = $filtro['operator'] === 'like' ?    "%" . $filtro['value'] . "%" : $filtro['value'];

                    $query->orWhereRaw($strRaw, $filtro['value']);
                }
            });
        }

        $query->where(function ($query) use ($extraFilters, $operadores) {
            foreach ($extraFilters as $filtro) {
                throw_unless(Arr::has($operadores, $filtro['operator']), Exception::class, 'Invalid operator');
                $strRaw = $filtro['field'] . ' ' . $operadores[$filtro['operator']] . ' ?';
                $filtro['value'] = $filtro['operator'] === 'like' ?    "%" . $filtro['value'] . "%" : $filtro['value'];
                $query->orWhereRaw($strRaw, $filtro['value']);
            }
        });

        if ($isDbQueryBuilder && !$hasFilterExcluidos && Schema::hasColumn($nomeTablePrincipal, 'deleted_at')) {
            $query->whereNull($nomeTablePrincipal . '.' . 'deleted_at');
        }

        if (count($sorts)) {
            foreach ($sorts as $coluna => $sortValue) {
                $orderBy = Arr::has($relaship, $coluna) ? $relaship[$coluna]['order_by'] : $coluna;
                $sortType = $sortValue == 1 ? 'asc' : 'desc';
                $query->orderBy($orderBy, $sortType);
            }
        }

        return $query;
    }
}
if (!function_exists('distinctFilter')) {
    function distinctFilter(array $filtro)
    {
        return collect($filtro)
            ->unique(
                function ($item) {
                    $value =  Arr::get($item, 'value', '');
                    $value =  is_array($value) ? implode('|', $value) : $value;
                    return $item['field'] . $item['operator'] . $value;
                }
            )
            ->values()
            ->all();
    }
}

if (!function_exists('collectEnum')) {
    function collectEnum(array $enum, bool $showName = false): Collection
    {
        $enumCollected = collect(array_map(fn ($i) => ($i), $enum));
        return $enumCollected
            ->when($showName, function ($collection) {
                return $collection->transform(
                    function ($item) {
                        return ['id' => $item->value, 'nome' => Str::title(implode(' ', Str::ucsplit($item->name)))];
                    }
                );
            }, function ($collection) {
                return $collection->transform(fn ($item) => ['id' => $item->value, 'nome' => $item->value]);
            });
    }
}

if (!function_exists('removerCaracterDeKeysOfArray')) {
    function removerCaracterDeKeysOfArray(array $data, string $caracter)
    {
        if (!count($data)) throw new \Exception("Dados em branco");
        return Arr::map($data, function ($row) use ($caracter) {
            return array_column(Arr::map($row, function ($val, $key) use ($caracter) {
                return [Str::replace($caracter, '', $key), $val];
            }, array_keys($row)), 1, 0);
        });
    }
}

if (!function_exists('adaptarRulesParaArray')) {
    /**
     * Renomea as regras de validações de um formulário para uma listagem de array
     */
    function adaptarRulesParaArray(Collection $rules): array
    {
        $rulesTypesByArrayRow = ['after:', 'gte:'];

        return $rules->mapWithKeys(
            function ($ruleRow, $ruleColumn) use ($rulesTypesByArrayRow) {
                return ['*.' . $ruleColumn => Arr::map(
                    $ruleRow,
                    function ($rule) use ($rulesTypesByArrayRow) {
                        Arr::map(
                            $rulesTypesByArrayRow,
                            function ($ruleTypesByArrayRow) use (&$rule) {
                                if (is_string($rule) && Str::startsWith($rule, $ruleTypesByArrayRow)) {
                                    return $rule = Str::replace($ruleTypesByArrayRow, $ruleTypesByArrayRow . '*.', $rule);
                                }
                            }
                        );
                        return $rule;
                    }
                )];
            }
        )->toArray();
    }
}


if (!function_exists('authUserIsAdmin')) {
    function authUserIsAdmin()
    {

        $user = auth()->user();
        if (!$user) return false;

        return $user->roles->pluck('name')->contains(RoleGroupEnum::Admin->name);
    }
}
