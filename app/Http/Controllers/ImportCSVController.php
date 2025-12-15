<?php

namespace App\Http\Controllers;

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
            $q->where('roles.id', 2);  // COPYWRITER
        })->orderBy('users.name')->get();

        $editors = User::whereHas('roles', function ($q) {
            $q->where('roles.id', 3);  // EDITOR
        })->orderBy('users.name')->get();


        $file = $request->file('file');

        // Pega o caminho temporário nativo do PHP
        $path = $file->getPathname();

        if (!file_exists($path)) {
            dd("Erro: arquivo não existe no caminho temporário", $path);
        }

        $rows = array_map('str_getcsv', file($path, FILE_SKIP_EMPTY_LINES));

        // Extrai cabeçalho
        $headers = array_map('trim', $rows[0]);
        unset($rows[0]);

        $preview = [];

        foreach ($rows as $r) {


            $line = array_combine($headers, $r);
            $copy = TagUsers::where('tag', explode(" ", $line['COPY RESPONSÁVEL'])[0])->first();
            $editor = TagUsers::where('tag', explode(" ", $line['EDITOR'])[0])->first();
            $copy   = $copy ? $copy->user : null;
            $editor   = $editor ? $editor->user : null;
            if (trim($line['ID CRIATIVO']) && ($line['COPY RESPONSÁVEL'] || $line['EDITOR']))
                $preview[] = [
                    'code'        => trim($line['ID CRIATIVO']),
                    'copy_name'   => $line['COPY RESPONSÁVEL'],
                    'editor_name' => $line['EDITOR'],
                    'copy_id'     => $copy->id ?? null,
                    'editor_id'   => $editor->id ?? null,
                ];
        }

        return view('admin.import.preview', [
            'preview'     => $preview,
            'copywriters' => $copywriters,
            'editors'     => $editors,
        ]);
    }


    public function store(Request $request)
    {
        $items = json_decode($request->payload, true);

        foreach ($items as $item) {

            // Ignorar registros inválidos
            if (empty($item['code']) || (empty($item['copy_id']) && empty($item['editor_id']))) {
                continue;
            }

            /**
             * 🔥 1. Extrair sigla (nichos)
             */
            $sigla = strtoupper(substr($item['code'], 0, 2));

            $nicho = \App\Models\Nicho::where('sigla', $sigla)->first();

            $nicho_id = $nicho->id ?? null;


            /**
             * 🔥 2. Criar/atualizar TASK com nicho preenchido
             */
            $task = Task::updateOrCreate(
                ['code' => $item['code']],
                [
                    'title' => 'criativo',
                    'normalized_code' => strtolower(str_replace(' ', '', $item['code'])),
                    'created_by' => FacadesAuth::id(),
                    'nicho' => $nicho_id,
                ]
            );

            /**
             * 🔥 3. Criar/atualizar SubTask com HOOK H1
             */
            $sub = SubTask::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'hook' => 'H1',
                ],
                [
                    'description' => 'Subtask inicial',
                    'status' => 'pendente',
                    'due_date' => Carbon::now()->addDays(3)
                ]
            );

            /**
             * 🔥 4. Copywriter
             */
            if (!empty($item['copy_id'])) {
                UserTask::updateOrCreate([
                    'user_id'     => $item['copy_id'],
                    'sub_task_id' => $sub->id
                ]);
            }

            /**
             * 🔥 5. Editor
             */
            if (!empty($item['editor_id'])) {
                UserTask::updateOrCreate([
                    'user_id'     => $item['editor_id'],
                    'sub_task_id' => $sub->id
                ]);
            }
        }

        return redirect()->route('admin.import.index')->with('success', 'Importação concluída com sucesso!');
    }
}
