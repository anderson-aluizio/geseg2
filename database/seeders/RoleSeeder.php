<?php

namespace Database\Seeders;

use App\Domain\CreateCustomPermission;
use App\Models\CentroCusto;
use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'gestao.cadastro_usuarios', 'group' => 'sistema'],
            ['name' => 'gestao.controle_acessos', 'group' => 'sistema'],
            ['name' => 'inicio', 'group' => 'inicio'],
        ];

        app()['cache']->forget('spatie.role.cache');
        app()['cache']->forget('spatie.permission.cache');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($permissions as $permission) {
            CreateCustomPermission::findOrCreate($permission['name'], $permission['group']);
        }

        Role::findOrCreate('Admin', 'web')->givePermissionTo(Permission::all());

        Artisan::call('geseg:atualiza-funcionarios');
        Artisan::call('geseg:atualiza-centro-custos');
        Artisan::call('geseg:atualiza-localidades');
        Artisan::call('geseg:atualiza-cargos');
        Artisan::call('geseg:atualiza-centro-custo-analiticos');

        $admin = User::firstOrNew(
            ['email' => 'anderson.nogueira@dinamo.srv.br'],
            ['name' => 'Anderson', 'funcionario_id' => Funcionario::withoutGlobalScopes()->where('cpf', '05547242357')->first()->id, 'password' => Hash::make('dinamo123')]
        );
        $admin->save();
        $admin->assignRole('Admin');
        $admin->syncPermissions(Permission::all());
        $admin->centroCustos()->sync(CentroCusto::withoutGlobalScopes()->get());
    }
}
