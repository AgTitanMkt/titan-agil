<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    protected function authenticated($request, $user)
    {
        if ($user->hasRole('ADMIN')) {
            return '/admin/dashboard';
        }

        if ($user->hasAnyRole(['COPYWRITER', 'EDITOR', 'GESTOR'])) {
            return route('tarefas.listagem');
        }

        return '/dashboard';
    }


    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->role('ADMIN')) {
            return redirect('/admin/dashboard');
        }

        if ($request->user()->hasAnyRole(['COPYWRITER', 'EDITOR', 'GESTOR'])) {
            return redirect()->route('tarefas.listagem');
        }

        return redirect('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
