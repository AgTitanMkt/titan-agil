<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'TITAN RH' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700,900" rel="stylesheet" />

    @vite([
        'resources/css/app.css',
        'resources/css/css-admin/admin-layout.css', 
        'resources/css/css-rh/css-rh.css', // CSS DO RH AQUI
        'resources/css/css-admin/admin-dashboard.css', 
        'resources/css/css-admin/admin-copy.css', 
        'resources/css/css-admin/admin-faturamento.css', 
        'resources/css/css-admin/admin-time.css', 
        'resources/css/css-admin/admin-perfil.css', 
        'resources/css/css-admin/admin-multiselect.css', 
        'resources/css/css-admin/admin-editors.css', 
        'resources/css/css-admin/admin-gestores.css', 
        'resources/css/css-rh/rh-colaboradores.css', // CSS DO RH COLABORADORES AQUI
        'resources/js/app.js', 
    ])
    @stack('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
    <div class="admin-page-wrapper">

        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/img/img-admin/logo titan.png" alt="Titan Logo" class="sidebar-logo">
                <span class="sidebar-title">TITAN MARKETING</span>
            </div>

            <nav class="sidebar-nav">
                
                {{-- ADMIN --}}
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
                    <li class="nav-item">
                        <a href="{{ route('admin.copywriters') }}" class="nav-link">
                            <i class="fas fa-chart-bar nav-icon"></i> Métricas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.copywriters') }}" class="nav-link">
                            <i class="fas fa-pen-fancy nav-icon"></i> CopyWriters
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.editors') }}" class="nav-link">
                            <i class="fas fa-edit nav-icon"></i> Editores
                        </a>
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
                
                {{-- RECURSOS HUMANOS --}}
                <p class="nav-section-title">RECURSOS HUMANOS</p>
                <ul>
                    {{-- Central de Colaboradores --}}
                    <li class="nav-item">
                        <a href="{{ route('rh.colaboradores') }}" class="nav-link">
                            <i class="fas fa-id-card nav-icon"></i> Central de Colaboradores
                        </a>
                    </li>
                    
                    {{-- Dashboards --}}
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle" data-target="dashboards-menu">
                            <i class="fas fa-chart-pie nav-icon"></i> Dashboards <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>
                        <ul id="dashboards-menu" class="submenu">
                            <li class="nav-item-submenu"><a href="{{ route('rh.pessoas') }}" class="nav-link-submenu">Gestão de Pessoal</a></li>
                            <li class="nav-item-submenu"><a href="{{ route('rh.equipe') }}" class="nav-link-submenu">Inteligência de Equipe (BI e RH)</a></li>
                            <li class="nav-item-submenu"><a href="{{ route('rh.financeiro') }}" class="nav-link-submenu">Gestão Financeira (Visão RH)</a></li>
                            <li class="nav-item-submenu"><a href="{{ route('rh.operacoes') }}" class="nav-link-submenu">Operações (Financeiro)</a></li>
                        </ul>
                    </li>

                    {{-- Sistema de Gestão e Integrações  --}}
                    <li class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle" data-target="gestao-integracoes-menu">
                            <i class="fas fa-cogs nav-icon"></i> Sistema de Gestão e Integrações <i class="fas fa-chevron-down submenu-arrow"></i>
                        </a>
                        <ul id="gestao-integracoes-menu" class="submenu">
                            <li class="nav-item-submenu"><a href="{{ route('rh.carreira') }}" class="nav-link-submenu">Plano de Carreira e PDI</a></li>
                            <li class="nav-item-submenu"><a href="{{ route('rh.comportamental') }}" class="nav-link-submenu">Mapeamento de Perfil Comportamental</a></li>
                            <li class="nav-item-submenu"><a href="{{ route('rh.documentos') }}" class="nav-link-submenu">Gestão de Documentos</a></li>
                        </ul>
                    </li>

                    {{-- MODULO DE ON/OFF, PERFOMANCE VS. CUSTO, PESQUISA E HISTORICO --}}
                    <li class="nav-item">
                        <a href="{{ route('rh.cadastro') }}" class="nav-link">
                            <i class="fas fa-clipboard-list nav-icon"></i> Módulo de On/Offboarding
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('rh.performance') }}" class="nav-link">
                            <i class="fas fa-money-check-alt nav-icon"></i> Perfomance vs. Custo (ROI)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('rh.pesquisa') }}" class="nav-link">
                            <i class="fas fa-search nav-icon"></i> Pesquisa e Histórico
                        </a>
                    </li>
                </ul>
                
                
                {{-- PÁGINAS DE CONTA ( X-LAYOUT) --}}
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


        {{-- scripts para o Sidebar e a logica do sub menus do rh --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const menuToggle = document.getElementById('menuToggle');
                const adminWrapper = document.querySelector('.admin-page-wrapper');
                const mainContentArea = document.querySelector('.main-content-area');
                const sidebar = document.querySelector(".sidebar");
                const burger = document.getElementById("burger-toggle");


                // logica de toggle para o sidebar em telas menores
                if (menuToggle && adminWrapper && mainContentArea) {
                    menuToggle.addEventListener('click', function() {
                        adminWrapper.classList.toggle('sidebar-open');
                        sidebar.classList.toggle("sidebar-open");
                        mainContentArea.classList.toggle("sidebar-open");
                    });

                    // fechar o menu ao clicar fora (no conteudo principal)
                    mainContentArea.addEventListener('click', function(event) {
                        if (adminWrapper.classList.contains('sidebar-open')) {
                            // verifica se o clique foi no conteudo mas nao no botao de toggle
                            if (event.target.closest('.page-content')) {
                                adminWrapper.classList.remove('sidebar-open');
                                sidebar.classList.remove("sidebar-open");
                                mainContentArea.classList.remove("sidebar-open");
                            }
                        }
                    });

                    // fechar clicando fora da sidebar 
                    document.addEventListener("click", (e) => {
                        const clickedInsideSidebar = sidebar.contains(e.target);
                        const clickedBurger = burger.contains(e.target);

                        if (!clickedInsideSidebar && !clickedBurger && window.innerWidth < 1024) {
                            sidebar.classList.remove("sidebar-open");
                            mainContentArea.classList.remove("sidebar-open");
                            adminWrapper.classList.remove('sidebar-open');
                        }
                    });


                    // menu fechado em desktop
                    window.addEventListener('resize', function() {
                        if (window.innerWidth > 1024) {
                            adminWrapper.classList.remove('sidebar-open');
                            sidebar.classList.remove("sidebar-open");
                            mainContentArea.classList.remove("sidebar-open");
                        }
                    });
                }


                // logica de Toggling para  sub menus do RH
                const submenuToggles = document.querySelectorAll('.submenu-toggle');

                submenuToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault(); // evita navegacao, pois e um menu expansivel

                        const targetId = this.getAttribute('data-target');
                        const submenu = document.getElementById(targetId);
                        const arrowIcon = this.querySelector('.submenu-arrow');
                        const parentLi = this.closest('.nav-item');

                        // toggle da visibilidade do sub menu
                        submenu.classList.toggle('open');
                        parentLi.classList.toggle('submenu-active');

                        // toggle da rotacao da seta
                        if (submenu.classList.contains('open')) {
                            arrowIcon.style.transform = 'rotate(180deg)';
                        } else {
                            arrowIcon.style.transform = 'rotate(0deg)';
                        }
                    });
                });
            });
        </script>

    </div>
    @stack('scripts')
</body>

</html>