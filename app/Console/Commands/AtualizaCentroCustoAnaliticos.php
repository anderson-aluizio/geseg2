<?php

namespace App\Console\Commands;

use App\Domain\Apis\CentroCustoAnaliticoApi;
use Illuminate\Console\Command;

class AtualizaCentroCustoAnaliticos extends Command
{
    protected $signature = 'geseg:atualiza-centro-custo-analiticos';
    protected $description = 'Atualiza Centros de Custo Analítico com o Odin';

    public function handle()
    {
        (new CentroCustoAnaliticoApi())->handle();
    }
}
