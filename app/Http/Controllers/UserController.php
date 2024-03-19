<?php

namespace App\Http\Controllers;

use App\Enum\DefaultStateEnum;
use App\Enum\RoleGroupEnum;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\UserCreated;
use App\Models\CentroCusto;
use App\Models\Funcionario;
use App\Models\User;
use App\Services\BannerMessage;
use AndersonNogueira\Datatable\DataTable;
use App\Enum\UserStateEnum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        $columns = collect([
            ['field' => 'name', 'label' => 'Nome', 'isSortable' => true],
            [
                'field' => 'roles.name', 'label' => 'Grupo', 'isSortable' => true,
                'fnOrderBy' => Role::select('name')
                    ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
            ],
            ['field' => 'state', 'label' => 'Status', 'isSortable' => true],
            ['field' => 'id', 'label' => '#'],
        ]);
        $data = new DataTable(User::with('roles'), $columns, $request);
        return Inertia::render('Users/Index', [
            'dataTable' => $data->get(),
        ]);
    }

    public function create()
    {
        $userIsAdmin = User::find(auth()->id())->hasRole(RoleGroupEnum::Admin->name);
        $centroCustos = CentroCusto::when($userIsAdmin, function ($query) {
            return  $query->withoutGlobalScope('centroCusto');
        })->orderBy('nome')->get(['id', 'nome']);
        $roles = Role::when(!User::find(auth()->id())->hasRole(RoleGroupEnum::Admin->name), function ($query) {
            return $query->where('id', '!=', RoleGroupEnum::Admin->value);
        })->get();
        return Inertia::render('Users/Create', [
            'states' => collectEnum(DefaultStateEnum::cases()),
            'centroCustos' => $centroCustos,
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $validatedRequest = $request->validated();
        $funcionario = Funcionario::withoutGlobalScope('centroCusto')->find($validatedRequest['funcionario_id']);
        $password = 'dinamo123';
        $validatedRequest['name'] = $funcionario->nome;
        $validatedRequest['password'] = Hash::make($password);
        $user = User::create($validatedRequest);
        $user->centroCustos()->sync($validatedRequest['centro_custos']);
        $user->syncRoles([$validatedRequest['role_id']]);
        Mail::to($user->email)->send(new UserCreated($user, $password));

        BannerMessage::message("Registro cadastrado");
        return Redirect::route('users.index');
    }

    public function edit(User $user)
    {
        $user->load('funcionario:id,nome', 'centroCustos');
        $user->centroCustos->transform(fn ($item) => $item->id);
        $user->role_id = $user->roles->first()->id;

        $userIsAdmin = User::find(auth()->id())->hasRole(RoleGroupEnum::Admin->name);
        $centroCustos = CentroCusto::when($userIsAdmin, function ($query) {
            return  $query->withoutGlobalScope('centroCusto');
        })->orderBy('nome')->get(['id', 'nome']);
        $roles = Role::when(!User::find(auth()->id())->hasRole(RoleGroupEnum::Admin->name), function ($query) {
            return $query->where('id', '!=', RoleGroupEnum::Admin->value);
        })->get();
        return Inertia::render('Users/Edit', [
            'user' => $user,
            'states' => collectEnum(UserStateEnum::cases()),
            'centroCustos' => $centroCustos,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedRequest = $request->validated();

        $user->update($validatedRequest);
        $user->centroCustos()->sync($validatedRequest['centro_custos']);
        $user->syncRoles([$validatedRequest['role_id']]);

        BannerMessage::message("Registro atualizado");
        return Redirect::route('users.index');
    }
}
