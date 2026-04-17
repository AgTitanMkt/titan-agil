<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncRedtrack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-redtrack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando sync Redtrack...');

        $service = new \App\Services\RedTrack\RedtrackAPIService();
        $start = microtime(true);

        $result = $service->fetchReportDailyRange('2026-04-01', '2026-04-17');

        $time = round(microtime(true) - $start, 2);
        $this->info("✅ Finalizado em {$time}s — {$result['total_itens']} itens em {$result['total_dias']} dias");
    }
}
