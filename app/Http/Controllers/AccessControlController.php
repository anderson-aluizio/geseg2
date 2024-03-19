<?php

namespace App\Http\Controllers;

use App\Enum\RoleGroupEnum;
use App\Services\BannerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccessControlController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $permissions =
            Permission::with('roles:id')
            ->get(['id', 'name', 'group_name'])
            ->groupBy(function ($item) {
                return (string) Str::of($item->group_name)
                    ->replace('_', ' ')
                    ->title();
            })
            ->sortBy(fn ($item, $key) => $key)
            ->transform(function ($item, $key) {
                return $item->groupBy(function ($item, $key) {
                    return (string) Str::of($item->name)
                        ->before('.')
                        ->replace('_', ' ')
                        ->title();
                })->sortBy(fn ($item, $key) => $key);
            })
            ->map(function ($item) {
                return $item->map(function ($item, $key) {
                    return $item->map(function ($item, $key) {
                        return [
                            'id' => $item->id,
                            'search_index' =>  Str::of($item->name)->replace('.', ' ')->replace('_', ' '),
                            'name' => Str::of($item->name)->after('.')->replace('_', ' ')->title(), // @phpstan-ignore-line
                            'group_name' => Str::of($item->group_name)->replace('_', ' ')->title(), // @phpstan-ignore-line
                            'roles' => $item->roles->sortBy('name')
                        ];
                    })->sortBy('name')->values();
                });
            });

        $roles = Role::with('permissions:id')
            ->where('name', '!=', 'Admin')
            ->get(['id', 'name'])
            ->map(function ($item) {
                $item->permissions->transform(function ($item) {
                    return $item->id;
                });
                return $item;
            })->toArray();

        return Inertia::render('AccessControl/Index', [
            'roles' => $roles,
            'permissions' =>  $permissions
        ]);
    }

    public function storeRole(Request $request)
    {
        $this->authorize('storeRole', Role::class);

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $dataValidated = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:roles'
            ]
        )->validate();
        $dataValidated['name'] = ucfirst($dataValidated['name']);
        $dataValidated['guard_name'] = 'web';

        Role::create($dataValidated);
        return Redirect::back();
    }

    public function updateRole(Request $request, Role $role)
    {
        $this->authorize('updateRole', Role::class);

        $validatedRequest = $request->validate([
            'name' => ['required', Rule::unique('roles')->ignore($role->id)]
        ]);
        if (in_array($role->id, [RoleGroupEnum::Admin->value, RoleGroupEnum::Colaborador->value]) && $role->name != $validatedRequest['name']) {
            BannerMessage::message("O nome deste grupo não pode ser alterado.", "danger");
            return Redirect::route('accesscontrol.index');
        }

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $role->syncPermissions($request->input('permissions'));

        $role->update($validatedRequest);
        BannerMessage::message("Registro atualizado");
        return Redirect::back();
    }

    public function deleteRole(Role $role)
    {
        if (in_array($role->id, [RoleGroupEnum::Admin->value, RoleGroupEnum::Colaborador->value])) {
            BannerMessage::message("Este grupo não pode ser excluído", "danger");
            return Redirect::route('accesscontrol.index');
        }

        if ($role->users()->count()) {
            BannerMessage::message("Existem usuários vinculados a este grupo, desvincule-os para depois excluir!", "danger");
            return Redirect::back();
        }

        $role->delete();
        BannerMessage::message("Registro apagado");
        return Redirect::back();
    }
}
