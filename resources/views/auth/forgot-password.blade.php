<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha - TITAN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700" rel="stylesheet" />
    <link rel="stylesheet" href="forgot-custom.css"> 

    @vite(['public/css/app.css', 'public/css/forgot-custom.css'])

</head>
<body>

    <div class="forgot-page-container">
        
        <div class="forgot-form-card">
            
            <h1 class="form-title">Esqueceu sua senha?</h1>
            <p class="form-subtitle">
                Sem problema! Informe seu e-mail e enviaremos um link para redefinir sua senha rapidamente.
            </p>

            <form method="POST" action="/password/email">
                <div class="form-group">
                    <label for="email" class="input-label" style="display:none;">Email</label>

                    <input 
                        id="email" 
                        class="text-input" 
                        type="email" 
                        name="email" 
                        value="" 
                        required 
                        autofocus
                        placeholder="Seu endereço de email" 
                    />
                    </div>

                <div class="flex items-center justify-center mt-4">
                    <button type="submit" class="submit-button">
                        ENVIAR LINK DE REDEFINIÇÃO
                    </button>
                </div>
            </form>
        </div>
        
    </div>

</body>
</html>