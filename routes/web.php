<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


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
    Route::prefix('admin')->group(function(){
        Route::get('dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
        Route::get('copywriters',[AdminController::class,'copywriters'])->name('admin.copywriters');
    });
});



Route::get("teste",function(){return view("admin.dashboard");});
Route::get("copy",function(){return view("admin.copy");});

require __DIR__.'/auth.php';
