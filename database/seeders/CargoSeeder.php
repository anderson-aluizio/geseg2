<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cargo::truncate();

        $cargos = [
            [
                'id' => 1,
                'nome' => 'ELETRICISTA JR',
                'area' => 'OPERACIONAL',
                'grupo' => 'ELETRICISTA',
                'subgrupo' => 'ELETRICISTA',
                'setor' => 'OPERACIONAL',
            ],
            [
                'id' => 2,
                'nome' => 'ELETRICISTA PL',
                'area' => 'OPERACIONAL',
                'grupo' => 'ELETRICISTA',
                'subgrupo' => 'ELETRICISTA',
                'setor' => 'OPERACIONAL',
            ],
            [
                'id' => 3,
                'nome' => 'ELETRICISTA SR',
                'area' => 'OPERACIONAL',
                'grupo' => 'ELETRICISTA',
                'subgrupo' => 'ELETRICISTA',
                'setor' => 'OPERACIONAL',
            ],
            [
                'id' => 4,
                'nome' => 'ENCARREGADO JR',
                'area' => 'OPERACIONAL',
                'grupo' => 'ENCARREGADO',
                'subgrupo' => 'ENCARREGADO',
                'setor' => 'OPERACIONAL',
            ],
            [
                'id' => 5,
                'nome' => 'ENCARREGADO PL',
                'area' => 'OPERACIONAL',
                'grupo' => 'ENCARREGADO',
                'subgrupo' => 'ENCARREGADO',
                'setor' => 'OPERACIONAL',
            ],
            [
                'id' => 6,
                'nome' => 'ENCARREGADO SR',
                'area' => 'OPERACIONAL',
                'grupo' => 'ENCARREGADO',
                'subgrupo' => 'ENCARREGADO',
                'setor' => 'OPERACIONAL',
            ],
        ];

        foreach ($cargos as $cargo) {
            Cargo::create($cargo);
        }
    }
}
