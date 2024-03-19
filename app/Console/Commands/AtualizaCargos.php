<?php

namespace App\Console\Commands;

use App\Domain\Apis\CargoApi;
use Illuminate\Console\Command;

class AtualizaCargos extends Command
{
    protected $signature = 'geseg:atualiza-cargos';
    protected $description = 'Atualiza Cargos com o Genesis';

    public function handle()
    {
        (new CargoApi())->handle();
    }
}
