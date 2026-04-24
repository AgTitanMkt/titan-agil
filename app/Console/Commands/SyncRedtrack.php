<?php

namespace App\Console\Commands;

use App\Services\RedTrack\RedtrackAPIService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncRedtrack extends Command
{
    protected $signature = 'app:sync-redtrack
                            {--from= : Data inicial (Y-m-d). Padrão: hoje}
                            {--to=   : Data final   (Y-m-d). Padrão: hoje}';

    protected $description = 'Sincroniza dados do RedTrack para o período informado (padrão: hoje)';

    public function handle(): int
    {
        $from = $this->option('from') ?: Carbon::now('America/Sao_Paulo')->format('Y-m-d');
        $to   = $this->option('to')   ?: Carbon::now('America/Sao_Paulo')->format('Y-m-d');

        $this->info("🚀 Iniciando sync RedTrack de {$from} até {$to}...");

        $service = app(RedtrackAPIService::class);
        $start   = microtime(true);

        $result = $service->fetchReportDailyRange($from, $to);

        $elapsed = round(microtime(true) - $start, 2);

        $this->info("✅ Finalizado em {$elapsed}s — {$result['total_itens']} itens em {$result['total_dias']} dias");

        return self::SUCCESS;
    }
}