<?php

namespace App\Services\ClickUp;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickUpAPIService
{
    protected string $baseUrl;
    protected string $token;
    protected string $code;
    protected string $secret;
    protected string $clientID;
    protected array $url;
    protected $tokenOauth;

    public function __construct()
    {
        $this->baseUrl = config('services.clickup.base_url');
        $this->code = '33G9Z79HIOKL2E6RS9CM6T7M9NKWDZ37';
        $this->url = config('services.clickup.url');
        $this->clientID = config('services.clickup.client_id');
        $this->secret = config('services.clickup.secret');
        $this->tokenOauth = "106147941_e6da57e5ca26cced883d8e283b7ac7ba6380d57c5c98c1b1063f0189ad765e49";
    }


    protected function getAccessToken(): string
    {
        $response = Http::asJson()->post($this->url['token_url'], [
            'client_id'     => $this->clientID,
            'client_secret' => $this->secret,
            'code'          => $this->code,
        ]);

        if ($response->failed()) {
            Log::error('Erro ao obter access_token do ClickUp', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new Exception('Falha ao obter token do ClickUp. Detalhes: ' . $response->body());
        }

        $data = $response->json();

        if (empty($data['access_token'])) {
            throw new Exception('Resposta inválida da API do ClickUp: access_token ausente.');
        }

        // você pode armazenar esse token se quiser
        $this->tokenOauth = $data['access_token'];

        return $this->tokenOauth;
    }

    protected function headers(): array
    {
        return [
            'Authorization' => $this->tokenOauth,
            'Accept' => 'application/json',
        ];
    }

    public function getTasksFromList(string $listId): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/list/{$listId}/task");

        if ($response->failed()) {
            Log::error("Erro ao buscar tasks da lista {$listId}", [
                'response' => $response->body()
            ]);
            throw new Exception("Erro ao buscar task da lista. " . $response->body());
        }

        return $response->json('tasks');
    }

    public function getTaskDetails(string $taskId): ?array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/task/{$taskId}");

        if ($response->failed()) {
            Log::error("Erro ao buscar detalhes da task {$taskId}", [
                'response' => $response->body()
            ]);
            return null;
        }

        return $response->json();
    }
}
