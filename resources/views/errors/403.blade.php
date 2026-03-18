<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-gray-200">

    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="text-center">

            <!-- Código -->
            <div class="text-4xl font-semibold tracking-wide">
                403 <span class="mx-3 text-gray-500">|</span>
                <span class="text-gray-300">ACESSO NEGADO</span>
            </div>

            <!-- Descrição -->
            <p class="mt-6 text-gray-400 max-w-md mx-auto">
                Você não possui permissão para acessar esta página.
                Caso acredite que isso seja um erro, entre em contato com o administrador.
            </p>

            <!-- Botão -->
            <div class="mt-8">
                <a href="{{ url('/') }}"
                   class="inline-block px-6 py-2 text-sm font-medium text-white bg-slate-700 hover:bg-slate-600 rounded-md transition">
                    Voltar para Home
                </a>
            </div>

            <!-- Usuário logado -->
            @auth
            <div class="mt-10 text-sm text-gray-500">
                Logado como
                <span class="text-gray-300 font-medium">
                    {{ auth()->user()->name }}
                </span>

                <div class="mt-2">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-red-400 hover:text-red-300">
                        Sair
                    </a>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
            @endauth

        </div>
    </div>

</body>
</html>