<?php

namespace Database\Seeders;

use App\Models\CentroCustoAnalitico;
use Illuminate\Database\Seeder;

class CentroCustoAnaliticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CentroCustoAnalitico::truncate();

        $data = [
            [
                "id" => "1",
                "centro_custo_id" => "3",
                "nome" => "DINAMO",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "11",
                "centro_custo_id" => "3020",
                "nome" => "ADMINISTRATIVO",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "1101",
                "centro_custo_id" => "3040",
                "nome" => "SAO LUIS",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "1101001",
                "centro_custo_id" => "3020",
                "nome" => "COORPORATIVO",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "1101001001",
                "centro_custo_id" => "3010",
                "nome" => "DIRETORIA COORPORATIVA",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "1101001002",
                "centro_custo_id" => "3020",
                "nome" => "GESTAO CORPORATIVA",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "1101001003",
                "centro_custo_id" => "3010",
                "nome" => "DINAMO TECNOLOGIA",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "1101001004",
                "centro_custo_id" => "4010",
                "nome" => "INVESTIMENTO DIRETORIA",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "1101001005",
                "centro_custo_id" => "4010",
                "nome" => "INVESTIMENTO CORPORATIVO",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ],
            [
                "id" => "1101001010",
                "centro_custo_id" => "3010",
                "nome" => "ENGEL",
                "created_at" => "2022-05-16 09:41:23",
                "updated_at" => "2023-05-23 03:00:10"
            ]
        ];

        foreach ($data as $row) {
            CentroCustoAnalitico::create($row);
        }
    }
}
