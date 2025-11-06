<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Titan Marketing</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/login-custom.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="login-container">
        <div class="left-panel">
            <div class="glass-header">
                <span class="titan-marketing-text">TITAN MARKETING</span>
            </div>
            
           <div class="proverb-text">
                <p>
                    "SE TE MOSTRARES FRACO NO DIA DA ANGÚSTIA,
                    <span class="proverb-highlight">A TUA FORÇA É PEQUENA.</span>"
                </p>
            </div>
        </div>
        <div class="right-panel">
            <div class="login-form-wrapper">
                <h2 class="welcome-text">Prazer em te ver!</h2>
                <p class="subtitle-text">Digite seu e-mail e senha para entrar</p>

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="input-label">Email</label>
                        <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Seu endereço de email" />
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="input-label">Senha</label>
                        <input id="password" class="input-field" type="password" name="password" required autocomplete="current-password" placeholder="Sua senha" />
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="remember-me-group">
                        <label for="remember_me" class="remember-me-label">
                            <input id="remember_me" type="checkbox" name="remember" class="remember-me-checkbox">
                            <span class="remember-me-text">Lembre-se de mim</span>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="login-button">
                            ENTRAR
                        </button>
                    </div>
                </form>

                <div class="register-link">
                    Não tem uma conta? <a href="{{ route('register') }}" class="register-text">Cadastre-se</a>
                </div>

                @if (Route::has('password.request'))
                    <div class="forgot-password-link">
                        <a href="{{ route('password.request') }}" class="forgot-password-text">Esqueceu sua senha?</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>