<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CentroCusto;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CentroCustoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::flush();
        CentroCusto::truncate();

        $centroCustos = [
            ['id' => '3040', 'nome' => 'MA SAO LUIS'],
            ['id' => '3050', 'nome' => 'MA PINHEIRO'],
        ];

        DB::table('centro_custos')->insert($centroCustos);
    }
}
