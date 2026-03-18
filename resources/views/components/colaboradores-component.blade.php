<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>{{ $title ?? 'TITAN ADM' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700,900" rel="stylesheet" />

    @vite([
        'resources/css/app.css',
        'resources/css/css-admin/admin-layout.css', // CSS DO COMPONENTE //
        'resources/css/css-admin/admin-dashboard.css', // CSS DO DASH //
        'resources/css/css-admin/admin-copy.css', // CSS DO COPY //
        'resources/css/css-admin/admin-faturamento.css', // CSS DO FATURAMENTO //
        'resources/css/css-admin/admin-time.css', // CSS DO TIME //
        'resources/css/css-admin/admin-perfil.css', // CSS DO PERFIL //
        'resources/css/css-admin/admin-multiselect.css', // CSS DO MULTISELECT - FILTRO //
        'resources/css/css-admin/admin-editors.css', // CSS DOS EDITORES //
        'resources/css/css-admin/admin-gestores.css', // CSS DOS GESTORES CTR (%) CPA//
        'resources/css/css-colaboradores/colaboradores-metas.css', // CSS DOS COLABORADORES PARA VER AS METAS - ATUAL //
        'resources/js/app.js', // JS //
    ])
    @stack('styles')
</head>

<body>
    <div class="admin-page-wrapper">

        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/img/img-admin/logo titan.png" alt="Titan Logo" class="sidebar-logo">
                <span class="sidebar-title">TITAN MARKETING</span>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    {{-- ADMIN, HEAD, MANAGER -> DASHBOARD --}}
                    @if(auth()->user()->hasAnyRole(['ADMIN', 'HEAD', 'MANAGER']))
                    <li class="nav-item active">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="fas fa-chart-line nav-icon"></i> Dashboard
                        </a>
                    </li>

                    {{-- Seção: DADOS (visível para ADMIN, HEAD, MANAGER) --}}
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle">
                            <i class="fas fa-database nav-icon"></i> Dados
                            <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>

                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.time') }}" class="nav-link submenu-link">
                                    <i class="fas fa-users nav-icon"></i> Time
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.faturamento') }}" class="nav-link submenu-link">
                                    <i class="fas fa-wallet nav-icon"></i> Faturamento
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gestores') }}" class="nav-link submenu-link">
                                    <i class="fas fa-users-cog nav-icon"></i> Gestores
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Seção: MÉTRICAS (visível para ADMIN, HEAD, MANAGER) --}}
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle">
                            <i class="fas fa-chart-bar nav-icon"></i> Métricas
                            <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>

                        <ul class="submenu">
                            <li>
                                <a href="{{ route('colaboradores.metas') }}" class="nav-link submenu-link">
                                    <i class="fas fa-bullseye nav-icon"></i> Metas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.agents', ['copywriters', 'IN']) }}" class="nav-link submenu-link">
                                    <i class="fas fa-pen-fancy nav-icon"></i> Copywriters Internos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.agents', ['copywriters', 'EX']) }}" class="nav-link submenu-link">
                                    <i class="fas fa-edit nav-icon"></i> Copywriters Externos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.agents', ['editors', 'IN']) }}" class="nav-link submenu-link">
                                    <i class="fas fa-video nav-icon"></i> Editores Internos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.creatives', 'IN') }}" class="nav-link submenu-link">
                                    <i class="fas fa-layer-group nav-icon"></i> Criativos Internos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.creatives', 'EX') }}" class="nav-link submenu-link">
                                    <i class="fas fa-shapes nav-icon"></i> Criativos Externos
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Gerenciar (apenas ADMIN e MANAGER) --}}
                    @if(auth()->user()->hasAnyRole(['ADMIN', 'MANAGER']))
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle">
                            <i class="fas fa-sliders-h nav-icon"></i> Gerenciar
                            <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>

                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.users.create') }}" class="nav-link submenu-link">
                                    <i class="fas fa-user-plus nav-icon"></i> Cadastrar novo usuário
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @else
                    {{-- COPY, EDITOR, DEVELOPER, ANALYST, ASSISTANT -> METAS ao entrar --}}
                    <li class="nav-item active">
                        <a href="{{ route('colaboradores.metas') }}" class="nav-link">
                            <i class="fas fa-bullseye nav-icon"></i> Metas
                        </a>
                    </li>

                    {{-- COPYWRITER -> vê Copy (INT/EXT) e Criativos (INT/EXT) --}}
                    @if(auth()->user()->hasRole('COPYWRITER'))
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle">
                            <i class="fas fa-pen-fancy nav-icon"></i> Copywriting
                            <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>

                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.agents', ['copywriters', 'IN']) }}" class="nav-link submenu-link">
                                    <i class="fas fa-pen-fancy nav-icon"></i> Copywriters Internos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.agents', ['copywriters', 'EX']) }}" class="nav-link submenu-link">
                                    <i class="fas fa-pen-fancy nav-icon"></i> Copywriters Externos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.creatives', 'IN') }}" class="nav-link submenu-link">
                                    <i class="fas fa-layer-group nav-icon"></i> Criativos Internos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.creatives', 'EX') }}" class="nav-link submenu-link">
                                    <i class="fas fa-shapes nav-icon"></i> Criativos Externos
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    {{-- EDITOR -> vê Editor (INT) e Criativos (INT/EXT) --}}
                    @if(auth()->user()->hasRole('EDITOR'))
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle">
                            <i class="fas fa-video nav-icon"></i> Edição
                            <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>

                        <ul class="submenu">
                            <li>
                                <a href="{{ route('admin.agents', ['editors', 'IN']) }}" class="nav-link submenu-link">
                                    <i class="fas fa-video nav-icon"></i> Editores Internos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.creatives', 'IN') }}" class="nav-link submenu-link">
                                    <i class="fas fa-layer-group nav-icon"></i> Criativos Internos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.creatives', 'EX') }}" class="nav-link submenu-link">
                                    <i class="fas fa-shapes nav-icon"></i> Criativos Externos
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @endif

                    {{-- TAREFAS - Visível para TODOS --}}
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle">
                            <i class="fa fa-bars nav-icon"></i> Tarefas
                            <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>

                        <ul class="submenu">
                            <li>
                                <a href="{{ route('tarefas.listagem') }}" class="nav-link submenu-link">
                                    <i class="fa-solid fa-list nav-icon"></i> Listagem
                                </a>
                            </li>
                            @if(auth()->user()->hasAnyRole(['ADMIN', 'HEAD', 'MANAGER', 'GESTOR']))
                            <li>
                                <a href="{{ route('tarefas.cadastro') }}" class="nav-link submenu-link">
                                    <i class="fa-solid fa-file-circle-plus nav-icon"></i> Cadastro
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    {{-- IMPORTAR CSV - Visível para TODOS --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.import.index') }}" class="nav-link">
                            <i class="fas fa-file-excel nav-icon"></i> Importar CSV
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <img src="/img/img-admin/Sidebar Logo.png" alt="Logo Footer" class="footer-image">
            </div>

        </aside>

        <div class="main-content-area">

            <header class="header-bar">
                <button class="menu-toggle" id="menuToggle">
                    <div id="burger-toggle" class="burger-btn">
                        <i class="fa fa-bars"></i>
                    </div>
                </button>

                <div style="display: flex; gap: 15px; align-items: center;">
                    <div class="header-user">
                        <i class="fas fa-user-tie user-avatar-icon"></i>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                    </div>

                    <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>

                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </header>

            <main class="page-content">

                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                @if (session('warning'))
                    <x-alert type="warning" :message="session('warning')" />
                @endif

                @if (session('info'))
                    <x-alert type="info" :message="session('info')" />
                @endif
                {{ $slot }}
            </main>
            @stack('scripts')
        </div>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const menuToggle = document.getElementById('menuToggle');
                const adminWrapper = document.querySelector('.admin-page-wrapper');
                const mainContent = document.querySelector('.main-content-area');

                // Toggle menu on button click
                if (menuToggle) {
                    menuToggle.addEventListener('click', (e) => {
                        e.stopPropagation();
                        adminWrapper.classList.toggle('sidebar-open');
                    });
                }

                // Close menu when clicking on content
                if (mainContent) {
                    mainContent.addEventListener('click', (event) => {
                        if (adminWrapper.classList.contains('sidebar-open')) {
                            if (event.target.closest('.page-content')) {
                                adminWrapper.classList.remove('sidebar-open');
                            }
                        }
                    });
                }

                // Close menu on resize to desktop
                window.addEventListener('resize', () => {
                    if (window.innerWidth > 1024) {
                        adminWrapper.classList.remove('sidebar-open');
                    }
                });

                // Submenu toggle
                document.querySelectorAll('.submenu-toggle').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const item = btn.parentElement;
                        item.classList.toggle('open');
                    });
                });
            });
        </script>

    </div>
    @stack('scripts')
</body>

</html>
