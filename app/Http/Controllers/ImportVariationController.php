<?php

namespace App\Http\Controllers;

use App\Models\Nicho;
use App\Models\SubTask;
use App\Models\TagUsers;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportVariationController extends Controller
{
    public function index()
    {
        return view('admin.import.variations');
    }

    public function preview(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $copywriters = User::whereHas('roles', fn($q) => $q->where('roles.id', 2))->orderBy('name')->get();
        $editors = User::whereHas('roles', fn($q) => $q->where('roles.id', 3))->orderBy('name')->get();

        $path = $request->file('file')->getPathname();
        $tags = TagUsers::with('user')->get()->keyBy('tag');

        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        $headers = array_map('trim', $headers);

        $preview = [];

        while (($row = fgetcsv($handle)) !== false) {

            if (count($headers) !== count($row)) continue;

            $line = array_combine($headers, $row);
            $idCriativo = trim($line['ID DO CRIATIVO'] ?? '');

            // filtro que aceita APENAS AS VARIACOES, V2,V3, V4 E ETC...
            if (!preg_match('/V\d+/', $idCriativo)) {
                continue;
            }

            // EXTRACAO DAS TAGS
            $copyTag = isset($line['COPY RESPONSÁVEL'])
                ? explode(" ", trim($line['COPY RESPONSÁVEL']))[0]
                : null;

            $editorTag = isset($line['EDITOR'])
                ? explode(" ", trim($line['EDITOR']))[0]
                : null;

            
            // COPY
            
            $copy = $copyTag && isset($tags[$copyTag])
                ? $tags[$copyTag]->user
                : null;

            if (!$copy && !empty($line['COPY RESPONSÁVEL'])) {
                $copy = User::firstOrCreate(
                    ['name' => trim($line['COPY RESPONSÁVEL'])],
                    [
                        'email' => strtolower($copyTag) . '@agencia-titan.com',
                        'password' => bcrypt('12345678'),
                    ]
                );

                if ($copy->wasRecentlyCreated) {
                    $copy->roles()->attach(2);
                }

                $tags[$copyTag] = (object)['user' => $copy];
            }

            
            // EDITOR
            
            $editor = $editorTag && isset($tags[$editorTag])
                ? $tags[$editorTag]->user
                : null;

            if (!$editor && !empty($line['EDITOR'])) {
                $editor = User::firstOrCreate(
                    ['name' => trim($line['EDITOR'])],
                    [
                        'email' => strtolower($editorTag) . '@agencia-titan.com',
                        'password' => bcrypt('12345678'),
                    ]
                );

                if ($editor->wasRecentlyCreated) {
                    $editor->roles()->attach(3);
                }

                $tags[$editorTag] = (object)['user' => $editor];
            }

            
            // PREVIEW
            
            if (!empty($idCriativo)) {
                $preview[] = [
                    'code'        => $idCriativo,
                    'copy_name'   => $line['COPY RESPONSÁVEL'] ?? null,
                    'editor_name' => $line['EDITOR'] ?? null,
                    'copy_id'     => $copy->id ?? null,
                    'editor_id'   => $editor->id ?? null,
                ];
            }
        }

        fclose($handle);

        return view('admin.import.preview_variations', compact('preview', 'copywriters', 'editors'));
    }

    public function store(Request $request)
    {
        $items = json_decode($request->payload, true) ?? [];

        if (empty($items)) {
            return redirect()->route('admin.import.variations')
                ->with('error', 'Nenhum dado.');
        }

        $now = now();
        $due = Carbon::now()->addDays(3);
        $nichos = Nicho::pluck('id', 'sigla');

        $tasks = [];

        foreach ($items as $item) {

            if (empty($item['code'])) continue;

            $code = trim($item['code']);
            $sigla = strtoupper(substr($code, 0, 2));
            $nicho_id = $nichos[$sigla] ?? 18;

            $tasks[] = [
                'code' => $code,
                'title' => 'Variação',
                'normalized_code' => strtolower(str_replace(' ', '', $code)),
                'created_by' => Auth::id(),
                'nicho' => $nicho_id,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        Task::upsert($tasks, ['code'], ['title', 'normalized_code', 'nicho', 'updated_at']);

        $codes = collect($tasks)->pluck('code');
        $taskMap = Task::whereIn('code', $codes)->pluck('id', 'code');

        $subtasks = [];

        foreach ($items as $item) {

            $taskId = $taskMap[$item['code']] ?? null;
            if (!$taskId) continue;

            $subtasks[] = [
                'task_id' => $taskId,
                'hook' => 'V1',
                'description' => 'Variação Importada',
                'status' => 'pendente',
                'due_date' => $due,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        SubTask::upsert($subtasks, ['task_id', 'hook'], ['description', 'status', 'due_date', 'updated_at']);

        $subMap = SubTask::whereIn('task_id', $taskMap->values())
            ->where('hook', 'V1')
            ->pluck('id', 'task_id');

        $userTasks = [];

        foreach ($items as $item) {

            $taskId = $taskMap[$item['code']] ?? null;
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

        UserTask::upsert($userTasks, ['user_id', 'sub_task_id']);

        return redirect()->route('admin.import.variations')
            ->with('success', 'Variações importadas com sucesso!');
    }
}