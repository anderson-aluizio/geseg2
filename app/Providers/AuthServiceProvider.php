<?php

namespace App\Providers;

use App\Models\Funcionario;
use App\Models\User;
use App\Policies\AccessControlPolicy;
use App\Policies\FuncionarioPolicy;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Role::class => AccessControlPolicy::class,
        User::class => UserPolicy::class,
        Funcionario::class => FuncionarioPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
