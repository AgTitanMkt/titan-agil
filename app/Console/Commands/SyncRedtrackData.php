<?php

namespace App\Console\Commands;

use App\Services\RedTRack\RedtrackAPIService;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\progress;

class SyncRedtrackData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:redtrack {--from=} {--to=}'; //Y-m-d

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza e persiste os relatórios de performance do RedTrack';

    /**
     * Execute the console command.
     */
    public function handle(RedtrackAPIService $service): int
    {
        $dateFrom = $this->option('from') ?? now()->subDays(7)->toDateString();
        $dateTo   = $this->option('to')   ?? now()->toDateString();

        $this->info("🔄 Iniciando sincronização diária do RedTrack");
        $this->line("📅 Período: {$dateFrom} → {$dateTo}");

        try {
            $period = CarbonPeriod::create($dateFrom, $dateTo);
            $totalDays = iterator_count($period);
            $processed = 0;
            $totalItems = 0;

            // Progress bar moderna (Laravel 12)
            $bar = progress(label: 'Baixando relatórios...', steps: $totalDays);

            foreach ($period as $day) {
                $date = $day->format('Y-m-d');

                $bar->advance(); // avança 1 passo na barra
                $this->line("📅 Processando {$date}");

                try {
                    $result = $service->fetchReport($date, $date);
                    $data = $result->getData(true);
                    $items = $data['total_itens'] ?? 0;
                    $totalItems += $items;
                    $processed++;
                } catch (Exception $e) {
                    Log::error("RedTrack → Erro ao processar {$date}: " . $e->getMessage());
                    $this->warn("⚠️  Falha em {$date}: {$e->getMessage()}");
                }

                usleep(300000); // 0.3s entre chamadas
            }


            $bar->finish();
            $this->newLine(2);

            $this->info("✅ Sincronização concluída com sucesso!");
            $this->line("📊 Dias processados: {$processed}/{$totalDays}");
            $this->line("📈 Itens processados: {$totalItems}");
            $this->line("🕓 Início: {$dateFrom} — Fim: {$dateTo}");

            Log::info('RedTrack → Sincronização finalizada', [
                'periodo' => [$dateFrom, $dateTo],
                'dias_processados' => $processed,
                'total_itens' => $totalItems,
            ]);

            return self::SUCCESS;
        } catch (Exception $e) {
            Log::error('RedTrack → Erro geral na sincronização', [
                'erro' => $e->getMessage(),
            ]);

            $this->error("❌ Erro geral: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
