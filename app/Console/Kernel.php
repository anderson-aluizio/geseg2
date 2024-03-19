<?php

namespace App\Console;

use App\Console\Commands\AtualizaCargos;
use App\Console\Commands\AtualizaCentroCustos;
use App\Console\Commands\AtualizaCentroCustoAnaliticos;
use App\Console\Commands\AtualizaFuncionarioFechamentos;
use App\Console\Commands\AtualizaFuncionarios;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(AtualizaCentroCustos::class)->daily('05:00');
        $schedule->command(AtualizaCargos::class)->dailyAt('05:00');
        $schedule->command(AtualizaCentroCustoAnaliticos::class)->daily('05:00');
        $schedule->command(AtualizaFuncionarios::class)->daily('05:00');

        $schedule->command(AtualizaFuncionarioFechamentos::class)->monthlyOn(1);
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
