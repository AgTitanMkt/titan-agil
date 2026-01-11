<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColaboradoresController;
use App\Http\Controllers\ImportCSVController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RhController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/login')->with('success', 'Você saiu da sua conta.');
})->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role('ADMIN')) {
            return redirect('/admin/dashboard');
        }
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('copywriters', [AdminController::class, 'copywriters'])->name('admin.copywriters');
        Route::get('editores', [AdminController::class, 'editors'])->name('admin.editors');
        Route::get('editors/synergy', [AdminController::class, 'synergyData'])
            ->name('admin.editors.synergy');
        
        // ROTA PARA GESTORES
        Route::get('gestores', [AdminController::class, 'gestores'])->name('admin.gestores');

        Route::get('time', [AdminController::class, 'time'])->name('admin.time');
        Route::get('faturamento', [AdminController::class, 'faturamento'])->name('admin.faturamento');
        Route::get('creative-history', [AdminController::class, 'creativeHistory'])->name('admin.creative.history');
        
        Route::prefix('import')->group(function(){
            Route::get('index',[ImportCSVController::class,'index'])->name('admin.import.index');
            Route::post('preview',[ImportCSVController::class,'preview'])->name('admin.import.preview');
            Route::post('store',[ImportCSVController::class,'store'])->name('admin.import.store');
        });
    });
    Route::prefix('rh')->group(function(){
        Route::get('colaboradores',[RhController::class, 'colaboradores'])->name('rh.colaboradores');
        Route::get('status',[RhController::class, 'status'])->name('rh.status');
        Route::get('calendario',[RhController::class, 'calendario'])->name('rh.calendario'); 
        Route::get('equipe',[RhController::class, 'equipe'])->name('rh.equipe');
        Route::get('financeiro',[RhController::class, 'financeiro'])->name('rh.financeiro');
        Route::get('operacoes',[RhController::class, 'operacoes'])->name('rh.operacoes');
        Route::get('carreira',[RhController::class, 'carreira'])->name('rh.carreira');
        Route::get('comportamental',[RhController::class, 'comportamental'])->name('rh.comportamental');
        Route::get('documentos',[RhController::class, 'documentos'])->name('rh.documentos');
        Route::get('cadastro',[RhController::class, 'cadastro'])->name('rh.cadastro');
        Route::get('performance',[RhController::class, 'performance'])->name('rh.performance');
        Route::get('pesquisa',[RhController::class, 'pesquisa'])->name('rh.pesquisa');
    });

    Route::prefix('colaboradores')->group(function () {
        Route::get('metas',[ColaboradoresController::class, 'metas'])->name('colaboradores.metas');
    });
});




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


require __DIR__ . '/auth.php';