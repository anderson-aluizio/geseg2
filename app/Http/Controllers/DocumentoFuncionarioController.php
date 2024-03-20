<?php

namespace App\Http\Controllers;

use AndersonNogueira\Datatable\DataTable;
use App\Enum\DefaultStateEnum;
use App\Models\Documento;
use App\Models\DocumentoFuncionario;
use App\Models\Funcionario;
use App\Services\BannerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DocumentoFuncionarioController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(DocumentoFuncionario::class, 'documento_funcionario');
    }

    public function index(Request $request, Funcionario $funcionario)
    {
        $columns = collect([
            [
                'field' => 'documento.nome', 'label' => 'Nome', 'isSortable' => true, 'isGlobalSearch' => true,
                'fnOrderBy' => Documento::select('nome')->whereColumn('documentos.id', 'documento_funcionarios.documento_id')
            ],
            [
                'field' => 'documento.nome_abreviado', 'label' => 'Nome Abreviado', 'isSortable' => true, 'isGlobalSearch' => true,
                'fnOrderBy' => Documento::select('nome')->whereColumn('documentos.id', 'documento_funcionarios.documento_id')
            ],
            ['field' => 'status', 'label' => 'Status', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'prazo_inicial', 'label' => 'Prazo Inicial', 'isSortable' => true],
            ['field' => 'prazo_final', 'label' => 'Prazo Final', 'isSortable' => true],
            ['field' => 'id', 'label' => '#'],
        ]);
        $data = new DataTable(DocumentoFuncionario::with('documento', 'criador', 'editor'), $columns, $request);
        return Inertia::render('DocumentoFuncionarios/Index', [
            'dataTable' => $data->get(),
            'statuses' => collectEnum(DefaultStateEnum::cases()),
            'documentos' => Documento::all(),
            'funcionario' => $funcionario,
        ]);
    }

    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            'funcionario_id' => 'required', 'exists:funcionarios,id',
            'documento_id' => 'required', 'exists:documentos,id',
        ]);
        $validatedRequest['created_by'] = auth()->id();
        $validatedRequest['updated_by'] = auth()->id();
        $validatedRequest['funcionario_cpf'] = Funcionario::find($validatedRequest['funcionario_id'])->cpf;
        $validatedRequest['identificador'] = DocumentoFuncionario::where('documento_id', $validatedRequest['documento_id'])
            ->where('funcionario_id', $validatedRequest['funcionario_id'])->count() + 1;
        DocumentoFuncionario::create($validatedRequest);
        BannerMessage::message('Registro cadastrado com sucesso!');
        return Redirect::back();
    }
}
