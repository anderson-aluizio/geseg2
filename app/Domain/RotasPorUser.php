<?php

namespace App\Domain;

use Illuminate\Support\Collection;

final class RotasPorUser
{

    public static function listar(Collection $userPemissions)
    {
        $rotas = collect([
            [
                'title' => 'Consultas',
                'permissions' => ['colaborador.visualizar', 'cargo.visualizar'],
                'icon' => [
                    'element' => 'font-awesome-icon',
                    'attributes' => [
                        'icon' => 'magnifying-glass',
                        'class' => 'text-[#bc272d] p-1 text-xl'
                    ],
                ],
                'child' => [
                    [
                        'title' => 'Colaboradores',
                        'href' => route('funcionarios.index'),
                        'permissions' => ['colaborador.visualizar'],
                    ]
                ]
            ],
            [
                'title' => 'Cadastro',
                'permissions' => [
                    'documentos.cadastro', 'cargo.visualizar', 'cargo.cadastro'
                ],
                'icon' => [
                    'element' => 'font-awesome-icon',
                    'attributes' => [
                        'icon' => 'pen-to-square',
                        'class' => 'text-[#bc272d] p-1 text-xl'
                    ],
                ],
                'child' => [
                    [
                        'title' => 'Documentos',
                        'href' => route('documentos.index'),
                        'permissions' => [
                            'documentos.cadastro',
                        ],
                    ],
                    [
                        'title' => 'Cargos',
                        'href' => route('cargos.index'),
                        'permissions' => [
                            'cargos.visualizar', 'cargos.cadastro'
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Sistema',
                'permissions' => [
                    'gestao.controle_acessos',
                    'gestao.cadastro_usuarios'
                ],
                'icon' => [
                    'element' => 'font-awesome-icon',
                    'attributes' => [
                        'icon' => 'gear',
                        'class' => 'text-[#bc272d] p-1 text-xl'
                    ],
                ],
                'child' => [
                    [
                        'title' => 'Controle de acesso',
                        'href' => route('accesscontrol.index'),
                        'permissions' => [
                            'gestao.controle_acessos',
                        ],
                    ],
                    [
                        'title' => 'Usuários',
                        'href' => route('users.index'),
                        'permissions' => [
                            'gestao.cadastro_usuarios'
                        ],
                    ],
                ]
            ],
        ]);
        return $rotas->filter(function ($rota) use ($userPemissions) {
            if (isset($rota['permissions'])) {
                return self::rotaDisponivel(collect($rota['permissions']), $userPemissions);
            }
            if (isset($rota['child'])) {
                $rota['child'] = collect($rota['child'])->filter(function ($child) use ($userPemissions) {
                    if (isset($child['permissions'])) {
                        return self::rotaDisponivel(collect($child['permissions']), $userPemissions);
                    }
                });
            }
        })->values();
    }

    public static function rotaDisponivel(Collection $routePermissions, Collection $userPermissions): bool
    {
        if (!$routePermissions->count()) return false;

        return $routePermissions->filter(fn ($value, $key) => $userPermissions->contains($value))->count();
    }
}
