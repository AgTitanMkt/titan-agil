<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Para onde o usuário será redirecionado após login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Cria uma nova instância do controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
{
    dd(auth()->user());
    $user = auth()->user();

    if ($user->hasRole('ADMIN')) {
        return '/admin/dashboard';
    }

    if ($user->hasAnyRole(['COPYWRITER', 'EDITOR', 'GESTOR'])) {
        return route('tarefas.listagem');
    }

    return '/dashboard';
}
}
