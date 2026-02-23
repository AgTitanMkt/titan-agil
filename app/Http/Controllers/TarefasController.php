<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\DeadlineSubask;
use App\Models\Nicho;
use App\Models\Platform;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\TaskFiles;
use App\Models\User;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefasController extends Controller
{
    public function cadastro()
    {
        $copies = User::withRole(2)->get();
        $editors = User::withRole(3)->get();
        $nichos = Nicho::selectRaw('MIN(id) as id, name')
            ->groupBy('name')
            ->get();
        $platforms = Platform::all();
        return view('tasks.cadastro', compact('copies', 'editors', 'nichos', 'platforms'));
    }

    public function store(StoreTaskRequest $request)
    {
        $variation = $request->validated()['variacao'] === 'true';
        if (preg_match('/AD(\d+)/', $request->validated()['code'], $matches)) {
            $ad = (int) $matches[1];
        }

        if (!$variation) {
            $task = Task::create([
                'created_by' => Auth::id(),
                'title' => $request->validated()['titulo'],
                'nicho' => $request->validated()['nicho'],
                'code' => $request->validated()['code'],
                'ad' => $ad,
                'normalized_code' => strtolower($request->validated()['code']),
            ]);
        }else{
            $task = Task::where('code', $request->validated()['parentSearch'])->first();
        }

        $subtask = SubTask::create([
            'task_id' => $task->id,
            'status' => SubTask::STATUS['PENDING'],
            'description' => $variation ? $request->validated()['titulo'] : 'Subtask inicial',
            'variation' => $request->validated()['variacao'] == 'true',
            'platform_id' => $request->validated()['fonte_trafego'],
            'variation_number' => $variation ? $request->validated()['variation_number'] : null,
            'hook' => 'H1',
        ]);

        //so cria a relação se copywriter ou editor forem selecionados
        if ($request->validated()['copywriter_id']) {
            UserTask::create([
                'user_id' => $request->validated()['copywriter_id'],
                'subtask_id' => $subtask->id,
            ]);
        }

        if ($request->validated()['editor_id']) {
            UserTask::create([
                'user_id' => $request->validated()['editor_id'],
                'subtask_id' => $subtask->id,
            ]);
        }

        if ($request->validated()['link_doc']) {
            TaskFiles::create([
                'task_id' => $task->id,
                'file_type' => TaskFiles::FILE_TYPE['URL'],
                'file_url' => $request->validated()['link_doc'],
                'uploaded_by' => Auth::user()->id,
            ]);
        }

        if ($request->validated()['prazo_copy'] || $request->validated()['prazo_editor']) {
            DeadlineSubask::create([
                'subtask_id' => $subtask->id,
                'deadline_copy' => $request->validated()['prazo_copy'] ?? null,
                'deadline_editor' => $request->validated()['prazo_editor'] ?? null,
            ]);
        }


        return redirect()
            ->route('tarefas.listagem')
            ->with('success', 'Demanda criada com sucesso.');
    }


    public function listagem()
    {
        $copies = User::withRole(2)->get();
        $editors = User::withRole(3)->get();
        $nichos = Nicho::all()->pluck('name')->unique()->toArray();
        return view('tasks.listagem', compact('copies', 'editors', 'nichos'));
    }

    public function nameTask($nichoID)
    {
        $nicho = Nicho::find($nichoID);

        if (!$nicho) {
            return response()->json([
                'error' => 'Nicho não encontrado'
            ], 404);
        }

        $lastAdTask = Task::where('nicho', $nichoID)
            ->orderBy('ad', 'desc')
            ->first();

        $nextAd = $lastAdTask ? ($lastAdTask->ad + 1) : 1;

        $code = $nicho->sigla . 'AD' . str_pad($nextAd, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'code' => $code,
            'next_ad' => $nextAd,
            'nicho' => $nicho->name
        ]);
    }

    public function getCriativos(Request $request)
    {
        $search = $request->code;

        return Task::where('code', 'like', "%{$search}%")
            ->orWhere('title', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'code', 'title', 'nicho']);
    }

    public function getNexVariationNumber($taskId)
    {
        $lastVariation = SubTask::where('task_id', $taskId)
            ->where('variation', true)
            ->orderBy('variation_number', 'desc')
            ->first();

        return response()->json([
            'next_variation_number' => $lastVariation ? ($lastVariation->variation_number + 1) : 1
        ]);
    }
}
