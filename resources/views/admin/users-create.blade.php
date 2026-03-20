<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

        /* Select2 Custom Theme - Blue */
        .select2-container--default .select2-selection--single {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 8px !important;
            height: 46px !important;
            padding: 6px 16px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff !important;
            line-height: 32px !important;
            font-size: 14px !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6b7280 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #9ca3af transparent transparent transparent !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: rgba(59, 130, 246, 0.5) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .select2-dropdown {
            background: #1f2937 !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 8px !important;
            overflow: hidden;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 6px !important;
            color: #fff !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field::placeholder {
            color: #6b7280 !important;
        }

        .select2-container--default .select2-results__option {
            padding: 10px 16px !important;
            color: #e5e7eb !important;
            font-size: 14px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: rgba(59, 130, 246, 0.3) !important;
            color: #fff !important;
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background: rgba(59, 130, 246, 0.15) !important;
            color: #93c5fd !important;
        }

        .select2-container--default .select2-results__option .user-option-email {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 48px;
            color: #4b5563;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 600;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
            color: #6b7280;
        }
    </style>

    <div class="users-create-container">
        <div class="create-header">
            <h1>Ativar Usuário</h1>
            <p>Selecione um usuário inativo para ativá-lo e atribuir uma função</p>
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
                            <h3>Dados do Usuário Ativado</h3>
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
                                <div class="data-label">Senha (provisória - será alterada no primeiro acesso)</div>
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

        @if ($inactiveUsers->isEmpty())
            <div class="form-section">
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h3>Nenhum usuário inativo</h3>
                    <p>Todos os usuários do sistema já estão ativos.</p>
                </div>
            </div>
        @else
            <form action="{{ route('admin.users.store') }}" method="POST" class="form-section">
                @csrf

                <!-- Usuário Inativo -->
                <div class="form-group">
                    <label for="user_id" class="form-label">Selecionar Usuário Inativo</label>
                    <select
                        id="user_id"
                        name="user_id"
                        class="form-select @error('user_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">-- Buscar usuário --</option>
                        @foreach($inactiveUsers as $user)
                            <option value="{{ $user->id }}" data-email="{{ $user->email }}" @selected(old('user_id') == $user->id)>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    <span class="form-help">Busque pelo nome ou email do usuário</span>
                </div>

                <!-- Senha (readonly, gerada automaticamente) -->
                <div class="form-group">
                    <label class="form-label">Senha Provisória</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input
                            id="password_preview"
                            type="text"
                            class="form-input"
                            readonly
                            style="font-family: 'Courier New', monospace; color: #93c5fd;"
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
                    <span class="form-help">A senha será gerada automaticamente no padrão #Agenciatitan + 4 dígitos. O usuário deverá alterá-la no primeiro acesso.</span>
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
                        <i class="fas fa-user-check" style="margin-right: 8px;"></i> Ativar Usuário
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-cancel">
                        Cancelar
                    </a>
                </div>
            </form>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Select2
            $('#user_id').select2({
                placeholder: '-- Buscar usuário pelo nome ou email --',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() { return 'Nenhum usuário inativo encontrado'; },
                    searching: function() { return 'Buscando...'; }
                }
            });

            // Gerar preview da senha ao selecionar usuário
            $('#user_id').on('change', function() {
                const userId = $(this).val();
                const preview = document.getElementById('password_preview');

                if (userId) {
                    const randomNum = String(Math.floor(Math.random() * 10000)).padStart(4, '0');
                    preview.value = '#Agenciatitan' + randomNum;
                } else {
                    preview.value = '';
                }
            });

            // Toggle visibilidade da senha
            document.getElementById('togglePasswordVisibility')?.addEventListener('click', function(e) {
                e.preventDefault();
                const input = document.getElementById('password_preview');
                const icon = this.querySelector('i');

                if (input.type === 'text') {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });

            // Copiar dados do usuário ativado
            const copyDataBtn = document.getElementById('copyDataBtn');
            if (copyDataBtn) {
                copyDataBtn.addEventListener('click', function() {
                    const values = document.querySelectorAll('.new-user-data .data-value');
                    const name = values[0]?.textContent?.trim() || '';
                    const role = values[1]?.textContent?.trim() || '';
                    const email = values[2]?.textContent?.trim() || '';
                    const password = values[3]?.textContent?.trim() || '';

                    const dataToCopy = `Credenciais de Acesso:\n\nURL: ${window.location.origin}/login\nNome: ${name}\nFunção: ${role}\nEmail: ${email}\nSenha: ${password}`;

                    navigator.clipboard.writeText(dataToCopy).then(function() {
                        const originalText = copyDataBtn.innerHTML;
                        copyDataBtn.innerHTML = '<i class="fas fa-check" style="margin-right: 8px;"></i> Copiado!';
                        copyDataBtn.style.background = 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)';

                        setTimeout(function() {
                            copyDataBtn.innerHTML = originalText;
                            copyDataBtn.style.background = '';
                        }, 2500);
                    }).catch(function(err) {
                        console.error('Erro ao copiar:', err);
                    });
                });
            }
        });
    </script>
</x-layout>
