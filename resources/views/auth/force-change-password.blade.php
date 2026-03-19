<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alterar Senha - TITAN ADM</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700,900" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        .modal-card {
            position: relative;
            z-index: 1000;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 20px;
            padding: 48px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7),
                        0 0 80px rgba(59, 130, 246, 0.1);
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.05) 100%);
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .modal-icon i {
            font-size: 36px;
            color: #60a5fa;
        }

        .modal-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
        }

        .modal-description {
            text-align: center;
            font-size: 15px;
            color: #cbd5e1;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .success-alert {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            animation: fadeIn 0.3s ease;
        }

        .success-alert i {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .error-alert {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            animation: fadeIn 0.3s ease;
        }

        .error-alert ul {
            list-style: none;
            margin-top: 8px;
            margin-left: 0;
        }

        .error-alert li {
            margin-bottom: 4px;
            padding-left: 20px;
            position: relative;
        }

        .error-alert li:before {
            content: '•';
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .error-alert li:last-child {
            margin-bottom: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 8px;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input::placeholder {
            color: #64748b;
        }

        .form-input.error {
            border-color: rgba(239, 68, 68, 0.5) !important;
            background: rgba(239, 68, 68, 0.05) !important;
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 6px;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .toggle-password-btn:hover {
            color: #cbd5e1;
        }

        .form-error {
            font-size: 13px;
            color: #fca5a5;
            margin-top: 6px;
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .form-error i {
            font-size: 12px;
            flex-shrink: 0;
        }

        .password-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .password-hint i {
            font-size: 12px;
            flex-shrink: 0;
            color: #475569;
        }

        .password-strength {
            display: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            align-items: center;
            gap: 8px;
        }

        .password-strength.visible {
            display: flex;
        }

        .password-strength.weak {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .password-strength.medium {
            background: rgba(245, 158, 11, 0.1);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .password-strength.strong {
            background: rgba(34, 197, 94, 0.1);
            color: #86efac;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .btn-submit {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-submit:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="modal-card">
        <div class="modal-icon">
            <i class="fas fa-lock"></i>
        </div>

        <h1 class="modal-title">Alterar Senha</h1>
        <p class="modal-description">
            Por segurança, você precisa alterar sua senha antes de continuar usando o sistema.
        </p>

        @if ($errors->any())
            <div class="error-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="error-alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('password.force-change.update') }}" method="POST" id="passwordForm" novalidate>
            @csrf

            <div class="form-group">
                <label for="current_password" class="form-label">Senha Atual</label>
                <div class="form-input-wrapper">
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        class="form-input @error('current_password') error @enderror"
                        placeholder="Digite sua senha atual"
                        required
                        autofocus
                    >
                </div>
                @error('current_password')
                    <div class="form-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Nova Senha</label>
                <div class="form-input-wrapper">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-input @error('password') error @enderror"
                        placeholder="Digite uma nova senha (mín. 8 caracteres)"
                        required
                    >
                </div>
                @error('password')
                    <div class="form-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
                <div class="password-hint">
                    <i class="fas fa-info-circle"></i>
                    <span>Mínimo de 8 caracteres</span>
                </div>
                <div id="passwordStrength" class="password-strength"></div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                <div class="form-input-wrapper">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="form-input @error('password_confirmation') error @enderror"
                        placeholder="Confirme a nova senha"
                        required
                    >
                </div>
                @error('password_confirmation')
                    <div class="form-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
                <div id="matchWarning" style="display: none;" class="password-hint" style="color: #fca5a5; margin-top: 8px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>As senhas não conferem</span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-shield-alt"></i> Alterar Senha
            </button>
        </form>
    </div>

    <script>
        // Validação de força de senha
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            return strength;
        }

        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            
            if (this.value.length > 0) {
                passwordStrength.classList.add('visible');
                passwordStrength.innerHTML = '';

                if (strength <= 2) {
                    passwordStrength.className = 'password-strength visible weak';
                    passwordStrength.innerHTML = '<i class="fas fa-times-circle"></i> Senha fraca';
                } else if (strength <= 4) {
                    passwordStrength.className = 'password-strength visible medium';
                    passwordStrength.innerHTML = '<i class="fas fa-exclamation-circle"></i> Senha média';
                } else {
                    passwordStrength.className = 'password-strength visible strong';
                    passwordStrength.innerHTML = '<i class="fas fa-check-circle"></i> Senha forte';
                }
            } else {
                passwordStrength.classList.remove('visible');
            }

            // Validar confirmação
            validatePasswordMatch();
        });

        // Validação de confirmação de senha
        const confirmInput = document.getElementById('password_confirmation');
        const matchWarning = document.getElementById('matchWarning');

        function validatePasswordMatch() {
            if (confirmInput.value && passwordInput.value !== confirmInput.value) {
                matchWarning.style.display = 'flex';
                confirmInput.classList.add('error');
            } else {
                matchWarning.style.display = 'none';
                confirmInput.classList.remove('error');
            }
        }

        confirmInput.addEventListener('input', validatePasswordMatch);

        // Validação de formulário
        const form = document.getElementById('passwordForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            // Validação do cliente
            const currentPassword = document.getElementById('current_password').value;
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;

            if (!currentPassword) {
                e.preventDefault();
                alert('Por favor, digite sua senha atual');
                return;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('A nova senha deve ter pelo menos 8 caracteres');
                return;
            }

            if (password !== confirmation) {
                e.preventDefault();
                alert('As senhas não conferem');
                return;
            }

            if (currentPassword === password) {
                e.preventDefault();
                alert('A nova senha deve ser diferente da senha atual');
                return;
            }

            // Submit
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="spinner"></div> Alterando senha...';
        });
    </script>
</body>
</html>
