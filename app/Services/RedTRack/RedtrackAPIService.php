<?php

namespace App\Services\RedTrack;

use App\Models\RedtrackReport;
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
     * Busca relatórios paginados da API RedTrack,
     * mantém dados parciais mesmo em caso de erro.
     */
    public function fetchReport(
        string $dateFrom,
        string $dateTo,
        ?string $group = 'source,rt_ad',
        ?int $per = 1000,
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
                        RedtrackReport::updateOrCreate(
                            [
                                'name'   => $item['rt_ad'],
                                'source' => $item['source'],
                                'alias' => $item['source_alias'],
                                'date' => $dateFrom
                            ],
                            [
                                'normalized_rt_ad' => strtolower(str_replace(' ', '', $item['rt_ad'])),
                                'clicks'       => $item['clicks'] ?? 0,
                                'conversions'  => $item['conversions'] ?? 0,
                                'cost'         => $item['cost'] ?? 0,
                                'profit'       => $item['profit'] ?? 0,
                                'roi'          => $item['roi'] ?? 0,
                                'date'         => $dateFrom
                            ]
                        );


                        if (preg_match('/^[A-Za-z0-9]+-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}$/', $item['rt_ad'])) {
                            // Criativo válido
                        }

                        $totalItems++;
                    } catch (Exception $innerEx) {
                        Log::warning('RedTrack → Falha ao salvar item', [
                            'page' => $page,
                            'item' => $item['rt_campaign'],
                            'erro' => $innerEx->getMessage(),
                        ]);
                    }
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
