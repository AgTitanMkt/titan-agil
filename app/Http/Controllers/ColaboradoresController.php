<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ColaboradoresController extends Controller
{
    public function metas(){
        return view('colaboradores.metas'); // metas da Copa Profit
    }

}