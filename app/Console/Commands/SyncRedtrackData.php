<?php

namespace App\Console\Commands;

use App\Services\RedTRack\RedtrackAPIService;
use Illuminate\Console\Command;

class SyncRedtrackData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:redtrack {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza e persiste os relatórios de performance do RedTrack';

    /**
     * Execute the console command.
     */
    public function handle(RedtrackAPIService $service)
    {
        $dateTo = $this->option('to') ?? now()->format('Y-m-d');
        $dateFrom = $this->option('from') ?? now()->subMonths(6)->format('Y-m-d');

        $this->info("📡 Iniciando sincronização RedTrack de {$dateFrom} até {$dateTo}...");

        try {
            $service->fetchAndPersistReport($dateFrom, $dateTo);
            $this->info('✅ Sincronização concluída com sucesso!');
        } catch (\Throwable $e) {
            $this->error('❌ Erro ao sincronizar: ' . $e->getMessage());
        }
    }
}
