<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class ForceChangePasswordController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect('/login');
        }

        if (!$user->must_change_password) {
            return redirect('/');
        }

        return view('auth.force-change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'A senha atual é obrigatória',
            'current_password.current_password' => 'A senha atual está incorreta. Por favor tente novamente.',
            'password.required' => 'A nova senha é obrigatória',
            'password.min' => 'A nova senha deve ter no mínimo 8 caracteres',
            'password.confirmed' => 'As senhas não conferem. Verifique a confirmação.',
            'password.different' => 'A nova senha deve ser diferente da senha atual',
        ]);

        $user = $request->user();

        try {
            // Atualizar a senha e o flag de must_change_password
            $user->update([
                'password' => Hash::make($request->password),
                'must_change_password' => false,
            ]);

            Log::info('Senha alterada com sucesso para usuário: ' . $user->email);

            // Recarregar o usuário na sessão para atualizar must_change_password
            auth()->user()->refresh();

            return redirect('/')->with('success', 'Senha alterada com sucesso! Bem-vindo ao sistema.');
        } catch (\Exception $e) {
            Log::error('Erro ao alterar senha: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Erro ao alterar senha. Por favor, tente novamente.');
        }
    }
}
