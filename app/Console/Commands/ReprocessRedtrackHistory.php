<?php

namespace App\Console\Commands;

use App\Services\RedTrack\RedtrackAPIService;
use App\Models\RedtrackReport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReprocessRedtrackHistory extends Command
{
    protected $signature = 'app:reprocess-redtrack-history
                            {--from=2026-01-01 : Data inicial}
                            {--to=             : Data final (padrão: ontem)}
                            {--clean           : Apaga os registros do período antes de reimportar}';

    protected $description = 'Apaga e reimporta todo o histórico do RedTrack de um período';

    public function handle(): int
    {
        $from = $this->option('from');
        $to   = $this->option('to') ?: Carbon::yesterday('America/Sao_Paulo')->format('Y-m-d');

        $this->info("📅 Período: {$from} até {$to}");

        // confirmacao de seguranca
        if ($this->option('clean')) {
            $count = RedtrackReport::whereBetween('date', [$from, $to])->count();

            $this->warn("⚠️  Isso vai APAGAR {$count} registros do banco entre {$from} e {$to}.");

            if (!$this->confirm('Tem certeza que deseja continuar?')) {
                $this->info('Operação cancelada.');
                return self::SUCCESS;
            }

            $this->info('🗑️  Apagando registros antigos...');
            RedtrackReport::whereBetween('date', [$from, $to])->delete();
            $this->info('✅ Registros apagados.');
        }

        // reimportar
        $this->info('🚀 Iniciando reimportação...');

        $service = app(RedtrackAPIService::class);
        $start   = microtime(true);

        $result = $service->fetchReportDailyRange($from, $to);

        $elapsed = round(microtime(true) - $start, 2);

        $this->info("✅ Concluído em {$elapsed}s");
        $this->table(
            ['Dias processados', 'Itens importados'],
            [[$result['total_dias'], $result['total_itens']]]
        );

        return self::SUCCESS;
    }
}