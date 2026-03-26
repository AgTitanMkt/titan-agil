<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColaboradoresController;
use App\Http\Controllers\ImportCSVController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RhController;
use App\Http\Controllers\TarefasController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ImportVariationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        // ADMIN, HEAD, MANAGER -> Dashboard
        if ($user->hasAnyRole(['ADMIN', 'HEAD', 'MANAGER'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Restante (COPY, EDITOR, DEVELOPER, ANALYST, ASSISTANT) -> Metas
        return redirect()->route('colaboradores.metas');
    }
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')
        ->middleware('role:ADMIN,HEAD,MANAGER')
        ->group(function () {
            Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

            Route::get('gestores', [AdminController::class, 'gestores'])->name('admin.gestores');
            Route::get('time', [AdminController::class, 'time'])->name('admin.time');
            Route::get('faturamento', [AdminController::class, 'faturamento'])->name('admin.faturamento');
            Route::get('creative-history', [AdminController::class, 'creativeHistory'])->name('admin.creative.history');

            // ROTAS DE USUÁRIOS (ADMIN e MANAGER)
            Route::prefix('users')->middleware('role:ADMIN,MANAGER')->group(function () {
                Route::get('create', [AdminController::class, 'createUser'])->name('admin.users.create');
                Route::post('store', [AdminController::class, 'storeUser'])->name('admin.users.store');
            });
        });

    // ROTAS DE AGENTS E CRIATIVOS (acessível para ADMIN, HEAD, MANAGER, COPYWRITER, EDITOR)
    Route::prefix('admin')
        ->middleware('role:ADMIN,HEAD,MANAGER,COPYWRITER,EDITOR')
        ->group(function () {
            Route::get('agents/{type}/{collaborator?}', [AdminController::class, 'agents'])
            ->whereIn('type', ['editors', 'copywriters'])
            ->whereIn('collaborator', ['IN', 'EX'])
            ->name('admin.agents');

            Route::get('creatives/{collaborator?}', [AdminController::class, 'creatives'])
            ->whereIn('collaborator', ['IN', 'EX'])
            ->name('admin.creatives');

            Route::get('editors/synergy', function (Request $request) {
                return app(AdminController::class)->synergyData($request, 'editors');
            })->name('admin.editors.synergy');

            Route::get('copywriters/synergy', function (Request $request) {
                return app(AdminController::class)->synergyData($request, 'copywriters');
            })->name('admin.copies.synergy');

            Route::post('/creative/assign', [AdminController::class, 'assignCreative'])
            ->name('creative.assign');
        });
    Route::prefix('rh')
        ->middleware('role:ADMIN')
        ->group(function () {
            Route::get('colaboradores', [RhController::class, 'colaboradores'])->name('rh.colaboradores');
            Route::get('status', [RhController::class, 'status'])->name('rh.status');
            Route::get('calendario', [RhController::class, 'calendario'])->name('rh.calendario');
            Route::get('equipe', [RhController::class, 'equipe'])->name('rh.equipe');
            Route::get('financeiro', [RhController::class, 'financeiro'])->name('rh.financeiro');
            Route::get('operacoes', [RhController::class, 'operacoes'])->name('rh.operacoes');
            Route::get('carreira', [RhController::class, 'carreira'])->name('rh.carreira');
            Route::get('comportamental', [RhController::class, 'comportamental'])->name('rh.comportamental');
            Route::get('documentos', [RhController::class, 'documentos'])->name('rh.documentos');
            Route::get('cadastro', [RhController::class, 'cadastro'])->name('rh.cadastro');
            Route::get('performance', [RhController::class, 'performance'])->name('rh.performance');
            Route::get('pesquisa', [RhController::class, 'pesquisa'])->name('rh.pesquisa');

            Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
            Route::post('register', [RegisteredUserController::class, 'store']);
        });

    Route::prefix('colaboradores')->group(function () {
        Route::get('metas', [ColaboradoresController::class, 'metas'])->name('colaboradores.metas');
    });

    // ROTAS DE TAREFAS (visível para TODOS)
    Route::prefix('tarefas')
        ->middleware(['auth'])
        ->group(function () {

            Route::get('cadastro', [TarefasController::class, 'cadastro'])
                ->middleware('role:ADMIN,HEAD,MANAGER,GESTOR')
                ->name('tarefas.cadastro');

            Route::get('listagem', [TarefasController::class, 'listagem'])
                ->middleware('role:COPYWRITER,EDITOR,ADMIN,GESTOR,HEAD,MANAGER,DEVELOPER,ANALYST,ASSISTANT')
                ->name('tarefas.listagem');

            Route::get('code-task/{nichoID}', [TarefasController::class, 'nameTask'])
                ->name('tarefas.code');

            Route::get('next-variation-number/{taskId}', [TarefasController::class, 'getNexVariationNumber'])
                ->name('tarefas.nextVariationNumber');

            Route::post('cadastrar', [TarefasController::class, 'store'])
                ->name('tarefas.store');
        });

    // ROTAS DE IMPORTAR CSV (acessível para ADMIN, HEAD, MANAGER)
    Route::prefix('admin/import')->middleware('role:ADMIN,HEAD,MANAGER')->group(function () {
        Route::get('index', [ImportCSVController::class, 'index'])->name('admin.import.index');
        Route::post('preview', [ImportCSVController::class, 'preview'])->name('admin.import.preview');
        Route::post('store', [ImportCSVController::class, 'store'])->name('admin.import.store');
    });

    // NOVA ROTA/TELA: IMPORTAR VARIAÇÕES 
    Route::prefix('admin/import-variations')->middleware('role:ADMIN,HEAD,MANAGER')->group(function () {
    Route::get('index', [ImportVariationController::class, 'index'])->name('admin.import.variations');
    Route::post('preview', [ImportVariationController::class, 'preview'])->name('admin.import.variations.preview');
    Route::post('store', [ImportVariationController::class, 'store'])->name('admin.import.variations.store');
});

    Route::prefix('ajax')->group(function () {
        Route::get('criativos', [TarefasController::class, 'getCriativos'])->name('ajax.criativos');
        Route::get('gestores-by-trafego/{trafego_id}', [TarefasController::class, 'getGestoresByTrafego'])->name('ajax.gestores.by.trafego');
        Route::post('add-copywriter', [TarefasController::class, 'addCopywriter'])->name('ajax.add.copywriter');
        Route::post('add-editor', [TarefasController::class, 'addEditor'])->name('ajax.add.editor');
        Route::post('confirm-copy-delivery', [TarefasController::class, 'confirmCopyDelivery'])
            ->name('ajax.confirm.copy.delivery');
        Route::post('/ajax/review-copy-delivery', [TarefasController::class, 'reviewCopyDelivery'])
            ->name('ajax.review.copy.delivery');
        Route::post('/ajax/review-editor-delivery', [TarefasController::class, 'reviewEditorDelivery'])
            ->name('ajax.review.editor.delivery');
        Route::post('confirm-editor-delivery', [TarefasController::class, 'confirmEditorDelivery'])
            ->name('ajax.confirm.editor.delivery');
    });
});




// Rotas de teste/demonstração - apenas para ADMIN
Route::middleware(['auth', 'role:ADMIN,HEAD'])->group(function () {
    Route::get("teste", function () {
        return view("admin.dashboard");
    });
    Route::get("copy", function () {
        return view("admin.copy");
    });
    Route::get("faturamento", function () {
        return view("admin.faturamento");
    });
    Route::get("time", function () {
        return view("admin.time");
    });
    Route::get("perfil", function () {
        return view("admin.perfil");
    });
    Route::get("gestores", function () {
        return view("admin.gestores");
    });
});


require __DIR__ . '/auth.php';
