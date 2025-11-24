<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
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
            $copy   = User::where('name', 'LIKE', '%' . $line['COPY RESPONSÁVEL'] . '%')->first();
            $editor = User::where('name', 'LIKE', '%' . $line['EDITOR'] . '%')->first();
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
        foreach ($request->items as $item) {
            $task = Task::updateOrCreate(
                [
                    'code' => $item['code'],
                ],
                [
                    'title' => 'criativo',
                    'normalized_code' => strtolower(str_replace(' ', '', $item['code'])),
                    'created_by' => FacadesAuth::user()->id,
                ]
            );

            $sub = SubTask::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'hook' => 1,
                ],
                [
                    'description' => 'Subtask inicial',
                    'status' => 'pendente',
                    'due_date' => Carbon::now()->addDays(3)
                ]
            );

            if ($item['copy_id']) {
                UserTask::updateOrCreate([
                    'user_id' => $item['copy_id'],
                    'sub_task_id' => $sub->id
                ]);
            }
            if ($item['editor_id']) {
                UserTask::updateOrCreate([
                    'user_id' => $item['editor_id'],
                    'sub_task_id' => $sub->id
                ]);
            }
        }

        return redirect()->route('admin.import.index')->with('success', 'Importação concluída com sucesso!');
    }
}
