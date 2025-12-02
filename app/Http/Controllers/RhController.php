<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RhController extends Controller
{
    public function colaboradores(){
        return view('rh.colaboradores');
    }

    public function pessoas (){
        return view('rh.pessoas');
    }

    public function equipe (){
        return view ('rh.equipe');
    }

    public function financeiro (){
        return view ('rh.financeiro');
    }

    public function operacoes (){
        return view ('rh.operacoes');
    }

    public function carreira (){
        return view ('rh.carreira');
    }

    public function comportamental (){
        return view ('rh.comportamental');
    }

    public function documentos (){
        return view ('rh.documentos');
    }

    public function cadastro (){
        return view ('rh.cadastro');
    }

    public function performance (){
        return view ('rh.performance');
    }

    public function pesquisa (){
        return view ('rh.pesquisa');
    }
}
