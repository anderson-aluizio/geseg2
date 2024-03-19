<?php

namespace App\Http\Controllers;

use AndersonNogueira\Datatable\DataTable;
use App\Models\Cargo;
use App\Models\Documento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CargoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Cargo::class, 'cargo');
    }

    public function index(Request $request)
    {
        $columns = collect([
            ['field' => 'nome', 'label' => 'Nome', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'area', 'label' => 'Area', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'grupo', 'label' => 'Grupo', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'subgrupo', 'label' => 'Sub-Grupo', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'setor', 'label' => 'Setor', 'isSortable' => true, 'isGlobalSearch' => true],
            ['field' => 'id', 'label' => '#']
        ]);
        $model = Cargo::query();
        $data = new DataTable($model, $columns, $request);
        return Inertia::render('Cargos/Index', [
            'dataTable' => $data->get(),
        ]);
    }

    public function edit(Cargo $cargo)
    {
        return Inertia::render('Cargos/Edit', [
            'cargo' => $cargo->load('documentos'),
            'documentos' => Documento::whereDoesntHave('cargos', fn ($query) => $query->where('cargo_id', $cargo->id))->ativos()->get(),
        ]);
    }
}
