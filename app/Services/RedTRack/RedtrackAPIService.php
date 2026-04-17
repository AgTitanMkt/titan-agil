<?php

namespace App\Services\RedTrack;

use App\Models\Nicho;
use App\Models\RedtrackReport;
use App\Models\SubTask;
use App\Models\TagUsers;
use App\Models\Task;
use App\Models\UserTask;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RedtrackAPIService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.redtrack.base_url');
        $this->apiKey  = config('services.redtrack.api_key');
    }

    /**
     * busca relatorios de paginas da API RedTrack, com base no script Python validado que retorna os valores corretos.
     *
     * python:
     *   by_ad[ad]["revenue"] += float(row.get("revenue", 0) or 0)  c
     *   by_ad[ad]["cost"]    += float(row.get("cost", 0) or 0)
     *   ad = row.get("rt_ad") or "(sem rt_ad)"                      
     */
    public function fetchReport(
        string $dateFrom,
        string $dateTo,
        ?string $group = 'source,rt_campaign,rt_ad',
        ?int $per = 100,
        ?array $extra = []
    ) {
        $page        = 1;
        $maxRetries  = 3;
        $totalItems  = 0;

        Log::info('RedTrack → Iniciando fetchReport', [
            'from'  => $dateFrom,
            'to'    => $dateTo,
            'group' => $group,
            'per'   => $per,
        ]);

        while (true) {
            $params = array_merge([
                'api_key'   => $this->apiKey,
                'group'     => $group,
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
                'timezone'  => 'America/Sao_Paulo',
                'per'       => $per,
                'page'      => $page,
                'total'     => 'false',
            ], $extra);

            $http = Http::timeout(90);

            if (config('app.env') === 'local') {
                $http = $http->withoutVerifying();
            }

            try {
                $response = $http->get($this->baseUrl . '/report', $params);

                // retry logic
                $retryCount = 0;
                while ($response->failed() && $retryCount < $maxRetries) {
                    $retryCount++;
                    Log::warning("RedTrack → Tentando novamente ({$retryCount}/{$maxRetries})", [
                        'page'   => $page,
                        'status' => $response->status(),
                    ]);
                    sleep(2);
                    $response = $http->get($this->baseUrl . '/report', $params);
                }

                if ($response->failed()) {
                    Log::error('RedTrack → Falha persistente', [
                        'page'   => $page,
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);

                    if ($response->status() === 404) {
                        break;
                    }
                    continue;
                }

                $data  = json_decode($response->body(), true, 512, JSON_BIGINT_AS_STRING);
                $items = $data['items'] ?? $data ?? [];

                // garante que e array indexado (lista de rows)
                if (!empty($items) && !isset($items[0])) {
                    $items = [$items];
                }

                if (!empty($items)) {
                    Log::info('RedTrack → Keys do primeiro item', array_keys($items[0]));
                    Log::info('RedTrack → Valores do primeiro item', $items[0]);
                }

                if (empty($items)) {
                    Log::info("RedTrack → Nenhum item na página {$page}");
                    break;
                }

                foreach ($items as $item) {
                    try {

                        // ad = row.get("rt_ad") or "(sem rt_ad)"
                        // nao joga pro lixo o NATIVE que nao tem rt_ad 
                        $rtAd = (isset($item['rt_ad']) && trim($item['rt_ad']) !== '')
                            ? trim($item['rt_ad'])
                            : '(sem rt_ad)';


                        // revenue += float(row.get("revenue", 0) or 0)  <- campo "revenue", NAO "total_revenue"
                        $revenue = (float) (($item['revenue'] ?? 0) ?: 0);
                        $cost    = (float) (($item['cost']    ?? 0) ?: 0);

                        // descarta apenas se absolutamente vazio 
                        if ($revenue == 0.0 && $cost == 0.0) {
                            continue;
                        }

                        // profit - revenue - cost
                        $profit     = $revenue - $cost;
                        $rtCampaign = trim($item['rt_campaign'] ?? '');
                        $source     = trim($item['source']      ?? '');
                        $itemDate   = $item['date']             ?? $dateFrom;

                        // (ex: MMAD123V2 -> taskCode=MMAD123, variation=2)
                        $adParts         = explode('-', $rtAd);
                        $rawCode         = trim($adParts[0]);
                        $taskCode        = $rawCode;
                        $variationNumber = null;

                        if (preg_match('/^(.*)V(\d+)$/i', $rawCode, $matches)) {
                            $taskCode        = $matches[1];
                            $variationNumber = (int) $matches[2];
                        }

                        // date + name (rt_ad) + source + rt_campaign
                        // garante que o mesmo criativo em campanhas diferentes nao colide
                        RedtrackReport::updateOrCreate(
                            [
                                'date'        => $itemDate,
                                'name'        => $rtAd,
                                'source'      => $source,
                                'rt_campaign' => $rtCampaign,
                            ],
                            [
                                'alias'            => $item['source_alias'] ?? null,
                                'normalized_rt_ad' => strtolower(str_replace(' ', '', $rtAd)),
                                'ad_code'          => $taskCode,
                                'clicks'           => (int) (($item['clicks']      ?? 0) ?: 0),
                                'conversions'      => (int) (($item['conversions'] ?? 0) ?: 0),
                                'cost'             => $cost,
                                'revenue'          => $revenue,
                                'profit'           => $profit,
                                'roi'              => $cost > 0 ? round($profit / $cost, 6) : 0,
                            ]
                        );

                        // cria task/subTask apenas para criativos com codigo no padrao TITAN
                        // e que tenham rt_ad real (nao "(sem rt_ad)")
                        if (
                            $rtAd !== '(sem rt_ad)' &&
                            preg_match('/^[A-Za-z0-9]+-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}$/', $rtAd)
                        ) {
                            $cleanParts = array_values(array_filter(array_map('trim', $adParts)));
                            $lastParts  = array_slice($cleanParts, -2);

                            $agents = TagUsers::whereIn('tag', $lastParts)->get()
                                ->map(fn($agent) => $agent->user);

                            try {
                                $codeAdNumeric = null;
                                if (preg_match('/AD(\d+)/i', $taskCode, $m2)) {
                                    $codeAdNumeric = (int) $m2[1];
                                }

                                $task = Task::updateOrCreate(
                                    ['code' => $taskCode],
                                    [
                                        'created_by'      => 81,
                                        'title'           => 'nova tarefa',
                                        'nicho'           => Nicho::where('sigla', strtoupper(substr($taskCode, 0, 2)))->first()?->id,
                                        'normalized_code' => strtolower(str_replace(' ', '', $taskCode)),
                                        'ad'              => $codeAdNumeric,
                                    ]
                                );

                                $subTask = SubTask::where('task_id', $task->id)
                                    ->where('variation_number', $variationNumber)
                                    ->first();

                                if ($subTask) {
                                    $subTask->update(['status' => SubTask::STATUS['CONCLUDED']]);
                                } else {
                                    $subTask = SubTask::create([
                                        'task_id'          => $task->id,
                                        'description'      => $variationNumber ? "Variação {$variationNumber}" : 'Subtask Inicial',
                                        'status'           => SubTask::STATUS['CONCLUDED'],
                                        'variation'        => $variationNumber ? 1 : 0,
                                        'variation_number' => $variationNumber,
                                        'hook'             => 'H1',
                                    ]);
                                }

                                foreach ($agents as $agent) {
                                    if ($agent) {
                                        UserTask::updateOrCreate([
                                            'user_id'     => $agent->id,
                                            'sub_task_id' => $subTask->id,
                                        ]);
                                    }
                                }
                            } catch (Exception $e) {
                                Log::error('RedTrack → Erro ao salvar Task/SubTask', [
                                    'ad'   => $taskCode,
                                    'erro' => $e->getMessage(),
                                ]);
                            }
                        }

                        $totalItems++;
                    } catch (Exception $innerEx) {
                        Log::warning('RedTrack → Falha ao salvar item', [
                            'page' => $page,
                            'file' => $innerEx->getFile(),
                            'line' => $innerEx->getLine(),
                            'erro' => $innerEx->getMessage(),
                        ]);
                        throw new Exception($innerEx->getMessage());
                    }
                }

                // log de ultimo item processado
                Log::info('RedTrack → Amostra último item da página', [
                    'rt_ad'       => $item['rt_ad']      ?? '(sem rt_ad)',
                    'rt_campaign' => $item['rt_campaign'] ?? 'NÃO EXISTE',
                    'revenue'     => $item['revenue']     ?? 'NÃO EXISTE',
                    'cost'        => $item['cost']        ?? 0,
                ]);

                if (app()->runningInConsole()) {
                    echo "Página {$page} processada\n";
                    flush();
                }

                Log::info("RedTrack → Página {$page} processada", [
                    'itens_processados' => count($items),
                ]);

                if (count($items) < $per) {
                    break;
                }

                $page++;
                unset($items, $data);
                usleep(300000); // 0.3s entre paginas

            } catch (Exception $e) {
                Log::error('RedTrack → Exceção geral', [
                    'page' => $page,
                    'erro' => $e->getMessage(),
                ]);
                break;
            }
        }

        Log::info('RedTrack → Coleta finalizada', [
            'total_itens_processados' => $totalItems,
            'última_página'           => $page,
        ]);

        return response()->json([
            'msg'           => 'Fetch de dados concluído',
            'total_itens'   => $totalItems,
            'ultima_pagina' => $page,
        ], 200);
    }

    public function fetchReportDailyRange(string $dateFrom, string $dateTo): array
    {
        $start      = new \DateTime($dateFrom);
        $end        = new \DateTime($dateTo);
        $totalDays  = 0;
        $totalItems = 0;

        Log::info("RedTrack → Iniciando fetch diário de {$dateFrom} até {$dateTo}");

        while ($start <= $end) {
            $currentDate = $start->format('Y-m-d');
            $totalDays++;

            try {
                Log::info("RedTrack → Buscando dados do dia {$currentDate}");

                $response     = $this->fetchReport($currentDate, $currentDate, 'source,rt_campaign,rt_ad');
                $responseData = $response->getData(true);
                $itemsCount   = $responseData['total_itens'] ?? 0;

                $totalItems += $itemsCount;

                Log::info("RedTrack → Dia {$currentDate} concluído", ['itens' => $itemsCount]);
            } catch (Exception $e) {
                Log::error("RedTrack → Falha ao processar dia {$currentDate}", [
                    'erro' => $e->getMessage(),
                ]);
            }

            $start->modify('+1 day');
            sleep(1);
        }

        Log::info('RedTrack → Coleta diária finalizada', [
            'total_dias'              => $totalDays,
            'total_itens_processados' => $totalItems,
        ]);

        return [
            'msg'         => 'Coleta diária concluída',
            'total_dias'  => $totalDays,
            'total_itens' => $totalItems,
        ];
    }
}
