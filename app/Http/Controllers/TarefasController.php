<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\DeadlineSubask;
use App\Models\Nicho;
use App\Models\Platform;
use App\Models\SubTask;
use App\Models\SubtaskFile;
use App\Models\Task;
use App\Models\TaskFiles;
use App\Models\User;
use App\Models\UserDetail;
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
        $hasCopy = !empty($request->validated()['copywriter_id']);
        $hasEditor = !empty($request->validated()['editor_id']);

        $status = ($hasCopy && $hasEditor)
            ? SubTask::STATUS['PENDING']
            : SubTask::STATUS['CREATED'];

        $variation = $request->validated()['variacao'] === 'true';
        if (preg_match('/AD(\d+)/', $request->validated()['code'], $matches)) {
            $ad = (int) $matches[1];
        }

        $task = null;

        if (!$variation) {
            $task = Task::create([
                'created_by' => Auth::id(),
                'title' => $request->validated()['titulo'],
                'nicho' => $request->validated()['nicho'],
                'code' => $request->validated()['code'],
                'ad' => $ad,
                'normalized_code' => strtolower($request->validated()['code']),
            ]);
        } else {
            $task = Task::where('code', $request->validated()['parentSearch'])->first();
        }

        $subtask = SubTask::create([
            'task_id' => $task->id,
            'status' => $status,
            'description' => $variation
                ? $request->validated()['titulo']
                : 'Subtask inicial',
            'variation' => $request->validated()['variacao'] == 'true',
            'platform_id' => $request->validated()['fonte_trafego'],
            'variation_number' => $variation
                ? $request->validated()['variation_number']
                : null,
            'hook' => 'H1',
            'revised_by' => $request->validated()['gestor_id'] ?? null,
            'created_by' => Auth::user()->id,
        ]);

        //so cria a relação se copywriter ou editor forem selecionados
        if ($request->validated()['copywriter_id']) {
            UserTask::create([
                'user_id' => $request->validated()['copywriter_id'],
                'sub_task_id' => $subtask->id,
            ]);
        }

        if ($request->validated()['editor_id']) {
            UserTask::create([
                'user_id' => $request->validated()['editor_id'],
                'sub_task_id' => $subtask->id,
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

        $userId = Auth::id();

        $subtasks = SubTask::with([
            'task:id,title,nicho,code',
            'files:id,subtask_id,file_type,file_url,uploaded_by,created_at',
            'files.uploader:id,name',
            'agentes:id,name',
            'agentes.tags:id,user_id,tag',
            'assignments.user.roles:id,title',
            'platform:id,name',
            'revisedBy:id,name',
        ])
            ->where('status', '!=', SubTask::STATUS['CONCLUDED'])
            ->where(function ($query) use ($userId) {
                $query->where('created_by', $userId)
                    ->orWhere('revised_by', $userId)
                    ->orWhereHas('assignments', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('tasks.listagem', compact('copies', 'editors', 'nichos', 'subtasks'));
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

    public function getGestoresByTrafego($trafegoId, Request $request)
    {
        $gestores = UserDetail::where('platform_id', $trafegoId)
            ->with('user:id,name')
            ->get()
            ->map(function ($detail) {
                return [
                    'id' => $detail->user_id,
                    'name' => $detail->user->name,
                ];
            });

        return response()->json($gestores);
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

    public function addCopywriter(Request $request)
    {
        $request->validate([
            'sub_task_id' => 'required|exists:sub_tasks,id',
            'user_id' => 'required|exists:users,id',
        ]);

        UserTask::firstOrCreate(
            [
                'user_id' => $request->user_id,
                'sub_task_id' => $request->sub_task_id,
            ],
            [
                'status' => UserTask::STATUS['ASSIGNED'],
            ]
        );

        return response()->json(['message' => 'Copywriter adicionado com sucesso']);
    }

    public function addEditor(Request $request)
    {
        $request->validate([
            'sub_task_id' => 'required|exists:sub_tasks,id',
            'user_id' => 'required|exists:users,id',
        ]);

        UserTask::create([
            'user_id' => $request->user_id,
            'sub_task_id' => $request->sub_task_id,
            'status' => UserTask::STATUS['ASSIGNED'],
        ]);

        return response()->json(['message' => 'Editor adicionado com sucesso']);
    }
    public function confirmCopyDelivery(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'assignment_id' => 'required|exists:user_tasks,id',
            'delivery_link' => 'required|url'
        ], [
            'assignment_id.required' => 'Tarefa inválida.',
            'assignment_id.exists' => 'Atribuição não encontrada.',
            'delivery_link.required' => 'Informe o link da entrega.',
            'delivery_link.url' => 'Informe um link válido (ex: https://...).',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'type' => 'validation',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $assignment = UserTask::with('subTask')->find($request->assignment_id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'type' => 'not_found',
                'message' => 'Atribuição não encontrada.'
            ], 404);
        }

        // 🔒 Segurança
        if ($assignment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'type' => 'permission',
                'message' => 'Você não tem permissão para confirmar essa entrega.'
            ], 403);
        }

        // ❗ já entregue
        if ($assignment->status === UserTask::STATUS['DONE']) {
            return response()->json([
                'success' => false,
                'type' => 'business',
                'message' => 'Essa entrega já foi confirmada.'
            ], 409);
        }

        // ✅ Atualiza assignment
        $assignment->update([
            'status' => UserTask::STATUS['DONE'],
            'completed_at' => now(),
        ]);

        $subTask = $assignment->subTask;

        $subTask->update([
            'status' => SubTask::STATUS['REVIEW_COPY']
        ]);

        // salva ou atualiza link
        SubtaskFile::updateOrCreate(
            [
                'subtask_id' => $subTask->id,
                'file_type' => SubtaskFile::FILE_TYPE['URL'],
            ],
            [
                'file_url' => $request->delivery_link,
                'uploaded_by' => auth()->id(),
            ]
        );

        return response()->json([
            'success' => true,
            'type' => 'success',
            'message' => 'Entrega enviada com sucesso! Aguardando revisão do gestor.'
        ]);
    }
    public function reviewCopyDelivery(Request $request)
    {
        $request->validate([
            'subtask_id' => 'required|exists:sub_tasks,id',
            'decision' => 'required|in:approve,reject',
        ]);

        $subtask = SubTask::with(['assignments.user.roles', 'files'])
            ->findOrFail($request->subtask_id);

        // 🔒 Segurança: apenas gestor responsável (revised_by) ou ADMIN
        $user = auth()->user();

        $isAdmin = $user->roles->contains('title', 'ADMIN');
        if (!$isAdmin && $subtask->revised_by !== $user->id) {
            abort(403);
        }

        // Só permite ação se está aguardando revisão do copy
        if ($subtask->status !== SubTask::STATUS['REVIEW_COPY']) {
            return response()->json([
                'success' => false,
                'message' => 'Essa task não está em revisão de copy.'
            ], 422);
        }

        // Pega assignment do copywriter
        $copyAssignment = $subtask->assignments->first(function ($a) {
            return $a->user && $a->user->roles->contains('title', 'COPYWRITER');
        });

        if (!$copyAssignment) {
            return response()->json([
                'success' => false,
                'message' => 'Copywriter não encontrado na tarefa.'
            ], 422);
        }

        if ($request->decision === 'reject') {

            // volta para ajuste
            $copyAssignment->update([
                'status' => UserTask::STATUS['REJECTED'],
                'completed_at' => null,
            ]);

            $subtask->update([
                'status' => SubTask::STATUS['PENDING_COPY'], // ou CREATED, se fizer sentido no seu fluxo
            ]);

            return response()->json(['success' => true]);
        }

        // ✅ APPROVE
        // Verifica se editor já concluiu
        $editorDone = $subtask->assignments->contains(function ($a) {
            return $a->status === UserTask::STATUS['DONE']
                && $a->user
                && $a->user->roles->contains('title', 'EDITOR');
        });

        $subtask->update([
            'status' => $editorDone
                ? SubTask::STATUS['REVIEW']          // tudo pronto p/ revisão final/gestor
                : SubTask::STATUS['PENDING_EDITOR'],  // aguardando editor (você pode usar PENDING também)
        ]);

        return response()->json(['success' => true]);
    }
}
