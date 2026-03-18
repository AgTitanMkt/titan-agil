<x-layout>
    <style>
        .users-create-container {
            padding: 40px 30px;
            max-width: 700px;
            margin: 0 auto;
        }

        .create-header {
            margin-bottom: 40px;
        }

        .create-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .create-header p {
            font-size: 14px;
            color: #9ca3af;
        }

        .form-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #e5e7eb;
            margin-bottom: 8px;
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
            color: #6b7280;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select option {
            background: #1f2937;
            color: #fff;
        }

        .form-error {
            font-size: 12px;
            color: #ef4444;
            margin-top: 6px;
            display: block;
        }

        .form-help {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 6px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-submit {
            flex: 1;
            padding: 12px 24px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-cancel {
            flex: 1;
            padding: 12px 24px;
            background: transparent;
            color: #9ca3af;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            color: #e5e7eb;
            border-color: rgba(255, 255, 255, 0.25);
        }

        .success-alert {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .error-alert {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .role-info {
            background: rgba(59, 130, 246, 0.1);
            border-left: 3px solid #3b82f6;
            padding: 12px 16px;
            border-radius: 4px;
            margin-top: 12px;
            font-size: 13px;
            color: #93c5fd;
        }

        .btn-generate-password {
            padding: 6px 12px;
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-generate-password:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        .btn-generate-password:active {
            transform: translateY(0);
        }

        .btn-toggle-password {
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #9ca3af;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-toggle-password:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #e5e7eb;
            border-color: rgba(255, 255, 255, 0.25);
        }

        .password-strength {
            margin-top: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: none;
        }

        .password-strength.visible {
            display: block;
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

        .new-user-card {
            background: rgba(34, 197, 94, 0.05);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            margin-top: 16px;
        }

        .new-user-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .new-user-header i {
            font-size: 24px;
            color: #86efac;
        }

        .new-user-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #86efac;
            margin: 0;
        }

        .new-user-data {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .data-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .data-label {
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .data-value {
            font-size: 13px;
            font-weight: 500;
            color: #86efac;
            word-break: break-all;
            font-family: 'Courier New', monospace;
        }

        .btn-copy-data {
            width: 100%;
            padding: 12px 24px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-copy-data:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-copy-data:active {
            transform: translateY(0);
        }
    </style>

    <div class="users-create-container">
        <div class="create-header">
            <h1>Novo Usuário</h1>
            <p>Crie uma nova conta de usuário e atribua uma função</p>
        </div>

        @if ($errors->any())
            <div class="error-alert">
                <strong>Erros no formulário:</strong>
                <ul style="margin-top: 8px; margin-left: 16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="form-section" style="border-color: rgba(34, 197, 94, 0.3); background: rgba(34, 197, 94, 0.05);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <i class="fas fa-check-circle" style="font-size: 20px; color: #86efac;"></i>
                    <span style="color: #86efac; font-weight: 600;">{{ session('success') }}</span>
                </div>

                @if (session('newUser'))
                    <div class="new-user-card">
                        <div class="new-user-header">
                            <i class="fas fa-user-check"></i>
                            <h3>Dados do Novo Usuário</h3>
                        </div>

                        <div class="new-user-data">
                            <div class="data-item">
                                <div class="data-label">Nome</div>
                                <div class="data-value">{{ session('newUser.name') }}</div>
                            </div>
                            <div class="data-item">
                                <div class="data-label">Função</div>
                                <div class="data-value">{{ session('newUser.role') }}</div>
                            </div>
                            <div class="data-item" style="grid-column: 1 / -1;">
                                <div class="data-label">Email</div>
                                <div class="data-value">{{ session('newUser.email') }}</div>
                            </div>
                            <div class="data-item" style="grid-column: 1 / -1;">
                                <div class="data-label">Senha</div>
                                <div class="data-value" id="passwordDisplay">{{ session('newUser.password') }}</div>
                            </div>
                        </div>

                        <button type="button" class="btn-copy-data" id="copyDataBtn">
                            <i class="fas fa-copy" style="margin-right: 8px;"></i> Copiar Dados
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="form-section">
            @csrf

            <!-- Nome -->
            <div class="form-group">
                <label for="name" class="form-label">Nome Completo</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="form-input @error('name') border-red-500 @enderror"
                    placeholder="João Silva"
                    value="{{ old('name') }}"
                    required
                >
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-input @error('email') border-red-500 @enderror"
                    placeholder="joao@example.com"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <span class="form-help">O email deve ser único</span>
            </div>

            <!-- Senha -->
            <div class="form-group">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <label for="password" class="form-label" style="margin: 0;">Senha</label>
                    <button
                        type="button"
                        id="generatePasswordBtn"
                        class="btn-generate-password"
                        title="Gerar senha forte automaticamente"
                    >
                        <i class="fas fa-magic" style="margin-right: 4px;"></i> Gerar Senha
                    </button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-input @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                        readonly
                        required
                    >
                    <button
                        type="button"
                        id="togglePasswordVisibility"
                        class="btn-toggle-password"
                        title="Mostrar/Ocultar senha"
                    >
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <span class="form-help">Clique em "Gerar Senha" para criar uma senha forte automaticamente</span>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label for="role_id" class="form-label">Função</label>
                <select
                    id="role_id"
                    name="role_id"
                    class="form-select @error('role_id') border-red-500 @enderror"
                    required
                >
                    <option value="">-- Selecione uma função --</option>
                    @forelse($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                            {{ $role->title }}
                        </option>
                    @empty
                        <option disabled>Nenhuma role disponível</option>
                    @endforelse
                </select>
                @error('role_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <div class="role-info">
                    <strong>Funções Disponíveis:</strong><br>
                    • <strong>ADMIN</strong> - Papel de administrador<br>
                    • <strong>COPYWRITER</strong> - Papel de copy<br>
                    • <strong>EDITOR</strong> - Papel de editor<br>
                    • <strong>DEVELOPER</strong> - Papel de desenvolvedor<br>
                    • <strong>MANAGER</strong> - Papel de gestor<br>
                    • <strong>HEAD</strong> - Papel de head<br>
                    • <strong>ANALYST</strong> - Papel de analista<br>
                    • <strong>ASSISTANT</strong> - Papel de assistente
                </div>
            </div>

            <!-- Botões -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i> Criar Usuário
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn-cancel">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        // Função para gerar senha forte
        function generateStrongPassword(length = 16) {
            const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const lowercase = 'abcdefghijklmnopqrstuvwxyz';
            const numbers = '0123456789';
            const symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
            
            const allChars = uppercase + lowercase + numbers + symbols;
            let password = '';
            
            // Garantir que tem pelo menos um de cada tipo
            password += uppercase[Math.floor(Math.random() * uppercase.length)];
            password += lowercase[Math.floor(Math.random() * lowercase.length)];
            password += numbers[Math.floor(Math.random() * numbers.length)];
            password += symbols[Math.floor(Math.random() * symbols.length)];
            
            // Preencher o resto aleatoriamente
            for (let i = password.length; i < length; i++) {
                password += allChars[Math.floor(Math.random() * allChars.length)];
            }
            
            // Embaralhar
            return password.split('').sort(() => Math.random() - 0.5).join('');
        }

        // Botão gerar senha
        document.getElementById('generatePasswordBtn').addEventListener('click', (e) => {
            e.preventDefault();
            const newPassword = generateStrongPassword(16);
            document.getElementById('password').value = newPassword;
            
            // Feedback visual
            const btn = e.target.closest('.btn-generate-password');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check" style="margin-right: 4px;"></i> Senha Gerada!';
            btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.background = '';
            }, 2000);
        });

        // Toggle de visibilidade
        document.getElementById('togglePasswordVisibility').addEventListener('click', (e) => {
            e.preventDefault();
            const passwordInput = document.getElementById('password');
            const icon = e.currentTarget.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Gerar senha automaticamente ao carregar a página
        window.addEventListener('load', () => {
            const passwordInput = document.getElementById('password');
            if (!passwordInput.value) {
                document.getElementById('generatePasswordBtn').click();
            }
        });

        // Copiar dados do novo usuário
        const copyDataBtn = document.getElementById('copyDataBtn');
        if (copyDataBtn) {
            copyDataBtn.addEventListener('click', () => {
                const name = document.querySelector('.new-user-data .data-value')?.textContent || '';
                const role = document.querySelectorAll('.new-user-data .data-value')[1]?.textContent || '';
                const email = document.querySelectorAll('.new-user-data .data-value')[2]?.textContent || '';
                const password = document.querySelectorAll('.new-user-data .data-value')[3]?.textContent || '';

                const dataToCopy = `Credenciais de Acesso:

URL: ${window.location.origin}/login
Nome: ${name}
Função: ${role}
Email: ${email}
Senha: ${password}`;

                navigator.clipboard.writeText(dataToCopy).then(() => {
                    // Feedback visual
                    const originalText = copyDataBtn.innerHTML;
                    copyDataBtn.innerHTML = '<i class="fas fa-check" style="margin-right: 8px;"></i> Copiado!';
                    copyDataBtn.style.background = 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)';
                    
                    setTimeout(() => {
                        copyDataBtn.innerHTML = originalText;
                        copyDataBtn.style.background = '';
                    }, 2500);
                }).catch(err => {
                    console.error('Erro ao copiar:', err);
                    alert('Erro ao copiar dados. Tente novamente.');
                });
            });
        }
    </script>
</x-layout>
