<?php

namespace App\Console\Commands;

use App\Domain\Apis\CentroCustoApi;
use Illuminate\Console\Command;

class AtualizaCentroCustos extends Command
{
    protected $signature = 'geseg:atualiza-centro-custos';
    protected $description = 'Atualiza Centros de Custo com o Odin';

    public function handle()
    {
        (new CentroCustoApi())->handle();
    }
}
