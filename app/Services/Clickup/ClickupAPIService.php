<?php

namespace App\Services\ClickUp;

use App\Models\Role;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickUpAPIService
{
    protected string $baseUrl;
    protected string $tokenOauth;

    // ID fixo do workspace (Agência Titan)
    protected string $teamId = '9013195959';

    public function __construct()
    {
        $this->baseUrl = config('services.clickup.base_url', 'https://api.clickup.com/api/v2');
        $this->tokenOauth = config('services.clickup.api_key');
    }

    protected function headers(): array
    {
        return [
            'Authorization' => $this->tokenOauth,
            'Accept' => 'application/json',
        ];
    }

    /**
     * 🔹 Retorna todos os espaços (Spaces) do workspace
     */
    public function getSpaces(): array
    {
        $spacesAvailables = [
            "Memória",
            "Emagrecimento",
            "E.D.",
            "Diabetes",
            "Prostata",
            "Visao",
            "Neuropatia",
            "Ofertas"
        ];
        $url = "{$this->baseUrl}/team/{$this->teamId}/space";
        $response = Http::withHeaders($this->headers())->get($url);

        if ($response->failed()) {
            throw new Exception('Erro ao buscar espaços: ' . $response->body());
        }

        $spaces = $response->json('spaces') ?? [];
        // 🔹 Filtra apenas os espaços permitidos (por nome exato)
        $filtered = array_filter($spaces, function ($space) use ($spacesAvailables) {
            return in_array($space['name'], $spacesAvailables);
        });

        // 🔹 Reindexa o array para evitar chaves quebradas (0,1,2,...)
        return array_values($filtered);
    }

    public function getFolderFromSpace(string $spaceId): array
    {
        $url = "{$this->baseUrl}/space/{$spaceId}/folder";
        $folderAvailables = [
            "Produção de Ads",
            "Tráfego",
            "Produção",
            "Teste de Ofertas"
        ];
        $response = Http::withHeaders($this->headers())->get($url);
        if ($response->failed()) {
            Log::error("Erro ao buscar pastas do espaço {$spaceId}", ['response' => $response->body()]);
            return [];
        }

        $folders = $response->json('folders') ?? [];
        // 🔹 Filtra apenas os espaços permitidos (por nome exato)
        $filtered = array_filter($folders, function ($folder) use ($folderAvailables) {
            return in_array($folder['name'], $folderAvailables);
        });

        // 🔹 Reindexa o array para evitar chaves quebradas (0,1,2,...)
        return array_values($filtered);
    }

    /**
     * 🔹 Retorna todas as listas (Lists) de um Space
     */
    public function getListsFromFolder(string $folderId): array
    {
        $url = "{$this->baseUrl}/folder/{$folderId}/list";
        $response = Http::withHeaders($this->headers())->get($url);
        if ($response->failed()) {
            Log::error("Erro ao buscar listas da pasta {$folderId}", ['response' => $response->body()]);
            return [];
        }

        return $response->json('lists') ?? [];
    }

    /**
     * Busca todas as tasks de uma lista (todas as páginas).
     */
    public function getTasksFromList(string $listId): array
    {
        $url = "{$this->baseUrl}/list/{$listId}/task";
        $page = 0;
        $allTasks = [];

        try {
            do {
                $response = Http::withHeaders($this->headers())->get($url, [
                    'page' => $page,
                    'archived' => false,
                    'include_closed' => true,
                    'subtasks' => true,
                ]);

                if ($response->failed()) {
                    throw new Exception("Erro ao buscar tasks da lista {$listId}: " . $response->body());
                }

                $tasks = $response->json('tasks') ?? [];
                $allTasks = array_merge($allTasks, $tasks);

                $page++;
            } while (count($tasks) === 100); // Continua até acabar as páginas

            return $allTasks;
        } catch (Exception $e) {
            Log::error('ClickUp::getTasksFromList', ['error' => $e->getMessage()]);
            return [];
        }
    }


    /**
     * 🔹 Retorna TODAS as tasks do workspace, percorrendo todos os Spaces e Lists
     */
    public function getAllTasks(?string $spaceFilter = null, ?string $folderFilter = null, ?string $listFilter = null, ?string $taskName = null): array
    {
        $allTasks = [];
        $spaces = $this->getSpaces();

        foreach ($spaces as $space) {
            if ($spaceFilter && $space['name'] !== $spaceFilter) {
                continue; // pula outros spaces
            }
            $spaceId = $space['id'];

            $folders = $this->getFolderFromSpace($spaceId);
            foreach ($folders as $folder) {
                if ($folderFilter && $folder['name'] !== $folderFilter) {
                    continue; // pula outras pastas
                }
                $folderId = $folder['id'];
                $lists = $this->getListsFromFolder($folderId);
                foreach ($lists as $list) {
                    if ($listFilter && $list['name'] !== $listFilter) {
                        continue; // pula outras listas
                    }
                    $listId = $list['id'];
                    $tasks = $this->getTasksFromList($listId);
                    foreach ($tasks as $task) {
                        if ($taskName && $task['name'] !== $taskName) {
                            continue; // pula outras tarefas
                        }
                        try {
                            $allTasks[] = $task;
                            //salvando tasks no banco
                            $this->storeTask($task);
                        } catch (Exception $e) {
                            Log::error("Erro na task: {$task['folder']['name']} | {$task['list']['name']} | {$task['name']}. Erro {$e->getMessage()}. Linha: {$e->getLine()}" . "arquivo: " . $e->getFile());
                        }
                    }
                }
            }
        }

        Log::info('Total de tasks encontradas no ClickUp', ['count' => count($allTasks)]);
        return $allTasks;
    }

    private function storeTask(array $task, ?string $taskName = null): array
    {
        // Junta todos os possíveis responsáveis (assignees + custom fields)
        $assigneds = array_merge(
            $this->extractAssignees($task),
            $this->extractUsersFromCustomField($task, 'Copywriter'),
            $this->extractUsersFromCustomField($task, 'Editor'),
        );

        // Remove duplicados por e-mail
        $assigneds = collect($assigneds)
            ->unique(fn($u) => strtolower($u['email']))
            ->values()
            ->all();

        // Se ninguém foi encontrado, ainda assim persiste a Task/SubTask
        if (empty($assigneds)) {
            $assigneds = [[]]; // forçamos 1 iteração para criar a task sem usuário
        }

        foreach ($assigneds as $executedBy) {
            try {
                $userExecutedBy = null;

                if (!empty($executedBy['email'])) {
                    $userExecutedBy = User::where('email', $executedBy['email'])->first();

                    if (!$userExecutedBy) {
                        $userExecutedBy = User::create([
                            'name'     => $executedBy['name'] ?? 'Sem nome',
                            'email'    => $executedBy['email'],
                            // se seu model já tem cast 'password' => 'hashed', pode passar texto puro
                            'password' => '12345678',
                        ]);
                    }
                }

                // Cria/atualiza a Task local
                $tarefa = Task::updateOrCreate(
                    ['code' => $task['name'] ?? ''],
                    [
                        'title' => data_get($task, 'tags.0.name', 'Tarefa'),
                        'code'  => $task['name'] ?? ('VAZIO' . rand(1111, 9999)),
                    ]
                );

                // Cria/atualiza a SubTask relacionada
                $subTask = SubTask::updateOrCreate(
                    ['task_id' => $tarefa->id],
                    [
                        'description' => 'Obtido pelo Clickup',
                        'status'      => SubTask::STATUS['CREATED'],
                        'due_date'    => Carbon::now(),
                        'hook'        => 'H1',
                    ]
                );

                // Só cria relação usuário↔subtask se houver usuário válido
                if ($userExecutedBy) {
                    UserTask::updateOrCreate(
                        ['user_id' => $userExecutedBy->id, 'sub_task_id' => $subTask->id],
                        []
                    );
                }
            } catch (Exception $e) {
                Log::error(
                    'Erro ao salvar task: ' . $e->getMessage()
                        . ' | Linha: ' . $e->getLine()
                        . ' | Arquivo: ' . $e->getFile()
                );
            }
        }

        return [];
    }


    //helper para buscas agentes
    private function extractUsersFromCustomField(array $task, string $fieldName): array
    {
        $field = collect($task['custom_fields'] ?? [])
            ->firstWhere('name', $fieldName);

        $value = $field['value'] ?? [];

        // Retorna array vazio se não for array/iterável
        if (!is_array($value)) {
            return [];
        }

        // Normaliza para ['name' => ..., 'email' => ...]
        return collect($value)
            ->map(function ($u) {
                return [
                    'name'  => Arr::get($u, 'username'),
                    'email' => Arr::get($u, 'email'),
                ];
            })
            // mantém apenas usuários com e-mail válido
            ->filter(fn($u) => !empty($u['email']))
            ->values()
            ->all();
    }

    private function extractAssignees(array $task): array
    {
        return collect($task['assignees'] ?? [])
            ->map(fn($a) => [
                'name'  => Arr::get($a, 'username'),
                'email' => Arr::get($a, 'email'),
            ])
            ->filter(fn($u) => !empty($u['email']))
            ->values()
            ->all();
    }
}
