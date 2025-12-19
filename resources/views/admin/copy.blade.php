<x-layout>
    <link rel="stylesheet" href="{{ asset('css/admin-copy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-copy-dashboard.css') }}">

    <div class="copy-main-wrapper">
        
        <header class="titan-header-container">
            <div class="header-content">
                <img src="/img/img-admin/logo titan.png" alt="Titan Logo" class="sidebar-logo">
                <span class="brand-name">Agência Titan</span>
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
    
    <div class="filter-control-panel">
        <div class="filter-inner-box">
            <h3 class="filter-main-title">Seleção de Filtro</h3>
            <p class="filter-sub-title">Escolha o período desejado e veja as novas métricas.</p>
            
            <form class="filters-grid filters-grid-production">
                <div class="filter-group">
                    <x-date-range 
                        name="date" 
                        :from="$startDate" 
                        :to="$endDate" 
                        label="Intervalo de Datas" 
                    />
                </div>
                <button type="button" class="btn-filter-action">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="section-divider">
        <h2 class="display-title">Performance Geral</h2>
        <p class="display-subtitle">Nicho: Mrm, Ed, Wl</p>
    </div>

    <div class="main-metrics-row">
        <div class="metric-card-primary glow-blue">
            <div class="card-icon-top">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="internal-stack">
                <div class="mini-card-outline">
                    <span class="mini-label">Total Produzido</span>
                    <span class="mini-value">x.xxx Ads</span>
                </div>
                <div class="mini-card-outline">
                    <span class="mini-label">Total Testado</span>
                    <span class="mini-value">x.xxx Ads</span>
                </div>
            </div>
        </div>

        <div class="metric-card-secondary">
            <div class="card-icon-top">
                <i class="fas fa-percent"></i>
            </div>
            <div class="internal-stack">
                <div class="mini-card-outline secondary-border">
                    <span class="mini-label">Em validação</span>
                    <span class="mini-value">xx% | xx Ads</span>
                </div>
                <div class="mini-card-outline secondary-border">
                    <span class="mini-label">Taxa de Acerto</span>
                    <span class="mini-value">xx% | xx Ads</span>
                </div>
            </div>
        </div>
    </div>

    <div class="secondary-metrics-grid">
        <div class="small-metric-card">
            <span class="small-label">Melhor Nicho</span>
            <span class="small-data">MM | <span class="highlight-roi">xx% ROI</span></span>
        </div>
        <div class="small-metric-card">
            <span class="small-label">Maior Nicho</span>
            <span class="small-data">MM | <span class="highlight-profit">xx% do Profit</span></span>
        </div>

        <div class="small-metric-card">
            <span class="small-label">Melhor Copy</span>
            <span class="small-data">Nome | <span class="highlight-roi">xx% ROI</span></span>
        </div>
        <div class="small-metric-card">
            <span class="small-label">Maior Copy</span>
            <span class="small-data">Nome | <span class="highlight-profit">xx% do Profit</span></span>
        </div>

        <div class="small-metric-card">
            <span class="small-label">Melhor Dupla</span>
            <span class="small-data">RB e JP | <span class="highlight-roi">xx% ROI</span></span>
        </div>
        <div class="small-metric-card">
            <span class="small-label">Maior Dupla</span>
            <span class="small-data">RB e JP | <span class="highlight-profit">xx% do Profit</span></span>
        </div>
    </div>

</section>

        {{-- <section id="section-creatives" class="content-section" style="display: none;">
            <div class="placeholder-content">
                <h3>Seção de Criativos Aqui (vou adicionar o codigo antigo) </h3>
            </div>
        </section> --}}

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