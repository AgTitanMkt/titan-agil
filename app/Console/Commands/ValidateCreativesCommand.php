<?php

namespace App\Console\Commands;

use App\Models\RedtrackReport;
use App\Models\ValidatedCreative;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValidateCreativesCommand extends Command
{
    protected $signature = 'creatives:validate {--all : Processa todos os criativos (full scan)}';

    protected $description = 'Valida criativos em potencial e criativos validados com base no histórico do RedTrack';

    public function handle(): int
    {
        $isFullScan = $this->option('all');
        $today = Carbon::today();

        $this->info('▶ Iniciando validação de criativos');
        $this->info('Modo: ' . ($isFullScan ? 'FULL (histórico completo)' : 'DAILY (somente criativos ativos hoje)'));

        Log::info('ValidateCreativesCommand ▶ Início', [
            'mode' => $isFullScan ? 'FULL' : 'DAILY',
        ]);

        /** =====================================================
         * 1️⃣ DEFINIR CRIATIVOS QUE SERÃO PROCESSADOS
         * ===================================================== */
        $activeCreativesQuery = RedtrackReport::query()
            ->select('name')
            ->whereNotNull('name')
            ->whereNotIn('name', function ($q) {
                $q->select('ad_code')
                    ->from('validated_creatives')
                    ->where('is_validated', true);
            });

        if (!$isFullScan) {
            $activeCreativesQuery->whereDate('date', $today);
        }

        $activeCreatives = $activeCreativesQuery
            ->distinct()
            ->pluck('name');

        $totalCreatives = $activeCreatives->count();

        if ($totalCreatives === 0) {
            $this->warn('Nenhum criativo para processar.');
            return Command::SUCCESS;
        }

        $this->info("Criativos encontrados: {$totalCreatives}");

        Log::info('ValidateCreativesCommand ▶ Criativos filtrados', [
            'total' => $totalCreatives,
        ]);

        /** =====================================================
         * 2️⃣ BARRA DE PROGRESSO
         * ===================================================== */
        $progressBar = $this->output->createProgressBar($totalCreatives);
        $progressBar->start();

        /** =====================================================
         * 3️⃣ PROCESSAMENTO EM CHUNK
         * ===================================================== */
        $processed = 0;

        $activeCreatives->chunk(500)->each(function ($chunk) use (&$processed, $progressBar) {

            $creatives = RedtrackReport::select(
                'name',
                DB::raw('SUM(conversions) as total_conversions'),
                DB::raw('SUM(cost) as total_cost'),
                DB::raw('SUM(profit) as total_profit'),
                DB::raw('SUM(profit) / NULLIF(SUM(cost), 0) as roi')
            )
                ->whereIn('name', $chunk)
                ->groupBy('name')
                ->get();

            foreach ($creatives as $creative) {
                try {
                    $roi = round($creative->roi, 4);

                    // 🔥 verifica se entra em algum estágio
                    $isPotential = $creative->total_conversions >= 1 && $roi >= 1;
                    $isValidated = $creative->total_conversions >= 20 && $roi >= 1.8;

                    // ❌ se não é potencial nem validado, ignora totalmente
                    if (!$isPotential && !$isValidated) {
                        $progressBar->advance();
                        continue;
                    }

                    $record = ValidatedCreative::firstOrNew([
                        'ad_code' => $creative->name,
                    ]);

                    // métricas consolidadas
                    $record->total_conversions = $creative->total_conversions;
                    $record->total_cost        = $creative->total_cost;
                    $record->total_profit      = $creative->total_profit;
                    $record->roi               = $roi;

                    /** 🟡 POTENCIAL */
                    if ($isPotential && !$record->is_potential) {
                        $record->is_potential = true;
                        $record->potential_at = now();

                        Log::info('Criativo POTENCIAL', [
                            'ad_code' => $creative->name,
                            'roi' => $roi,
                            'conversions' => $creative->total_conversions,
                        ]);
                    }

                    /** 🟢 VALIDADO */
                    if ($isValidated && !$record->is_validated) {
                        $record->is_validated = true;
                        $record->validated_at = now();

                        Log::info('Criativo VALIDADO', [
                            'ad_code' => $creative->name,
                            'roi' => $roi,
                            'conversions' => $creative->total_conversions,
                        ]);
                    }

                    $record->save();
                    $processed++;
                } catch (\Throwable $e) {
                    Log::error('Erro ao processar criativo', [
                        'ad_code' => $creative->name,
                        'error' => $e->getMessage(),
                    ]);
                }

                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✔ Finalizado. Criativos processados: {$processed}");

        Log::info('ValidateCreativesCommand ▶ Finalizado', [
            'processed' => $processed,
        ]);

        return Command::SUCCESS;
    }
}
