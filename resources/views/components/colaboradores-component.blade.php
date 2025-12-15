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
        'resources/css/css-admin/admin-dashboard.css', // CSS DO DASHBOARD //
        'resources/css/css-admin/admin-copy.css', // CSS DO COPY //
        'resources/css/css-admin/admin-faturamento.css', // CSS DO FATURAMENTO //
        'resources/css/css-admin/admin-time.css', // CSS DO TIME //
        'resources/css/css-admin/admin-perfil.css', // CSS DO PERFIL (AINDA NAO USADO) // 
        'resources/css/css-admin/admin-multiselect.css', // CSS DO MULTISELECT (FILTROS) // 
        'resources/css/css-admin/admin-editors.css', // CSS DOS EDITORS //
        'resources/css/css-admin/admin-gestores.css', // CSS DOS GESTORES // 
        'resources/css/css-colaboradores/colaboradores-metas.css', // CSS DAS METAS // 
        'resources/css/css-colaboradores/colaboradores-copaprofit.css', // CSS DA COPA PROFIT // 
        'resources/js/app.js',
    ])
    @stack('styles')
</head>

<body>
<div class="admin-page-wrapper">

    <!-- SIDEBAR -->
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

                <li class="nav-item has-submenu open">
                    <a href="#" class="nav-link submenu-toggle">
                        <i class="fas fa-chart-bar nav-icon"></i> Métricas
                        <i class="fas fa-chevron-down submenu-arrow"></i>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="{{ route('colaboradores.metas') }}" class="nav-link submenu-link active">
                                <i class="fas fa-bullseye nav-icon"></i> Metas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.copywriters') }}" class="nav-link submenu-link">
                                <i class="fas fa-pen-fancy nav-icon"></i> CopyWriters
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.editors') }}" class="nav-link submenu-link">
                                <i class="fas fa-edit nav-icon"></i> Editores
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
    <!-- /SIDEBAR ACABOU -->

    <!-- CONTEUDO DENTRO -->
    <div class="main-content-area">

        <header class="header-bar">
            <button class="menu-toggle" id="menuToggle">
                <div id="burger-toggle" class="burger-btn">
                    <i class="fa fa-bars"></i>
                </div>
            </button>

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
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<!-- SCRIPTS -->
<script>
document.addEventListener('DOMContentLoaded', () => {

    // burger
    const burger = document.getElementById("burger-toggle");
    const sidebar = document.querySelector(".sidebar");
    const mainContent = document.querySelector(".main-content-area");

    burger.addEventListener("click", () => {
        sidebar.classList.toggle("sidebar-open");
        mainContent.classList.toggle("sidebar-open");
    });

    document.addEventListener("click", (e) => {
        if (!sidebar.contains(e.target) && !burger.contains(e.target)) {
            sidebar.classList.remove("sidebar-open");
            mainContent.classList.remove("sidebar-open");
        }
    });

    // submenu
    document.querySelectorAll('.submenu-toggle').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            btn.parentElement.classList.toggle('open');
        });
    });
});
</script>

@stack('scripts')
</body>
</html>