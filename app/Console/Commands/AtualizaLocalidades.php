<?php

namespace App\Console\Commands;

use App\Domain\Apis\CentroCustoApi;
use App\Domain\Apis\LocalidadeCidadeApi;
use App\Domain\Apis\LocalidadeEstadoApi;
use Illuminate\Console\Command;

class AtualizaLocalidades extends Command
{
    protected $signature = 'geseg:atualiza-localidades';
    protected $description = 'Atualiza Localidades com Jupiter';

    public function handle()
    {
        (new LocalidadeEstadoApi())->handle();
        (new LocalidadeCidadeApi())->handle();
    }
}
