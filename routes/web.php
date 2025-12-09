<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ImportCSVController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpyController;
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
        Route::get('time', [AdminController::class, 'time'])->name('admin.time');
        Route::get('faturamento', [AdminController::class, 'faturamento'])->name('admin.faturamento');
        Route::get('creative-history', [AdminController::class, 'creativeHistory'])->name('admin.creative.history');

        
        Route::prefix('spy')->group(function () {
            Route::get('mrm', [SpyController::class, 'mrm'])->name('spy.mrm');
        });

        Route::prefix('import')->group(function(){
            Route::get('index',[ImportCSVController::class,'index'])->name('admin.import.index');
            Route::post('preview',[ImportCSVController::class,'preview'])->name('admin.import.preview');
            Route::post('store',[ImportCSVController::class,'store'])->name('admin.import.store');
        });
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

require __DIR__ . '/auth.php';
