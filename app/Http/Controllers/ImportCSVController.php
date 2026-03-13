<?php

namespace App\Http\Controllers;

use App\Models\Nicho;
use App\Models\SubTask;
use App\Models\TagUsers;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class ImportCSVController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $copywriters = User::whereHas('roles', function ($q) {
            $q->where('roles.id', 2);
        })->orderBy('users.name')->get();

        $editors = User::whereHas('roles', function ($q) {
            $q->where('roles.id', 3);
        })->orderBy('users.name')->get();

        $file = $request->file('file');
        $path = $file->getPathname();

        if (!file_exists($path)) {
            dd("Erro: arquivo não existe no caminho temporário", $path);
        }

        /* MUDANCA AQUI, VAI CARREGAR TODAS AS TAGS DE UMA VEZ */
        $tags = TagUsers::with('user')->get()->keyBy('tag');

        /* LE O CSV EM STREMING  NAO CARREGA TUDO NA MEMORIA */
        $handle = fopen($path, 'r');

        $headers = fgetcsv($handle);
        $headers = array_map('trim', $headers);

        $preview = [];

        while (($row = fgetcsv($handle)) !== false) {

            if (count($headers) !== count($row)) {
                continue;
            }

            $line = array_combine($headers, $row);

            // EXTRAI A TAG
            $copyTag = isset($line['COPY RESPONSÁVEL'])
                ? explode(" ", trim($line['COPY RESPONSÁVEL']))[0]
                : null;

            $editorTag = isset($line['EDITOR'])
                ? explode(" ", trim($line['EDITOR']))[0]
                : null;

            // BUSCA O COPY PELAS TAGS JA CADADTRADAS NO BANCO
            $copy = $copyTag && isset($tags[$copyTag])
                ? $tags[$copyTag]->user
                : null;

            // SE NAO ACHOU O COPY ELE IRA CRIA-LO
            if (!$copy && !empty($line['COPY RESPONSÁVEL'])) {
                $copy = User::firstOrCreate(
                    ['name' => trim($line['COPY RESPONSÁVEL'])],
                    [
                        'email' => strtolower($copyTag) . '@agencia-titan.com',
                        // O tipo_colaborador o Model User.php define sozinho no booted(), ja coloquei la os prefixos $prefixos = ['GEX', 'BH', 'XMX', 'DAN', 'ROGERIO', 'IMP'];
                        'password' => bcrypt('12345678'), // senha porque o laragon exive quando cria um user na hora
                    ]
                );

                // CARGO DE COPY ID 2 PARA ELE APARECER NO SELECT
                if ($copy->wasRecentlyCreated) {
                    $copy->roles()->attach(2); 
                }
                
                // array de tags para nao repetir a query
                $tags[$copyTag] = (object)['user' => $copy];
            }

            // buscar editor pelas tags ja cadastradas
            $editor = $editorTag && isset($tags[$editorTag])
                ? $tags[$editorTag]->user
                : null;

            // SE NAO ACHOU O EDITOR ELE IRA CRIA-LO
            if (!$editor && !empty($line['EDITOR'])) {
                $editor = User::firstOrCreate(
                    ['name' => trim($line['EDITOR'])],
                    [
                        'email' => strtolower($editorTag) . '@agencia-titan.com',
                        'password' => bcrypt('12345678'),
                    ]
                );

                // CARGO DE EDITOR ID 3 PARA ELE APARECER NO SELECT 
                if ($editor->wasRecentlyCreated) {
                    $editor->roles()->attach(3); 
                }

                $tags[$editorTag] = (object)['user' => $editor];
            }

            if (
                !empty($line['ID CRIATIVO']) &&
                (!empty($line['COPY RESPONSÁVEL']) || !empty($line['EDITOR']))
            ) {
                $preview[] = [
                    'code'        => trim($line['ID CRIATIVO']),
                    'copy_name'   => $line['COPY RESPONSÁVEL'] ?? null,
                    'editor_name' => $line['EDITOR'] ?? null,
                    'copy_id'     => $copy->id ?? null,
                    'editor_id'   => $editor->id ?? null,
                ];
            }
        }

        fclose($handle);

        return view('admin.import.preview', [
            'preview'     => $preview,
            'copywriters' => $copywriters,
            'editors'     => $editors,
        ]);
    }


    public function store(Request $request)
    {
        $items = json_decode($request->payload, true) ?? [];

        $tasks = [];
        $subtasks = [];
        $userTasks = [];

        $now = now();
        $due = Carbon::now()->addDays(3);

        /* carregar nichos uma vez */
        $nichos = Nicho::pluck('id', 'sigla');

        foreach ($items as $item) {

            if (empty($item['code']) || (empty($item['copy_id']) && empty($item['editor_id']))) {
                continue;
            }

            $code = trim($item['code']);
            $sigla = strtoupper(substr($code, 0, 2));

            $nicho_id = $nichos[$sigla] ?? 18;

            $tasks[] = [
                'code' => $code,
                'title' => 'criativo',
                'normalized_code' => strtolower(str_replace(' ', '', $code)),
                'created_by' => FacadesAuth::id(),
                'nicho' => $nicho_id,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        /* UPSERT TASKS */
        Task::upsert(
            $tasks,
            ['code'],
            ['title', 'normalized_code', 'nicho', 'updated_at']
        );

        /* pegar tasks criadas */
        $codes = collect($tasks)->pluck('code');

        $taskMap = Task::whereIn('code', $codes)->pluck('id', 'code');

        foreach ($items as $item) {

            $code = trim($item['code']);
            $taskId = $taskMap[$code] ?? null;

            if (!$taskId) continue;

            $subtasks[] = [
                'task_id' => $taskId,
                'hook' => 'H1',
                'description' => 'Subtask inicial',
                'status' => 'pendente',
                'due_date' => $due,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        SubTask::upsert(
            $subtasks,
            ['task_id', 'hook'],
            ['description', 'status', 'due_date', 'updated_at']
        );

        /* pegar subtasks */
        $subMap = SubTask::whereIn('task_id', $taskMap->values())
            ->where('hook', 'H1')
            ->pluck('id', 'task_id');

        foreach ($items as $item) {

            $code = trim($item['code']);
            $taskId = $taskMap[$code] ?? null;
            $subId = $subMap[$taskId] ?? null;

            if (!$subId) continue;

            if (!empty($item['copy_id'])) {
                $userTasks[] = [
                    'user_id' => $item['copy_id'],
                    'sub_task_id' => $subId,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            if (!empty($item['editor_id'])) {
                $userTasks[] = [
                    'user_id' => $item['editor_id'],
                    'sub_task_id' => $subId,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }

        UserTask::upsert(
            $userTasks,
            ['user_id', 'sub_task_id']
        );

        return redirect()->route('admin.import.index')
            ->with('success', 'Importação concluída com sucesso!');
    }
}
