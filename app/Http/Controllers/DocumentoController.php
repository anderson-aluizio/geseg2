<?php

namespace App\Http\Controllers;

use AndersonNogueira\Datatable\DataTable;
use App\Enum\DefaultStateEnum;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Services\BannerMessage;
use Inertia\Inertia;

class DocumentoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Documento::class, 'documento');
    }

    public function index(Request $request)
    {
        $columns = collect([
            ['field' => 'nome', 'label' => 'Nome', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'nome_abreviado', 'label' => 'Nome Abreviado', 'isSortable' => true, 'isGlobalSearch' => true],
            [
                'field' => 'documento_pai.nome', 'label' => 'Documento Relacionado', 'isSortable' => true,
                'fnOrderBy' => Documento::from('documentos as documento_pais')->select('nome')->whereColumn('documento_pais.id', 'documentos.documento_pai_id')
            ],
            ['field' => 'status', 'label' => 'Status', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'prazo_em_dias', 'label' => 'Prazo em Dias', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'id', 'label' => '#'],
        ]);
        $data = new DataTable(Documento::with('documentoPai'), $columns, $request);
        return Inertia::render('Documentos/Index', [
            'dataTable' => $data->get(),
            'statuses' => collectEnum(DefaultStateEnum::cases()),
            'documentos' => Documento::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:documentos'],
            'nome_abreviado' => ['required', 'string', 'max:20', 'unique:documentos', 'regex:/^\S*$/'],
            'documento_pai_id' => ['nullable', 'exists:documentos,id'],
            'prazo_em_dias' => ['nullable', 'integer'],
        ]);
        $documentoPaiEnviadoJaUtilizado = Documento::where('documento_pai_id', $validatedData['documento_pai_id'])->exists();
        if ($validatedData['documento_pai_id'] && $documentoPaiEnviadoJaUtilizado) {
            return Redirect::back()->withErrors([
                'documento_pai_id' => 'O documento anterior relacionado enviado já está sendo utilizado em outro registro.'
            ]);
        }
        Documento::create($validatedData);
        BannerMessage::message('Registro cadastrado com sucesso!');
        return Redirect::route('documentos.index');
    }

    public function update(Request $request, Documento $documento)
    {
        $validatedData = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:documentos,nome,' . $documento->id],
            'nome_abreviado' => ['required', 'string', 'max:20', 'unique:documentos,nome_abreviado,' . $documento->id],
            'documento_pai_id' => ['nullable', 'exists:documentos,id'],
            'prazo_em_dias' => ['nullable', 'integer'],
            'status' => ['required', 'in:' . collectEnum(DefaultStateEnum::cases())->implode('id', ',')],
        ]);
        $documentoPaiEhOMesmo = $documento->id === $validatedData['documento_pai_id'];
        if ($documentoPaiEhOMesmo) {
            return Redirect::back()->withErrors([
                'documento_pai_id' => 'O documento anterior relacionado enviado não pode ser o mesmo que o atual.'
            ]);
        }
        $documentoPaiEnviadoJaUtilizado = Documento::where('id', '!=', $documento->id)
            ->where('documento_pai_id', $validatedData['documento_pai_id'])
            ->exists();
        if ($validatedData['documento_pai_id'] && $documentoPaiEnviadoJaUtilizado) {
            return Redirect::back()->withErrors([
                'documento_pai_id' => 'O documento anterior relacionado enviado já está sendo utilizado em outro registro.'
            ]);
        }
        $documento->update($validatedData);
        BannerMessage::message('Registro atualizada com sucesso!');
        return Redirect::route('documentos.index');
    }
}
