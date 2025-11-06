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
        'resources/css/css-admin/admin-layout.css',    // CSS DO COMPONENTE //
        'resources/css/css-admin/admin-dashboard.css', // CSS DO DASH //
        'resources/css/css-admin/admin-copy.css', // CSS DO COPY //
        'resources/css/css-admin/admin-faturamento.css', // CSS DO FATURAMENTO //
        'resources/css/css-admin/admin-time.css', // CSS DO TIME //
        'resources/css/css-admin/admin-perfil.css', // CSS DO PERFIL //
        'resources/js/app.js'
    ])
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
                    <li class="nav-item">
                        <a href="{{ route('admin.copywriters') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt nav-icon"></i> Métricas
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
                {{ $slot }}
            </main>
            @stack('scripts')
        </div>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    </div>
</body>
</html> 