<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Registro - Titan Marketing</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/register-custom.css', 'resources/js/app.js'])


</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="login-container">
        <div class="left-panel">
            <header class="glass-header">
                <span class="titan-marketing-text">TITAN MARKETING</span>
            </header>
            
            <div class="proverb-text register-proverb">
                <p>"A MÃO REMISSA EMPOBRECE,</p>
                <p><span class="proverb-highlight">MAS A MÃO DOS DILIGENTES ENRIQUECE."</span></p>
                <p class="proverb-reference"></p>
            </div>
            
        </div>

        <div class="right-panel">
            <div class="login-form-wrapper">
                
                <h1 class="welcome-text">Bem-vindo!</h1>
                <p class="subtitle-text">Use esse formulário para fazer login ou criar uma nova conta.</p>
                
                <form method="POST" action="{{ route('register') }}" class="login-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="input-label">Nome</label>
                        <input id="name" class="input-field" type="text" name="name" value="{{ old('name') }}" placeholder="Seu nome completo" required autofocus autocomplete="name" />
                        @error('name') <span class="input-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="input-label">Email</label>
                        <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" placeholder="Seu endereço de email" required autocomplete="username" />
                        @error('email') <span class="input-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="input-label">Senha</label>
                        <input id="password" class="input-field" type="password" name="password" placeholder="Sua senha" required autocomplete="new-password" />
                        @error('password') <span class="input-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="input-label">Confirmar Senha</label>
                        <input id="password_confirmation" class="input-field" type="password" name="password_confirmation" placeholder="Coloque sua senha novamente" required autocomplete="new-password" />
                        @error('password_confirmation') <span class="input-error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="remember-me-group">
                         <label class="remember-me-label">
                            <input class="remember-me-checkbox" type="checkbox" name="remember">
                            <span class="remember-me-text">Lembre-se de mim</span>
                         </label>
                    </div>

                    <button type="submit" class="login-button">
                        {{ __('ENTRAR') }}
                    </button>
                    
                </form>

                <div class="register-link">
                    Já tem uma conta? <a href="{{ route('login') }}" class="register-text">{{ __('Faça login') }}</a>
                </div>
                
                <p class="register-footer">
                    &copy; 2025. Feito com ❤️ por Titan Marketing
                </p>
                
            </div>
        </div>
    </div>










</body>
</html>