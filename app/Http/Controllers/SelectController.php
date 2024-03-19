<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\CentroCusto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Funcionario;
use App\Models\Pergunta;
use App\Models\User;

class SelectController extends Controller
{
    public function handler(Request $request)
    {
        $validated = $request->validate([
            'action' => 'bail|required',
        ]);

        try {
            return match ($validated['action']) {
                'funcionarios' => $this->getFuncionarios($request),
                'cargos' => $this->getCargos($request),
                'centro_custos' => $this->getCentroCustos($request),
                'users' => $this->getUsers($request),
            };
        } catch (\UnhandledMatchError $e) {
            return $e;
        }
    }

    public function getFuncionarios(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required',
            'without_global_scope' => ['required', 'boolean'],
        ]);
        $termo = Str::upper($validated['query']);
        $termo = "%{$termo}%";
        if ($validated['without_global_scope'] || authUserIsAdmin()) {
            return Funcionario::query()
                ->withoutGlobalScope('centroCusto')
                ->contratados()
                ->selectRaw("funcionarios.id, CONCAT(funcionarios.nome, ' - ', funcionarios.matricula) as nome, centro_custos.nome as subtext")
                ->selectRaw("cargos.nome as cargo")
                ->join('centro_custos', 'funcionarios.centro_custo_id', '=', 'centro_custos.id')
                ->join('cargos', 'cargos.id', '=', 'funcionarios.cargo_id')
                ->when($termo, function ($query, $termo) {
                    return  $query->whereRaw('(funcionarios.nome like ? or funcionarios.matricula like ?)', [$termo, $termo]);
                })
                ->orderBy('funcionarios.nome')
                ->limit(10)
                ->get();
        }
        return Funcionario::query()
            ->contratados()
            ->selectRaw("funcionarios.id, CONCAT(funcionarios.nome, ' - ', funcionarios.matricula) as nome, centro_custos.nome as subtext")
            ->selectRaw("cargos.nome as cargo")
            ->join('centro_custos', 'funcionarios.centro_custo_id', '=', 'centro_custos.id')
            ->join('cargos', 'cargos.id', '=', 'funcionarios.cargo_id')
            ->when($termo, function ($query, $termo) {
                return  $query->whereRaw('(funcionarios.nome like ? or funcionarios.matricula like ?)', [$termo, $termo]);
            })
            ->limit(10)
            ->get();
    }

    public function getCargos(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required',
        ]);
        $termo = Str::upper($validated['query']);
        $termo = "%{$termo}%";
        return Cargo::where('nome', 'like', $termo . '%')->get();
    }

    public function getCentroCustos(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required',
        ]);
        $termo = Str::upper($validated['query']);
        $termo = "%{$termo}%";
        return CentroCusto::where('nome', 'like', $termo . '%')->get();
    }

    public function getUsers(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required',
        ]);
        $termo = "%{$validated['query']}%";
        return User::queryFiltradosPorPermissao()
            ->where('name', 'like', $termo)
            ->selectRaw("users.id, users.name as nome")
            ->limit(10)
            ->get();
    }
}
