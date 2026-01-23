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
        'resources/css/css-admin/admin-copy-dashboard.css', // CSS DO COPY PARA ABA DE DASHBOARD //
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
                    <li class="nav-item active">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="fas fa-chart-line nav-icon"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.time') }}" class="nav-link">
                            <i class="fas fa-users nav-icon"></i> Time
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.faturamento') }}" class="nav-link">
                            <i class="fas fa-wallet nav-icon"></i> Faturamento
                        </a>
                    </li>
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
                                <a href="{{ route('admin.agents', 'copywriters') }}" class="nav-link submenu-link">
                                    <i class="fas fa-pen-fancy nav-icon"></i> Copywriters
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.agents', 'editors') }}" class="nav-link submenu-link">
                                    <i class="fas fa-pen-fancy nav-icon"></i> Editores
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.gestores') }}" class="nav-link">
                            <i class="fas fa-users-cog nav-icon"></i> Gestores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.import.index') }}" class="nav-link">
                            <i class="fas fa-file-excel nav-icon"></i> Importar CSV Criativos
                        </a>
                    </li>
                </ul>


                <div class="nav-divider"></div>
                <p class="nav-section-title">PÁGINAS DE CONTA</p>

                <ul>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-circle nav-icon"></i> Perfil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-sign-in-alt nav-icon"></i> Sign In
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/logout" class="nav-link">
                            <i class="fas fa-rocket nav-icon"></i> Sign Up
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
                <div class="header-user">
                    <i class="fas fa-user-tie user-avatar-icon"></i>
                    <span class="user-name">{{ auth()->user()->name }}</span>
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



        <header class="header-bar">
            <button class="menu-toggle" id="menuToggle">
                <div id="burger-toggle" class="burger-btn">
                    <i class="fa fa-bars"></i>
                </div>
            </button>

            <div class="header-user">
                <div class="user-avatar-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span class="user-name">{{ auth()->user()->name }}</span>
            </div>
        </header>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const menuToggle = document.getElementById('menuToggle');
                const adminWrapper = document.querySelector('.admin-page-wrapper');
                const mainContentArea = document.querySelector('.main-content-area');

                if (menuToggle && adminWrapper && mainContentArea) {

                    // abrir/fechar o menu ao clicar no botao
                    menuToggle.addEventListener('click', function() {
                        adminWrapper.classList.toggle('sidebar-open');
                    });

                    // 
                    mainContentArea.addEventListener('click', function(event) {
                        // verifica se o sidebar esta aberto e se o clique foi fora do menu
                        if (adminWrapper.classList.contains('sidebar-open')) {
                            // impede o clique em elementos internos do conteudo de fechar o menu
                            if (event.target.closest('.page-content')) {
                                adminWrapper.classList.remove('sidebar-open');
                            }
                        }
                    });

                    // menu fechado em desktop
                    window.addEventListener('resize', function() {
                        if (window.innerWidth > 1024) {
                            adminWrapper.classList.remove('sidebar-open');
                        }
                    });
                }
            });
        </script>
        {{-- script do burger --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const burger = document.getElementById("burger-toggle");
                const sidebar = document.querySelector(".sidebar");
                const mainContent = document.querySelector(".main-content-area");

                burger.addEventListener("click", () => {
                    sidebar.classList.toggle("sidebar-open");
                    mainContent.classList.toggle("sidebar-open");
                });

                // Fechar clicando fora da sidebar (mobile)
                document.addEventListener("click", (e) => {
                    const clickedInsideSidebar = sidebar.contains(e.target);
                    const clickedBurger = burger.contains(e.target);

                    if (!clickedInsideSidebar && !clickedBurger) {
                        sidebar.classList.remove("sidebar-open");
                        mainContent.classList.remove("sidebar-open");
                    }
                });
            });
        </script>

    </div>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.submenu-toggle').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault(); // evita navegação imediata

                    const item = btn.parentElement;
                    item.classList.toggle('open');
                });
            });
        });
    </script>

</body>

</html>
