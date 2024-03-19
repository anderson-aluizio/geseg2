<?php

namespace Database\Seeders;

use App\Domain\CreateCustomPermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        app()['cache']->forget('spatie.role.cache');
        app()['cache']->forget('spatie.permission.cache');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::findOrCreate('Admin', 'web')->givePermissionTo(Permission::all());
    }
}
