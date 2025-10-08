<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'TITAN') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900" rel="stylesheet" />

    @vite(['public/css/app.css', 'public/css/welcome-custom.css']) 

    
    
</head>
<body>
    <div class="welcome-container">
        
        {{-- ALTERAÇÃO AQUI: Envolver o 'N' em um span --}}
        <h1 class="titan-logo">TITA<span class="fading-n">N</span></h1> 

        <div class="button-container">
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="button login-button">
                    LOGIN IN
                </a>
            @endif

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="button register-button">
                    REGISTER
                </a>
            @endif
        </div>

        <div class="proverb-container">
            <p class="proverb-text">
                "NÃO AMES O SONO, PARA QUE NÃO EMPOBREÇAS;<br>
                <span class="proverb-highlight">ABRE OS TEUS OLHOS, E TE FARTARÁS DE PÃO."</span>
            </p>
        </div>

        <div class="corner-logo-container">
             <img src="" alt="Logo Gráfico" class="corner-logo">
        </div>
        
    </div>



</body>
</html>