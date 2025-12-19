<x-layout>
    <link rel="stylesheet" href="{{ asset('css/admin-copy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-copy-dashboard.css') }}">

    <div class="copy-main-wrapper">
        
        <header class="titan-header-container">
            <div class="header-content">
                <img src="/img/img-admin/logo titan.png" alt="Titan Logo" class="sidebar-logo">
                <span class="brand-name">Titan</span>
            </div>
        </header>

        <div class="title-section">
            <h1 class="main-title">Produção De CopyWrites</h1>
            <p class="sub-title">Métricas De Copy</p>
        </div>

        <div class="selector-container">
            <div class="glass-box">
                <div class="arrow-down-glow">
                    <div class="circle-icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <p class="instruction-text">Escolha qual métrica deseja visualizar</p>

                <div class="button-group">
                    <button id="btn-dashboard" class="btn-toggle active" onclick="switchView('dashboard')">
                        Dashboard
                    </button>
                    <button id="btn-creatives" class="btn-toggle inactive" onclick="switchView('creatives')">
                        Criativos
                    </button>
                </div>
            </div>
        </div>

        <section id="section-dashboard" class="content-section">
            <div class="placeholder-content">
                <h3>Dashboard Carregado</h3>
                <p>Área de métricas individuais e squads.</p>
            </div>
        </section>

        <section id="section-creatives" class="content-section" style="display: none;">
            <div class="placeholder-content">
                <h3>Seção de Criativos Carregada</h3>
            </div>
        </section>

    </div>

    <script>
        function switchView(view) {
            const btnDash = document.getElementById('btn-dashboard');
            const btnCreatives = document.getElementById('btn-creatives');
            const secDash = document.getElementById('section-dashboard');
            const secCreatives = document.getElementById('section-creatives');

            if (view === 'dashboard') {
                
                btnDash.classList.replace('inactive', 'active');
                btnCreatives.classList.replace('active', 'inactive');
                
                secDash.style.display = 'block';
                secCreatives.style.display = 'none';
            } else {
                
                btnCreatives.classList.replace('inactive', 'active');
                btnDash.classList.replace('active', 'inactive');
                
                secDash.style.display = 'none';
                secCreatives.style.display = 'block';
            }
        }
    </script>
</x-layout>