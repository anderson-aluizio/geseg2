<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\CentroCusto;
use App\Models\CentroCustoAnalitico;
use App\Models\Funcionario;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FuncionarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Funcionario::truncate();

        $centroCustos = collect(CentroCusto::withoutGlobalScopes()->get()->modelKeys());
        $centroCustosAnalitico = collect(CentroCustoAnalitico::all()->modelKeys());
        $cargos = collect(Cargo::all()->modelKeys());
        $states = collect([' ', 'A', 'F', 'D']);

        $faker = Faker::create();
        $data[] = [
            'cpf' => '05547242357',
            'situacao' => ' ',
            'matricula' => '020681',
            'filial' => (string)$faker->numberBetween(100, 104),
            'centro_custo_id' => $centroCustos->random(),
            'centro_custo_analitico_id' => $centroCustosAnalitico->random(),
            'nome' => 'Anderson Aluizio',
            'cargo_id' => $cargos->random(),
            'data_admissao' => (string)$faker->date(),
            'data_demissao' => null,
        ];
        for ($i = 0; $i < 80; $i++) {
            $data[] = [
                'cpf' => (string)str_pad($faker->unique()->numberBetween(123456789, 999999999), 11, "0", STR_PAD_LEFT),
                'situacao' => $states->random(),
                'matricula' => (string)$faker->numberBetween(200000, 210000),
                'filial' => (string)$faker->numberBetween(100, 104),
                'centro_custo_id' => $centroCustos->random(),
                'centro_custo_analitico_id' => $centroCustosAnalitico->random(),
                'nome' => (string)$faker->name(),
                'cargo_id' => $cargos->random(),
                'data_admissao' => (string)$faker->date(),
                'data_demissao' => (string)$faker->date(),
            ];
        }

        $chunks = array_chunk($data, 20);
        foreach ($chunks as $chunk) {
            Funcionario::insert($chunk);
        }
    }
}
