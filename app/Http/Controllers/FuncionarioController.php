<?php

namespace App\Http\Controllers;

use AndersonNogueira\Datatable\DataTable;
use App\Models\Cargo;
use App\Models\CentroCusto;
use App\Models\DocumentoFuncionario;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FuncionarioController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Funcionario::class, 'funcionario');
    }

    public function index(Request $request)
    {
        $columns = collect([
            ['field' => 'nome', 'label' => 'Nome', 'isGlobalSearch' => true, 'isSortable' => true],
            ['field' => 'situacao', 'label' => 'Situação', 'isSortable' => true],
            [
                'field' => 'data_admissao', 'label' => 'Admissão', 'isSortable' => true,
                'advancedFilter' => [
                    'filterOperators' => ['gt', 'ge', 'lt', 'le', 'eq', 'ne'],
                    'filterFieldType' => 'date',
                    'filterFieldConfig' => ['valueFormat' => 'YYYY-MM-DD']
                ]
            ],
            [
                'field' => 'matricula', 'label' => 'Matrícula', 'isGlobalSearch' => true, 'isSortable' => true,
                'advancedFilter' => ['filterOperators' => ['eq', 'ne', 'like']]
            ],
            [
                'field' => 'cargo.nome', 'label' => 'Cargo', 'isGlobalSearch' => true, 'isSortable' => true,
                'fnOrderBy' => Cargo::select('nome')->whereColumn('cargos.id', 'funcionarios.cargo_id'),
                'advancedFilter' => [
                    'filterFieldName' => 'cargo_id',
                    'filterFieldType' => 'select-search',
                    'filterFieldConfig' => [
                        'primarykey' => 'id',
                        'label' => 'nome',
                        'urlSearch' => 'cargos',
                        'objectReturn' => true,
                    ]
                ]
            ],
            [
                'field' => 'centro_custo.nome', 'label' => 'Centro Custo', 'isGlobalSearch' => true,
                'fnOrderBy' => CentroCusto::select('nome')->whereColumn('centro_custos.id', 'funcionarios.centro_custo_id'),
                'advancedFilter' => [
                    'filterFieldName' => 'centro_custo_id',
                    'filterFieldType' => 'select',
                    'filterFieldConfig' => [
                        'primarykey' => 'id',
                        'label' => 'nome',
                        'items' => CentroCusto::select('id', 'nome')->get()->toArray(),
                    ]
                ]
            ],
            ['field' => 'id', 'label' => '#'],
        ]);
        $model = Funcionario::with('cargo', 'centroCusto', 'centroCustoAnalitico');
        $data = new DataTable($model, $columns, $request);
        return Inertia::render('Funcionarios/Index', [
            'dataTable' => $data->get(),
            'columns' => $columns
        ]);
    }

    public function show(Funcionario $funcionario)
    {
        return Inertia::render('Funcionarios/Show', [
            'funcionario' => $funcionario->load('cargo', 'centroCusto', 'centroCustoAnalitico'),
            'documentos' => DocumentoFuncionario::with(['criador', 'pesquisa'])->where('funcionario_id', $funcionario->id)->vencidosEAVencer()->orderBy('prazo_final', 'desc')->get(),
        ]);
    }
}
