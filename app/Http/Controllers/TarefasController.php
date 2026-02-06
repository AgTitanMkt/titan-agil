<?php

namespace App\Http\Controllers;

use App\Models\Nicho;
use App\Models\User;
use Illuminate\Http\Request;

class TarefasController extends Controller
{
       public function cadastro()
    {
        $copies = User::withRole(2)->get();
        $editors = User::withRole(3)->get();
        $nichos = Nicho::all()->pluck("name")->unique()->toArray();
        return view('admin.cadastro', compact('copies', 'editors', 'nichos'));
    }

    public function listagem()
    {
        
        return view('admin.listagem');
    }
}
