<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get("teste",function(){return view("admin.dashboard");});
Route::get("copy",function(){return view("admin.copy");});
Route::get("faturamento",function(){return view("admin.faturamento");});
Route::get("time",function(){return view("admin.time");});
Route::get("perfil",function(){return view("admin.perfil");});

require __DIR__.'/auth.php';
