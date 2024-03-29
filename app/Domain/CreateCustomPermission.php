<?php

namespace App\Domain;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateCustomPermission
{
    public static function findOrCreate(string $name, string $groupName)
    {
        $permission = Permission::findOrCreate($name, 'web');
        if ($permission) {
            DB::table('permissions')
                ->where('id', $permission->id)
                ->update(['group_name' => $groupName]);
        }

        app()['cache']->forget('spatie.role.cache');
        app()['cache']->forget('spatie.permission.cache');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::findOrCreate('Admin', 'web')->givePermissionTo(Permission::all());
        return $permission;
    }
}
