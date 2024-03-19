<?php

namespace App\Console\Commands;

use App\Domain\Apis\FuncionarioApi;
use Illuminate\Console\Command;

class AtualizaFuncionarios extends Command
{
    protected $signature = 'geseg:atualiza-funcionarios';
    protected $description = 'Sincroniza os funcionarios com o Genesis';

    public function handle()
    {
        (new FuncionarioApi())->handle();
    }
}
