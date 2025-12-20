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
        {{-- COMECO DASHBOARD --}}
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

        <div class="analytics-charts-section">

    <div class="section-divider">
        <h2 class="display-title-performance">Performance Individual <span class="title-italic-light">Geral</span></h2>
    </div>

    <div class="niche-selector-bar">
        <div class="niche-block active" data-niche="mrm" onclick="updateNiche('mrm')">
            <div class="niche-badge">
                <span class="perc">49.65%</span>
                <span class="name">Mister M</span>
            </div>
        </div>
        <div class="niche-block ed" data-niche="ed" onclick="updateNiche('ed')">
            <div class="niche-badge">
                <span class="perc">45.20%</span>
                <span class="name">Ed</span>
            </div>
        </div>
        <div class="niche-block wl" data-niche="wl" onclick="updateNiche('wl')">
            <div class="niche-badge">
                <span class="perc">51.10%</span>
                <span class="name">WL</span>
            </div>
        </div>
        <div class="niche-block tn" data-niche="tn" onclick="updateNiche('tn')">
            <div class="niche-badge">
                <span class="perc">38.00%</span>
                <span class="name">TN</span>
            </div>
        </div>
    </div>

    <div id="container-graph-individual" class="graph-main-container glow-mrm">
        <div class="quadrant-labels">
            <span class="label-tl">Metralhadora</span>
            <span class="label-tr">Estrelas</span>
            <span class="label-bl">Gargalo</span>
            <span class="label-br">Sniper</span>
        </div>
        <canvas id="chartIndividual"></canvas>
    </div>

    <div class="section-divider mt-80">
        <h2 class="display-title">Sinergia do Time</h2>
        
        <div class="toggle-buttons-row">
            <button class="btn-synergy active">Selecionar Copy</button>
            <button class="btn-synergy inactive">Selecionar Editor</button>
        </div>
    </div>

    <div id="container-graph-synergy" class="graph-main-container glow-mrm">
        <div class="quadrant-labels">
            <span class="label-tl">Alto Custo</span>
            <span class="label-tr">Duplas Estrela</span>
            <span class="label-bl">Baixa Perf.</span>
            <span class="label-br">Boa Qualidade</span>
        </div>
        <canvas id="chartSynergy"></canvas>
    </div>
</div>

{{-- GRAFICO --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 

</section> {{-- FIM DA SECTION DASHBOARD --}}


        {{-- COMECO CRIATIVOS --}}
        {{-- <section id="section-creatives" class="content-section" style="display: none;">
            <div class="placeholder-content">
                <h3>Seção de Criativos Aqui (vou adicionar o codigo antigo) </h3>
            </div>
        </section> --}}

    </div>


    {{-- SCRIPT PARA ALTERNAR ENTRE DASHBOARD E CRIATIVOS --}}
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

    {{-- SCRIPT PARA TROCAR DE NICHO E CONFIGURAR AS BOLHAS CONFOME O ROI --}}
    <script>

        // cores e estados
        const nicheConfigs = {
            mrm: { color: '#0055ff', class: 'glow-mrm' },
            ed: { color: '#cc0000', class: 'glow-ed' },
            wl: { color: '#00aa00', class: 'glow-wl' },
            tn: { color: '#666666', class: 'glow-tn' }
        };

        // exemplos 
        const dataExample = [
            { x: 4.2, y: 32, r: 25, label: 'JT', name: 'Julia Tavares' },
            { x: 1.8, y: 22, r: 15, label: 'RB', name: 'Rogerio Barenco' },
            { x: 1.1, y: 16, r: 10, label: 'VG', name: 'Vinicius Gomes' },
            { x: 3.5, y: 12, r: 20, label: 'XX', name: 'Bruna Aguiar' }
        ];

        function initCharts() {
            const ctx1 = document.getElementById('chartIndividual').getContext('2d');
            const ctx2 = document.getElementById('chartSynergy').getContext('2d');

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { 
                title: { display: true, text: 'Y Quantidade', color: '#fff' },
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { color: '#fff' },
                min: 0, max: 40
            },
            x: { 
                title: { display: true, text: 'x ROI', color: '#fff' },
                grid: { color: 'rgba(255,255,255,0.05)' },
                ticks: { color: '#fff' },
                min: 0, max: 6
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const d = context.raw;
                        return [
                            `${d.name} (Copy)`,
                            `Produzidos: ${d.y} Ads`,
                            `ROI: ${d.x}x`,
                            `Profit: $${d.r * 10}K`
                        ];
                    }
                }
            }
        }
    };

    window.chart1 = new Chart(ctx1, {
        type: 'bubble',
        data: {
            datasets: [{
                data: dataExample,
                backgroundColor: 'rgba(255,255,255,0.8)',
                hoverBackgroundColor: '#fff'
            }]
        },
        options: commonOptions
    });

    window.chart2 = new Chart(ctx2, {
        type: 'bubble',
        data: {
            datasets: [{
                data: dataExample,
                backgroundColor: '#00aa00',
                label: 'Sinergia'
            }]
        },
        options: commonOptions
    });
}

// funcao para trocar de nicho e glow
    function updateNiche(nicheKey) {
        // atualiza os botoes
        document.querySelectorAll('.niche-block').forEach(b => b.classList.remove('active'));
        document.querySelector(`[data-niche="${nicheKey}"]`).classList.add('active');

        // troca glow dos conteiners
        const containers = document.querySelectorAll('.graph-main-container');
        const config = nicheConfigs[nicheKey];
        
        containers.forEach(c => {
            c.className = 'graph-main-container ' + config.class;
        });

        // dados do grafico
        window.chart1.data.datasets[0].backgroundColor = config.color;
        window.chart1.update();
    }

    // incia
    document.addEventListener('DOMContentLoaded', initCharts);

    </script>


</x-layout>