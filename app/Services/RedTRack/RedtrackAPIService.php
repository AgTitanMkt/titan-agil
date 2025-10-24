<?php

namespace App\Services\RedTRack;

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
     * Mesmo comportamento do fetch_report() do Python,
     * retorna tudo paginado, já com CTR e CPM calculados.
     */
    public function fetchReport(
        string $dateFrom,
        string $dateTo,
        ?string $group = 'rt_ad',
        ?int $per = 1000,
        ?array $extra = []
    ): array {
        $page = 1;
        $rows = [];

        while (true) {

            $params = array_merge([
                'api_key'   => $this->apiKey,
                'group'     => $group,
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
                'per'       => $per,
                'page'      => $page,
                'total'     => 'false',
            ], $extra);

            $response = Http::timeout(60)->get($this->baseUrl, $params);

            if ($response->failed()) {
                Log::error('Erro RedTrack API', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                throw new Exception('Erro ao consultar RedTrack: ' . $response->body());
            }

            $data  = $response->json();
            $items = is_array($data)
                ? $data
                : ($data['items'] ?? []);

            if (empty($items)) break;

            $rows = array_merge($rows, $items);

            if (count($items) < $per) break;
            $page++;
        }

        /**
         * ---> CALCULA CTR & CPM <---
         */
        foreach ($rows as &$r) {
            $impr   = $r['impressions']  ?? 0;
            $clicks = $r['clicks']        ?? 0;
            $cost   = $r['cost']          ?? 0;

            $r['CTR'] = ($impr > 0)
                ? round(($clicks / $impr) * 100, 2)
                : null;

            $r['CPM'] = ($impr > 0)
                ? round(($cost / $impr) * 1000, 2)
                : null;
        }
        unset($r);

        return $rows;
    }
}
