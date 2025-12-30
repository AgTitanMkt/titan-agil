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
use Illuminate\Support\Facades\Auth;
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
     * Busca relatórios paginados da API RedTrack,
     * mantém dados parciais mesmo em caso de erro.
     */
    public function fetchReport(
        string $dateFrom,
        string $dateTo,
        ?string $group = 'source,rt_ad',
        ?int $per = 100,
        ?array $extra = []
    ) {
        $page = 1;
        $maxRetries = 3;
        $totalItems = 0;

        Log::info('RedTrack → Iniciando fetchReport', [
            'from' => $dateFrom,
            'to'   => $dateTo,
            'group' => $group,
            'per' => $per,
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

            try {
                $response = Http::timeout(90)->get($this->baseUrl . '/report', $params);

                // Se falhar, tenta algumas vezes antes de desistir
                $retryCount = 0;
                while ($response->failed() && $retryCount < $maxRetries) {
                    $retryCount++;
                    Log::warning("RedTrack → Tentando novamente ({$retryCount}/{$maxRetries})", [
                        'page' => $page,
                        'status' => $response->status(),
                    ]);
                    sleep(2);
                    $response = Http::timeout(90)->get($this->baseUrl . '/report', $params);
                }

                // Se ainda falhou após retries
                if ($response->failed()) {
                    Log::error('RedTrack → Falha persistente', [
                        'page'   => $page,
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);

                    // Se for 404, encerra o loop; caso contrário, segue pro próximo
                    if ($response->status() === 404) {
                        break;
                    }
                    continue;
                }

                // Decodifica de forma mais leve que ->json()
                $data = json_decode($response->body(), true, 512, JSON_BIGINT_AS_STRING);
                $items = $data['items'] ?? $data ?? [];
                if (empty($items)) {
                    Log::info("RedTrack → Nenhum item encontrado na página {$page}");
                    break;
                }
                foreach ($items as $item) {
                    try {

                        $ad = explode('-', $item['rt_ad']);

                        RedtrackReport::updateOrCreate(
                            [
                                'name'   => $item['rt_ad'],
                                'source' => $item['source'],
                                'alias' => $item['source_alias'],
                                'date' => $dateFrom
                            ],
                            [
                                'normalized_rt_ad' => strtolower(str_replace(' ', '', $item['rt_ad'])),
                                'ad_code' => $ad[0],
                                'clicks'       => $item['clicks'] ?? 0,
                                'conversions'  => $item['conversions'] ?? 0,
                                'cost'         => $item['cost'] ?? 0,
                                'profit'       => $item['profit'] ?? 0,
                                'roi'          => $item['roi'] ?? 0,
                                'date'         => $dateFrom
                            ]
                        );


                        if (preg_match('/^[A-Za-z0-9]+-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}$/', $item['rt_ad'])) {

                            // limpa: remove espaços e valores vazios
                            $ad = array_map('trim', $ad);
                            $ad = array_filter($ad);
                            $ad = array_values($ad); // reorganiza índices

                            // pega as 2 últimas posições (ajuste o número se quiser)
                            $lastParts = array_slice($ad, -2);

                            // busca os usuários
                            $agents = TagUsers::whereIn('tag', $lastParts)->get()
                                ->map(function ($agent) {
                                    return $agent->user;
                                });

                            try {
                                // Criando a task e subtask
                                $task = Task::updateOrCreate(
                                    ['code' => $ad[0]],
                                    [
                                        'created_by' => 81,
                                        'title' => 'nova tarefa',
                                        'nicho' => Nicho::where('sigla', strtoupper(substr($ad[0], 0, 2)))->first()->id,
                                        'normalized_code' => strtolower(str_replace(' ', '', $ad[0]))
                                    ]
                                );
                                $SubTask = SubTask::updateOrCreate(
                                    ['task_id' => $task->id],
                                    [
                                        'description' => 'Subtask Inicial',
                                        'status' => 'Pendente',
                                        'hook' => 'H1',
                                        'due_date' => Carbon::now()
                                    ]
                                );

                                foreach ($agents as $agent) {
                                    UserTask::updateOrCreate(
                                        [
                                            'user_id' => $agent->id,
                                            'sub_task_id' => $SubTask->id
                                        ],

                                    );
                                }
                            } catch (Exception $e) {
                                Log::error("Erro ao salvar task Criativo. " . $e->getMessage(), $ad);
                            }
                        }

                        $totalItems++;
                    } catch (Exception $innerEx) {
                        Log::warning('RedTrack → Falha ao salvar item', [
                            'page' => $page,
                            // 'item' => json_encode($item),
                            'file' => $innerEx->getFile(),
                            'line' => $innerEx->getLine(),
                            'erro' => $innerEx->getMessage(),
                        ]);
                        throw new Exception($innerEx->getMessage());
                    }

                    return $items;
                }

                Log::info("RedTrack → Página {$page} processada", [
                    'itens_processados' => count($items),
                ]);

                // Se retornou menos que o limite, acabou
                if (count($items) < $per) {
                    break;
                }

                $page++;
                unset($items, $data); // libera memória
                usleep(300000); // 0.3s entre chamadas

            } catch (Exception $e) {
                Log::error('RedTrack → Exceção geral', [
                    'page'  => $page,
                    'erro'  => $e->getMessage(),
                ]);
                break; // mantém dados processados e encerra
            }
        }

        Log::info('RedTrack → Coleta finalizada', [
            'total_itens_processados' => $totalItems,
            'última_página' => $page,
        ]);

        return response()->json([
            'msg' => 'Fetch de dados concluído',
            'total_itens' => $totalItems,
            'ultima_pagina' => $page
        ], 200);
    }

    public function fetchReportDailyRange(string $dateFrom, string $dateTo): array
    {
        $start = new \DateTime($dateFrom);
        $end   = new \DateTime($dateTo);
        $totalDays = 0;
        $totalItems = 0;

        Log::info("RedTrack → Iniciando fetch diário de {$dateFrom} até {$dateTo}");

        while ($start <= $end) {
            $currentDate = $start->format('Y-m-d');
            $totalDays++;

            try {
                Log::info("RedTrack → Buscando dados do dia {$currentDate}");

                // Chama o método já existente, mas passando o mesmo dia como range
                $response = $this->fetchReport($currentDate, $currentDate, 'source,rt_ad');

                // Se o método fetchReport retornar um Response JSON Laravel:
                $responseData = $response->getData(true);
                $itemsCount = $responseData['total_itens'] ?? 0;

                $totalItems += $itemsCount;
            } catch (Exception $e) {
                Log::error("RedTrack → Falha ao processar dia {$currentDate}: {$e->getMessage()}");
            }

            $start->modify('+1 day');
            sleep(1); // evita sobrecarga de requisições
        }

        Log::info('RedTrack → Coleta diária finalizada', [
            'total_dias' => $totalDays,
            'total_itens_processados' => $totalItems,
        ]);

        return [
            'msg' => 'Coleta diária concluída',
            'total_dias' => $totalDays,
            'total_itens' => $totalItems,
        ];
    }
}
