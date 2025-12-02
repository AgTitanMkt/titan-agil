<x-layout>
    
    {{-- ARQUIVO --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <div class="header-container">
        <h2 class="dashboard-page-title">Produção Gestores de Tráfego</h2>
        <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>
    </div>

    {{-- filtros de performance --}}
    <div class="production-filters-section glass-card filters-shadow">
        <h3 class="section-title">Filtros de Gestores</h3>

        <form class="filters-grid filters-grid-production">
            <div class="filter-group">
                {{-- AJUSTAR VARIAVEL --}}
                <x-date-range name="date" :from="now()->subDays(30)" :to="now()" label="Intervalo de Datas" />
            </div>
            <div class="filter-group">
                {{-- FILTRO GESTORES --}}
                <x-multiselect name="gestores" label="Gestores" :options="['Gestor Ary', 'Gestor Hoberlan', 'Gestor Barros']" :selected="request('gestores', [])"
                    placeholder="Selecione um ou mais gestores">
                </x-multiselect>
            </div>

            <div class="filter-submit-area filter-submit-area-production">
                <button type="submit" class="btn-filter">FILTRAR</button>
            </div>
        </form>
    </div>

    {{-- CAMPANHAS gerenciadas (tabela principal) --}}
    <div class="copy-production-section glass-card table-shadow">
        <h3 class="section-title">Campanhas Gerenciadas por Gestor</h3>

        <div class="table-responsive">
            <table class="metrics-main-table">
                <thead>
                    <tr>
                        <th class="header-editor">Gestor</th>
                        <th class="header-metrics">Campanhas</th> 
                        <th class="header-metrics">Cliques</th>
                        <th class="header-metrics">Conversões</th>
                        <th class="header-metrics">Custo</th>
                        <th class="header-metrics">Lucro</th>
                        {{-- NOVAS MÉTRICAS INSERIDAS --}}
                        <th class="header-metrics">CTR (%)</th> 
                        <th class="header-metrics">CPA</th>
                        {{-- FIM NOVAS MÉTRICAS --}}
                        <th class="header-metrics">ROI (%)</th>
                        <th class="header-action">Detalhes</th>
                    </tr>
                </thead>

                
                <tbody>
                    
                    {{-- EXEMPLO --}}
                    <tr class="editor-row clickable-row" data-editor-id="1" onclick="toggleDetails('gestor-1')">
                        <td class="editor-name-cell">
                            <span class="arrow-indicator"><i class="fas fa-chevron-right"></i></span>
                            <span class="fw-bold">Gestor Ary (teste mockado)</span>
                        </td>
                        <td>5/7</td>
                        <td>12.500</td>
                        <td>850</td>
                        <td>@dollar(5000)</td>
                        <td class="positive-value">@dollar(15000)</td>
                        {{-- MOCK CTR --}}
                        <td class="positive-value">3.50%</td> 
                        {{-- MOCK CPA --}}
                        <td class="negative-value">@dollar(5.88)</td>
                        <td class="positive-value">300.00%</td>
                        
                        <td class="action-cell">
                            <button class="btn-subview-cta" data-name="Gestor Ary (teste mockado)"
                                onclick="event.stopPropagation(); handleGestorModalOpen(this);"
                                title="Ver Sub Visualização 2.0">
                                <i class="fas fa-box-open"></i>
                            </button>
                        </td>
                    </tr>
                    
                    {{-- GESTOR ARY ) --}}
                    <tr id="details-gestor-1" class="details-row" style="display: none;">
                        <td colspan="10" class="details-cell"> 
                            <div class="nested-table-container custom-scrollbar">
                                <h4 class="nested-table-title">Campanhas de Gestor Ary</h4> 
                                <table class="nested-table">
                                    <thead>
                                        <tr>
                                            <th data-sort-key="campaign_code" class="sortable">Campanha <i
                                                class="fas fa-sort"></i></th>
                                            <th data-sort-key="clicks" class="sortable">Cliques <i
                                                class="fas fa-sort"></i></th>
                                            <th data-sort-key="conversions" class="sortable">Conversões <i
                                                class="fas fa-sort"></i></th>
                                            <th data-sort-key="cost" class="sortable">Custo <i
                                                class="fas fa-sort"></i></th>
                                            <th data-sort-key="profit" class="sortable">Lucro <i
                                                class="fas fa-sort"></i></th>
                                            <th data-sort-key="revenue" class="sortable">Receita <i
                                                class="fas fa-sort"></i></th>
                                            {{-- NOVAS METRICAS  --}}
                                            <th data-sort-key="ctr" class="sortable">CTR <i class="fas fa-sort"></i></th>
                                            <th data-sort-key="cpa" class="sortable">CPA <i class="fas fa-sort"></i></th>
                                            {{-- FIM --}}
                                            <th data-sort-key="roi" class="sortable">ROI <i class="fas fa-sort"></i>
                                            </th>
                                            <th class="text-center">Gráfico</th>
                                            <th class="text-center">
                                                <i class="fas fa-box-open" title="Sub Visualização 2.0"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- campanhas testes mockadas --}}
                                        <tr class="creative-detail-row">
                                            <td class="creative-code">Campanha 001</td>
                                            <td>5000</td>
                                            <td>300</td>
                                            <td>@dollar(1500)</td>
                                            <td class="positive-value">@dollar(4500)</td>
                                            <td>@dollar(6000)</td>
                                            <td class="positive-value">6.00%</td>
                                            <td class="negative-value">@dollar(5.00)</td>
                                            <td class="positive-value">300.00%</td>
                                            <td class="text-center">
                                                <button class="btn-chart" onclick="event.stopPropagation(); openCreativeChart('Campanha 001', 'Campanha 001');" title="Ver Gráfico">
                                                    <i class="fas fa-chart-line"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn-subview-cta-item" onclick="event.stopPropagation(); openCreativeSubView('Campanha 001');" title="Ver Sub Visualização da Campanha">
                                                    <i class="fas fa-box-open"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="creative-detail-row">
                                            <td class="creative-code">Campanha 002</td>
                                            <td>7500</td>
                                            <td>500</td>
                                            <td>@dollar(3000)</td>
                                            <td class="positive-value">@dollar(10500)</td>
                                            <td>@dollar(13500)</td>
                                            <td class="positive-value">6.67%</td>
                                            <td class="negative-value">@dollar(6.00)</td>
                                            <td class="positive-value">350.00%</td>
                                            <td class="text-center">
                                                <button class="btn-chart" onclick="event.stopPropagation(); openCreativeChart('Campanha 002', 'Campanha 002');" title="Ver Gráfico">
                                                    <i class="fas fa-chart-line"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn-subview-cta-item" onclick="event.stopPropagation(); openCreativeSubView('Campanha 002');" title="Ver Sub Visualização da Campanha">
                                                    <i class="fas fa-box-open"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="no-data-message" style="display:none;">Nenhuma campanha encontrada para este gestor neste período.</p>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- campanhas testes mockadas 2 --}}
                    <tr class="editor-row clickable-row" data-editor-id="2" onclick="toggleDetails('gestor-2')">
                        <td class="editor-name-cell">
                            <span class="arrow-indicator"><i class="fas fa-chevron-right"></i></span>
                            <span class="fw-bold">Gestor Hoberlan (teste mockado)</span>
                        </td>
                        <td>3/4</td>
                        <td>8.000</td>
                        <td>400</td>
                        <td>@dollar(4000)</td>
                        <td class="negative-value">@dollar(-1000)</td>
                        {{-- MOCK CTR --}}
                        <td class="positive-value">4.00%</td>
                        {{-- MOCK CPA --}}
                        <td class="negative-value">@dollar(10.00)</td>
                        <td class="negative-value">-25.00%</td>
                        <td class="action-cell">
                            <button class="btn-subview-cta" data-name="Gestor B (Mock)"
                                onclick="event.stopPropagation(); handleGestorModalOpen(this);"
                                title="Ver Sub Visualização 2.0">
                                <i class="fas fa-box-open"></i>
                            </button>
                        </td>
                    </tr>
                    <tr id="details-gestor-2" class="details-row" style="display: none;">
                        <td colspan="10" class="details-cell">
                             <p class="no-data-message">Nenhuma campanha encontrada para este gestor neste período.</p>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    
    
    {{-- PARTE DE SUB VISUALIZACAO 2.0 --}}

    {{-- MODAL DO GRAFICO DE CAMPANHA  --}}
    <div id="campaignChartModalGestor" class="creative-modal" style="display: none;"> {{-- ID alterado --}}
        <div class="creative-modal-content">
            <div class="creative-modal-header">
                <h3 id="chartCampaignTitleGestor">Gráfico da Campanha</h3> {{-- tutulo alterado --}}
                <div class="chart-controls">
                    <button id="toggleChartTypeBtnGestor" class="btn-chart-control" title="Alternar para Barras"> {{-- ID alterado --}}
                        <i class="fas fa-chart-bar"></i>
                    </button>
                    <button id="refreshChartBtnGestor" class="btn-chart-control" title="Atualizar Dados"> {{-- ID alterado --}}
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
                <button class="creative-modal-close" onclick="closeCampaignChartGestor()">×</button> {{-- troca de funcoes--}}
            </div>
            <div class="creative-chart-wrapper">
                <canvas id="campaignChartGestor"></canvas> {{-- ID alterado --}}
            </div>
        </div>
    </div>

    

    {{-- SUB VISUALIZACAO 2.0 DETALHES DO GESTOR  --}}
    <div id="gestorDetailsModal" class="creative-modal subview-modal" style="display: none;"> {{-- ID alterado --}}
        <div class="creative-modal-content subview-modal-content">

            <div class="creative-modal-header">
                <h3 id="gestorDetailsTitle" class="main-title-hidden">Detalhes do Gestor: [Nome]</h3> {{-- titulo alterado --}}
                <button class="creative-modal-close" onclick="closeGestorDetailsModal()">×</button> {{-- funcao alterada --}}
            </div>

            <div class="subview-body-container">

                {{-- CARD DE INFORMACOES DO GESTOR --}}
                <div class="editor-info-card glass-card">
                    <div class="editor-info-header">
                        <h3 id="gestorNameTitle" class="editor-name-title">Nome do Gestor</h3> 
                        <p id="gestorRoleEmail" class="editor-role-email">Função: Gestor de Tráfego | Email: email@titan.com</p> 
                    </div>
                </div>

                {{-- CARDS DE TOTAL, CLIQUES, LUCRO, ROI + CTR/CPA  --}}
                <div class="details-cards-grid">
                    <div class="metric-card glass-card card-creatives">
                        <span class="card-icon"><i class="fas fa-rocket"></i></span> {{-- icone alterado para refletir campanha de trafego--}}
                        <p class="card-title">Total de Campanhas</p> 
                        <h4 id="cardTotalCampaigns" class="card-value">0</h4> {{-- ID alterado --}}
                    </div>
                    <div class="metric-card glass-card card-clicks">
                        <span class="card-icon"><i class="fas fa-hand-pointer"></i></span>
                        <p class="card-title">Total de Cliques</p>
                        <h4 id="cardTotalClicksGestor" class="card-value">0</h4> {{-- ID alterado --}}
                    </div>
                    
                    {{-- NOVO CARD  CTR MEDIO --}}
                    <div class="metric-card glass-card card-ctr">
                        <span class="card-icon"><i class="fas fa-mouse-pointer"></i></span>
                        <p class="card-title">CTR Médio</p>
                        <h4 id="cardAverageCTR" class="card-value">0.00%</h4>
                    </div>
                    
                    {{-- NOVO CARD  CPA MEDIO --}}
                    <div class="metric-card glass-card card-cpa">
                        <span class="card-icon"><i class="fas fa-dollar-sign"></i></span>
                        <p class="card-title">CPA Médio</p>
                        <h4 id="cardAverageCPA" class="card-value"> $ 0,00</h4>
                    </div>

                    <div class="metric-card glass-card card-profit">
                        <span class="card-icon"><i class="fas fa-coins"></i></span>
                        <p class="card-title">Lucro Total</p>
                        <h4 id="cardTotalProfitGestor" class="card-value">R$ 0,00</h4> {{-- ID alterado --}}
                    </div>
                    <div class="metric-card glass-card card-roi">
                        <span class="card-icon"><i class="fas fa-chart-line"></i></span>
                        <p class="card-title">ROI Médio</p>
                        <h4 id="cardAverageROIGestor" class="card-value">0.00%</h4> {{-- ID alterado --}}
                    </div>
                </div>

                {{-- GRAFICO DIARIO IGUAL --}}
                <div class="editor-chart-section glass-card">
                    <div class="editor-chart-header">
                        <h4 class="chart-section-title">Performance Diária (Lucro vs Custo) - Por Gestor</h4> 
                        <div class="chart-controls">
                            <button id="toggleDailyChartTypeBtnGestor" class="btn-chart-control" 
                                title="Alternar para Linhas">
                                <i class="fas fa-chart-bar"></i>
                            </button>
                            <button id="refreshDailyChartBtnGestor" class="btn-chart-control" title="Atualizar Dados"> 
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="creative-chart-wrapper">
                        <canvas id="gestorDailyChart"></canvas> 
                    </div>
                </div>

                {{-- ABA DE NICHO IGUAL IGUAL A COPY/EDITORS --}}
                <div class="niche-data-section glass-card">
                    <h4 class="chart-section-title">Análise de Performance por Nicho</h4>

                    <div class="niche-tabs">
                        <button class="tab-button active" onclick="switchNicheViewGestor('table')"> 
                            <i class="fas fa-table"></i> Tabela
                        </button>
                        <button class="tab-button" onclick="switchNicheViewGestor('chart')"> 
                            <i class="fas fa-chart-bar"></i> Gráfico
                        </button>
                    </div>

                    <div id="nicheTableViewGestor" class="niche-content-view active custom-scrollbar" 
                        style="max-height: 400px; overflow-y: auto; margin-top: 15px;">
                        <table id="detailsNicheTableGestor" class="nested-table details-subview-table"> 
                            <thead>
                                <tr>
                                    <th data-sort-key="niche_name" class="sortable details-sortable">Nicho <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="total_campaigns" class="sortable details-sortable">Campanhas <i 
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="clicks" class="sortable details-sortable">Cliques <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="conversions" class="sortable details-sortable">Conv. <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="cost" class="sortable details-sortable">Custo <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="profit" class="sortable details-sortable">Lucro <i
                                            class="fas fa-sort"></i></th>
                                    {{-- INCLUSAO DE CTR E CPA NA TABELA DE NICHO --}}
                                    <th data-sort-key="ctr" class="sortable details-sortable">CTR <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="cpa" class="sortable details-sortable">CPA <i
                                            class="fas fa-sort"></i></th>
                                    {{-- FIM --}}
                                    <th data-sort-key="roi" class="sortable details-sortable">ROI <i
                                            class="fas fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="detailsNicheTableBodyGestor"> 
                                {{-- DADOS VIA JS --}}
                            </tbody>
                        </table>
                        <p id="nicheNoDataMessageGestor" class="no-data-message" 
                            style="display: none; text-align: center;">Nenhum dado de nicho encontrado.</p>
                    </div>

                    <div id="nicheChartViewGestor" class="niche-content-view" style="display: none; margin-top: 15px;"> 
                        <div class="creative-chart-wrapper" style="height: 350px;">
                            <canvas id="gestorNicheChart"></canvas> 
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Logica de Interacao JavaScript adaptada para Gestores --}}
    <script>
        // variaveis globais de cores IGUAL
        const COLOR_PRIMARY_AZUL = '#0f53ff';
        const COLOR_SUCCESS_VERDE = '#4ADE80';
        const COLOR_DANGER_VERMELHO = '#F87171';

        // FUNCAO IGUAL
        function formatCurrency(value) {
            return new Intl.NumberFormat('en', { 
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 2,
            }).format(value);
        }

        function formatRoi(value) {
            return `${(value * 100).toFixed(2).replace('.', ',')}%`;
        }
        
        // formata CTR (como porcentagem)
        function formatCtr(value) {
            return `${(value * 100).toFixed(2).replace('.', ',')}%`;
        }


        // TABELA PRINCIPAL (gestores)

        // detalhes In-line por GESTOR, ajustada para 'gestor-'
        function toggleDetails(key) {
            const row = document.getElementById(`details-${key}`);
            // procurar pelo ID do usuario no atributo data-editor-id
            const gestorRow = document.querySelector(`tr.clickable-row[data-editor-id="${key.replace('gestor-', '')}"]`); // ajustado
            const icon = gestorRow.querySelector('.arrow-indicator i');

            if (row.style.display === "none") {
                // fechar outros se estiver expandido
                document.querySelectorAll('.details-row').forEach(r => {
                    if (r.id !== `details-${key}` && r.style.display !== "none") {
                        r.style.display = "none";
                        const otherKey = r.id.replace('details-', '');
                        const otherGestorRow = document.querySelector(
                            `tr.clickable-row[data-editor-id="${otherKey.replace('gestor-', '')}"]`); // ajustado
                        if (otherGestorRow) {
                            otherGestorRow.classList.remove('details-expanded');
                            otherGestorRow.querySelector('.arrow-indicator i').classList.remove('fa-chevron-down');
                            otherGestorRow.querySelector('.arrow-indicator i').classList.add('fa-chevron-right');
                        }
                    }
                });

                row.style.display = "table-row";
                gestorRow.classList.add('details-expanded');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                row.style.display = "none";
                gestorRow.classList.remove('details-expanded');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }

        // FUNCAO PARA SUB VISUALIZACAO DE CAMPANHA (ANTIGO CRIATIVO/COPY) ainda um placeholder
        function openCampaignSubView(campaignCode) { 
            alert(`Abrir Sub Visualização 2.0 para a Campanha: ${campaignCode}. (a ser implementado, ainda)`); 
        }


        // VARIAVEIS E FUNCOES DO MODAL SUB VISUALIZACAO DO GESTOR (ANTIGO COPY)

        let currentGestorData = []; // dados do gestor atual 
        let gestorDetailsChart = null; 
        let gestorNicheChart = null; 
        let gestorDailyChartType = 'line'; // estado do grafico diario  padrao line 

        // fechar modal (ADAPTADA)
        function closeGestorDetailsModal() { 
            document.getElementById("gestorDetailsModal").style.display = "none"; 

            if (gestorDetailsChart) { 
                gestorDetailsChart.destroy(); 
                gestorDetailsChart = null; 
            }
            if (gestorNicheChart) { 
                gestorNicheChart.destroy(); 
                gestorNicheChart = null; 
            }
            gestorDailyChartType = 'line'; 
        }

        // desenhar grafico diario ADAPTADA para gestor
        function drawGestorDailyChart(data, chartType = 'line') { 

            if (gestorDetailsChart) { 
                gestorDetailsChart.destroy(); 
            }

            const ctx = document.getElementById("gestorDailyChart").getContext("2d"); 

            // labels e dados (utilizei Creative Code, pois nao temos dados diarios simulados, ainda)
            console.log(data);

            const labels = data.map(item => item.code.substring(0, 10) + '...');
            const profitData = data.map(item => item.total_profit);
            const costData = data.map(item => item.total_cost);

            gestorDetailsChart = new Chart(ctx, { // alterado
                type: chartType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Lucro",
                        data: profitData,
                        borderColor: chartType === 'line' ? COLOR_SUCCESS_VERDE : 'transparent',
                        backgroundColor: chartType === 'line' ? `rgba(74,222,128,0.15)` :
                            COLOR_SUCCESS_VERDE,
                        fill: chartType === 'line',
                        tension: chartType === 'line' ? 0.35 : 0,
                        borderWidth: 2,
                        pointRadius: chartType === 'line' ? 4 : 0,
                        yAxisID: 'y'
                    }, {
                        label: "Custo",
                        data: costData,
                        borderColor: chartType === 'line' ? COLOR_DANGER_VERMELHO : 'transparent',
                        backgroundColor: chartType === 'line' ? `rgba(248,113,113,0.15)` :
                            COLOR_DANGER_VERMELHO,
                        fill: chartType === 'line',
                        tension: chartType === 'line' ? 0.35 : 0,
                        borderWidth: 2,
                        pointRadius: chartType === 'line' ? 4 : 0,
                        yAxisID: 'y'
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'x',
                    plugins: {
                        legend: {
                            labels: {
                                color: "#ddd"
                            }
                        },
                        tooltip: {
                            backgroundColor: "rgba(10, 10, 10, 0.95)",
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += formatCurrency(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            ticks: {
                                color: "#aaa"
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            stacked: false,
                            ticks: {
                                color: "#aaa",
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            },
                            grid: {
                                color: "rgba(255,255,255,0.08)"
                            }
                        }
                    }
                }
            });

            // BOTAO DE ALTERNAR 
            const toggleBtn = document.getElementById('toggleDailyChartTypeBtnGestor'); 
            if (chartType === 'line') {
                toggleBtn.innerHTML = '<i class="fas fa-chart-bar"></i>';
                toggleBtn.title = "Alternar para Colunas";
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-chart-line"></i>';
                toggleBtn.title = "Alternar para Linhas";
            }
        }

        // ALTERNAR TIPO DE GRAFICO DIARIO, LINE OU BAR
        function toggleGestorDailyChartType() { 
            gestorDailyChartType = gestorDailyChartType === 'line' ? 'bar' : 'line'; 
            drawGestorDailyChart(currentGestorData, gestorDailyChartType);
        }

        // RECARREGAR O GRAFICO DIARIO
        function refreshGestorDailyChart() { 
            // por enquanto, apenas redesenha com os dados atuais
            drawGestorDailyChart(currentGestorData, gestorDailyChartType); 
            alert(
                "Gráfico de Performance Diária atualizado (redesenhado com dados existentes, pois precisa atualizar a logica)."
            );
        }

        // AGRUPA DADOS POR NICHO (INCLUINDO CTR E CPA)
        function groupDataByNiche(data) {
            const nicheMap = {};

            data.forEach(item => {
                const niche = item.nicho_name ?? 'Não definido';
                const clicks = Number(item.total_clicks) || 0;
                const conversions = Number(item.total_conversions) || 0;
                const cost = Number(item.total_cost) || 0;
                const profit = Number(item.total_profit) || 0;

                if (!nicheMap[niche]) {
                    nicheMap[niche] = {
                        niche_name: niche,
                        total_campaigns: 0, // alterado de total_copies
                        clicks: 0,
                        conversions: 0,
                        cost: 0,
                        profit: 0,
                        roi: 0,
                        ctr: 0, // NOVO
                        cpa: 0, // NOVO
                    };
                }

                nicheMap[niche].total_campaigns += 1; // alterado
                nicheMap[niche].clicks += clicks;
                nicheMap[niche].conversions += conversions;
                nicheMap[niche].cost += cost;
                nicheMap[niche].profit += profit;
            });

            // calcular metricas finais de cada nicho (ROI, CTR, CPA)
            Object.keys(nicheMap).forEach(key => {
                const n = nicheMap[key];
                
                // ROI = Lucro / Custo
                n.roi = n.cost > 0 ? n.profit / n.cost : 0;
                
                // CTR = Cliques / impressoes (assumindo que "impressions" vira em 'item')
                // CTR sera apenas um placeholder
                // assumindo uma taxa base para nao quebrar
                n.ctr = n.clicks > 0 ? (n.conversions / n.clicks) * 0.5 : 0; // Placeholder: Conversoes/Cliques * 0.5 

                // CPA = Custo / Conversoes
                n.cpa = n.conversions > 0 ? n.cost / n.conversions : 0;
            });

            return Object.values(nicheMap);
        }


        // PREENCHER A TABELA DE NICHO 
        function fillNicheTableGestor(nicheData) { 
            const tableBody = document.getElementById('detailsNicheTableBodyGestor'); 
            const noDataMessage = document.getElementById('nicheNoDataMessageGestor'); 
            tableBody.innerHTML = '';

            if (!nicheData.length) {
                noDataMessage.style.display = 'block';
                return;
            }

            noDataMessage.style.display = 'none';

            nicheData.forEach(item => {
                const profitClass = item.profit >= 0 ? 'positive-value' : 'negative-value';
                const roiClass = item.roi >= 0 ? 'positive-value' : 'negative-value';

                const row = document.createElement('tr');
                row.className = 'niche-detail-row';

                row.innerHTML = `
            <td class="niche-name">${item.niche_name}</td>
            <td>${item.total_campaigns}</td>
            <td>${item.clicks.toLocaleString('pt-BR')}</td>
            <td>${item.conversions.toLocaleString('pt-BR')}</td>
            <td>${formatCurrency(item.cost)}</td>
            <td class="${profitClass}">${formatCurrency(item.profit)}</td>
            <td class="default-value">${formatCtr(item.ctr)}</td>
            <td class="default-value">${formatCurrency(item.cpa)}</td>
            <td class="${roiClass}">${formatRoi(item.roi)}</td>
        `;

                tableBody.appendChild(row);
            });

            addNicheTableSortingGestor(); // tera um funcao de ordenacao
        }
        
        // NOVO SE QUISER REMOVER PODE funcao Switch de Nicho (tabela/grafico)
        function switchNicheViewGestor(view) {
            const tableView = document.getElementById('nicheTableViewGestor');
            const chartView = document.getElementById('nicheChartViewGestor');
            const tabs = document.querySelectorAll('.niche-tabs .tab-button');

            tabs.forEach(tab => tab.classList.remove('active'));
            
            if (view === 'table') {
                tableView.style.display = 'block';
                chartView.style.display = 'none';
                document.querySelector('.niche-tabs button:nth-child(1)').classList.add('active');
            } else {
                tableView.style.display = 'none';
                chartView.style.display = 'block';
                document.querySelector('.niche-tabs button:nth-child(2)').classList.add('active');
            }
        }


        // DESENHA GRAFICO DE NICHO PARA GESTOR, ARRUMADO
        function drawGestorNicheChart(nicheData) { 
            if (gestorNicheChart) { 
                gestorNicheChart.destroy(); 
            }

            const ctx = document.getElementById("gestorNicheChart").getContext("2d"); 

            const labels = nicheData.map(item => item.niche_name);
            const profitData = nicheData.map(item => item.profit);
            const costData = nicheData.map(item => item.cost);

            gestorNicheChart = new Chart(ctx, { 
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Lucro",
                        data: profitData,
                        backgroundColor: COLOR_SUCCESS_VERDE,
                        yAxisID: 'y'
                    }, {
                        label: "Custo",
                        data: costData,
                        backgroundColor: COLOR_DANGER_VERMELHO,
                        yAxisID: 'y'
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'x',
                    plugins: {
                        legend: {
                            labels: {
                                color: "#ddd"
                            }
                        },
                        tooltip: {
                            backgroundColor: "rgba(10, 10, 10, 0.95)",
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += formatCurrency(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            ticks: {
                                color: "#aaa"
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            stacked: false,
                            ticks: {
                                color: "#aaa",
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            },
                            grid: {
                                color: "rgba(255,255,255,0.08)"
                            }
                        }
                    }
                }
            });
        }
        
        // ordenação de tabela de Nicho 
        function addNicheTableSortingGestor() {
            // a ser implementado.
        }

        // ALTERNAR VISAO DE NICHO, ARRUMADO PARA GESTOR
        function switchNicheViewGestor(viewType) {
            document.querySelectorAll('.niche-content-view').forEach(view => {
                view.style.display = 'none';
                view.classList.remove('active');
            });

            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            document.getElementById(`niche${viewType.charAt(0).toUpperCase() + viewType.slice(1)}ViewGestor`).style.display =
                'block';
            document.getElementById(`niche${viewType.charAt(0).toUpperCase() + viewType.slice(1)}ViewGestor`).classList.add(
                'active');

            // CLASS ATIVA DO CTA/BOTAO ATUALIZADA
            const tabButtons = document.querySelectorAll('.niche-tabs .tab-button');
            if (viewType === 'table' && tabButtons[0]) {
                tabButtons[0].classList.add('active');
            } else if (viewType === 'chart' && tabButtons[1]) {
                tabButtons[1].classList.add('active');
            }


            // se for para o grafico garante que ele seja desenhado/redesenhado (ADAPTADO PARA GESTOR)
            if (viewType === 'chart' && currentGestorData.length > 0) {
                const nicheData = groupDataByNiche(currentGestorData);
                drawGestorNicheChart(nicheData);
            }
        }

        // ORDENACAO para tabela e tabela de nicho (MANTIDA mas ID dinamico para gestor)
        let currentSortKey = '';
        let currentSortDirection = 'asc';

        function sortDetailsTable(key, tableBodyId) {
            const tableBody = document.getElementById(tableBodyId);
            const rows = Array.from(tableBody.querySelectorAll('tr'));
            const tableElement = tableBody.closest('table');
            const header = tableElement.querySelector(`th[data-sort-key="${key}"]`);

            let direction;

            if (key === currentSortKey) {
                direction = currentSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                direction = 'asc';
            }

            currentSortKey = key;
            currentSortDirection = direction;

            // RESETA INCONES DE ORDENACAO, MAS APENAS PARA A TABELA SENDO ORDENADA
            tableElement.querySelectorAll('.details-sortable i, .sortable i').forEach(icon => {
                icon.className = 'fas fa-sort';
            });

            const icon = header.querySelector('i');
            icon.className = direction === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';

            rows.sort((a, b) => {
                const aValRaw = a.querySelector(
                    `td:nth-child(${Array.from(header.parentNode.children).indexOf(header) + 1})`).innerText;
                const bValRaw = b.querySelector(
                    `td:nth-child(${Array.from(header.parentNode.children).indexOf(header) + 1})`).innerText;

                let aVal, bVal;

                // trata a conversao de valores para ordenacao numerica
                if (key !== 'niche_name' && key !== 'creative_code') {
                    aVal = parseFloat(aValRaw.replace(/[^0-9,-]/g, '').replace(',', '.'));
                    bVal = parseFloat(bValRaw.replace(/[^0-9,-]/g, '').replace(',', '.'));
                } else {
                    aVal = aValRaw.toLowerCase();
                    bVal = bValRaw.toLowerCase();
                }

                let comparison = 0;

                if (aVal < bVal) {
                    comparison = -1;
                }
                if (aVal > bVal) {
                    comparison = 1;
                }

                return direction === 'asc' ? comparison : comparison * -1;
            });

            rows.forEach(row => tableBody.appendChild(row));
        }

        // FUNCOES DE ORDENACAO ESPECIFICA 
        function sortNicheTableGestor(key) {
            sortDetailsTable(key, 'detailsNicheTableBodyGestor');
        }

        function sortInlineCreativesTable(key) {
            // a tabela aninhada in-line nao tem um ID especifico no body mas podemos encontra-lo
            // o body da tabela in-line é o unico tbody dentro da tr.details-row visivel
            const visibleDetailsRow = document.querySelector('.details-row[style*="table-row"]');
            if (visibleDetailsRow) {
                const tableBody = visibleDetailsRow.querySelector('.nested-table tbody');
                if (tableBody) {
                    sortDetailsTable(key, tableBody.id || 'temp-inline-table-body'); // passa um ID temporario se nao tiver
                    // utliza o método sortDetailsTable usa `querySelector` baseado no header para encontrar a coluna
                    // e nao e dependente do ID do tbody se o header for encontrado no contexto correto
                }
            }
        }

        // listener de ordenacao para a tabela de nichos no modal (ADAPTADA para GESTOR)
        function addNicheTableSortingGestor() {
            document.querySelectorAll('#detailsNicheTableGestor .details-sortable').forEach(header => {
                // remove listeners antigos para evitar duplicacao (clona e substitui)
                header.replaceWith(header.cloneNode(true));
            });

            document.querySelectorAll('#detailsNicheTableGestor .details-sortable').forEach(header => {
                header.addEventListener('click', () => {
                    sortNicheTableGestor(header.dataset.sortKey);
                });
            });
        }

        // adiciona o listener de ordenacao para a tabela de criativos aninhada (ADAPTADA para GESTOR)
        function addInlineCreativesSorting() {
            document.querySelectorAll('.nested-table:not(#detailsNicheTableGestor) th.sortable').forEach(header => {
                // remove listeners antigos
                header.replaceWith(header.cloneNode(true));
            });

            document.querySelectorAll('.nested-table:not(#detailsNicheTableGestor) th.sortable').forEach(header => {
                header.addEventListener('click', function() {
                    // a ordenacao da tabela in-line agora usa a funcao centralizada para tudo
                    sortInlineCreativesTable(header.dataset.sortKey);
                });
            });
        }


        // ABRIR MODAL DE DETALHES DO GESTOR, ARRUMADA
        function openGestorDetailsModal(gestorName, clicks, copies, profit, roi, gestorEmail, creativesJson) {

            document.getElementById("gestorDetailsModal").style.display = "flex";
            document.getElementById("gestorNameTitle").innerText = gestorName;

            document.getElementById("gestorRoleEmail").innerHTML =
                `Função: Gestor | Email: <b>${gestorEmail}</b>`;
            document.getElementById('cardTotalClicksGestor').innerHTML = clicks
            document.getElementById('cardTotalProfitGestor').innerHTML = profit
            document.getElementById('cardTotalCampaigns').innerHTML = copies // alterado para cardTotalCampaigns
            document.getElementById('cardAverageROIGestor').innerHTML = roi
            try {
                currentGestorData = JSON.parse(creativesJson);
            } catch (e) {
                console.error("Erro ao parsear JSON:", e);
                currentGestorData = [];
            }

            drawGestorDailyChart(currentGestorData, gestorDailyChartType);

            const nicheData = groupDataByNiche(currentGestorData);
            fillNicheTableGestor(nicheData);

            switchNicheViewGestor('table');
        }



        // FUNCOES DO MODAL DE GRAFICO DO CRIATIVO (creativeChartModalGestor) 

        window.creativeChartGestor = null; // ADAPTADA VARIAVEL GLOBAL
        let currentCreativeCodeGestor = null;
        let currentChartTypeGestor = 'line';

        // fecha o grafico de criativo (ADAPTADA PARA GESTOR)
        function closeCampaignChartGestor() { // alterado nome da funcao
            document.getElementById("campaignChartModalGestor").style.display = "none"; // alterado ID
        }

        // BUSCA DADOS POR GRAFICO, AJUSTADA
        async function fetchChartDataGestor(creativeCode) { 
            document.getElementById("chartCampaignTitleGestor").innerText = 
                `${creativeCode}`;

            try {
                const response = await fetch(`/admin/creative-history?creative=${creativeCode}`);

                if (!response.ok) {
                    throw new Error("Erro ao buscar dados do servidor.");
                }

                const data = await response.json();

                // converter ROI para numero e porcentagem (%)
                return data.map(item => ({
                    date: item.date,
                    profit: parseFloat(item.profit),
                    cost: parseFloat(item.cost),
                    roi: item.cost > 0 ? (item.profit / item.cost) : 0
                }));

            } catch (error) {
                console.error(error);
                alert("Erro ao carregar dados do criativo.");
                return [];
            }
        }


        // DESENHA O GRAFICO DE CRIATIVO ARRUMADA
        function drawCampaignChartGestor(data, chartType) { 
            const labels = data.map(item => item.date);
            const profitData = data.map(item => item.profit);
            const costData = data.map(item => item.cost);
            const roiData = data.map(item => item.roi);

            const ctx = document.getElementById("campaignChartGestor").getContext("2d"); 

            if (window.creativeChartGestor instanceof Chart) { 
                window.creativeChartGestor.destroy(); 
            }

            window.creativeChartGestor = new Chart(ctx, { 
                type: chartType,
                data: {
                    labels: labels,
                    datasets: [{
                            label: "Lucro (R$)",
                            data: profitData,
                            borderColor: chartType === 'line' ? COLOR_SUCCESS_VERDE : 'transparent',
                            backgroundColor: chartType === 'line' ?
                                "rgba(74,222,128,0.15)" : COLOR_SUCCESS_VERDE,
                            fill: chartType === 'line',
                            tension: chartType === 'line' ? 0.35 : 0,
                            borderWidth: 2,
                            pointRadius: chartType === 'line' ? 4 : 0,
                            yAxisID: 'y'
                        },
                        {
                            label: "Custo (R$)",
                            data: costData,
                            borderColor: chartType === 'line' ? COLOR_DANGER_VERMELHO : 'transparent',
                            backgroundColor: chartType === 'line' ?
                                "rgba(248,113,113,0.15)" : COLOR_DANGER_VERMELHO,
                            fill: chartType === 'line',
                            tension: chartType === 'line' ? 0.35 : 0,
                            borderWidth: 2,
                            pointRadius: chartType === 'line' ? 4 : 0,
                            yAxisID: 'y' // MESMO EIXO
                        },
                        {
                            label: "ROI (%)",
                            data: roiData,
                            borderColor: "#3B82F6",
                            backgroundColor: "transparent",
                            borderWidth: 2,
                            pointRadius: 3,
                            tension: 0.35,
                            yAxisID: 'roi'
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: "#ddd",
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: "rgba(10, 10, 10, 0.95)",
                            titleColor: "#fff",
                            bodyColor: "#fff",
                            borderColor: COLOR_PRIMARY_AZUL,
                            borderWidth: 1,
                            cornerRadius: 6,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label + ': ';
                                    if (context.dataset.yAxisID === 'roi') {
                                        return label + (context.raw * 100).toFixed(2) + '%';
                                    }
                                    return label + formatCurrency(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: "#aaa",
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: {
                                color: "rgba(255,255,255,0.08)"
                            }
                        },

                        // 📌 ESCALA UNICA PARA CUSTO E LUCRO
                        y: {
                            type: 'linear',
                            position: 'left',
                            ticks: {
                                color: "#aaa",
                                callback: value => formatCurrency(value)
                            },
                            grid: {
                                color: "rgba(255,255,255,0.08)"
                            },
                            title: {
                                display: true,
                                text: 'Custo / Lucro (R$)',
                                color: "#ccc",
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            }
                        },

                        // 📌 ROI (%) – eixo separado
                        roi: {
                            type: 'linear',
                            position: 'right',
                            min: -1,
                            ticks: {
                                color: "#4aa8ff",
                                callback: value => (value * 100).toFixed(0) + '%'
                            },
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'ROI (%)',
                                color: "#4aa8ff"
                            }
                        }
                    }
                }
            });

            // Atualiza icone do botão de alternancia
            const toggleBtn = document.getElementById('toggleChartTypeBtnGestor'); 
            if (chartType === 'line') {
                toggleBtn.innerHTML = '<i class="fas fa-chart-bar"></i>';
                toggleBtn.title = "Alternar para Colunas";
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-chart-line"></i>';
                toggleBtn.title = "Alternar para Linhas";
            }
        }

        // ABRIR O GRAFICO DE CRIATIVOS ARRUMADA
        async function openCreativeChart(creativeCode, creativeTitle) {
            currentCreativeCodeManager = creativeCode; 
            document.getElementById("campaignChartModalGestor").style.display = "flex"; 
            document.getElementById("chartCampaignTitleGestor").innerText = creativeTitle; 

            const data = await fetchChartDataManager(creativeCode); 
            if (data.length > 0) {
                drawCampaignChartGestor(data, currentChartTypeManager); 
            } else {
                if (window.creativeChartManager instanceof Chart) { 
                    window.creativeChartManager.destroy(); 
                }
                document.getElementById("chartCampaignTitleGestor").innerText = 
                    `${creativeTitle} (Sem dados no período ainda)`;
            }
        }

        // listeners de inicializacao
        window.onload = () => {
            // inicializa a ordenacao das tabelas aninhadas in-line (tabela de criativos)
            addInlineCreativesSorting();

            // listeners para o grafico diario (ADAPTADOS para gestores)
            document.getElementById('toggleDailyChartTypeBtnGestor').addEventListener('click', toggleGestorDailyChartType); // funcao alterada
            document.getElementById('refreshDailyChartBtnGestor').addEventListener('click', refreshGestorDailyChart); // funcao alterada

            // listeners para o grafico de criativo (ADAPTADOS para gestores)
            document.getElementById('refreshChartBtnGestor').addEventListener('click', async () => { // ID alterado
                if (currentCreativeCodeManager) { // variavel Alterada
                    const data = await fetchChartDataManager(currentCreativeCodeManager); // funcao e variavel
                    drawCampaignChartGestor(data, currentChartTypeManager); // funcao e variavel
                }
            });

            document.getElementById('toggleChartTypeBtnGestor').addEventListener('click', async () => { 
                currentChartTypeManager = currentChartTypeManager === 'line' ? 'bar' : 'line'; 

                if (currentCreativeCodeManager) { 
                    const data = await fetchChartDataManager(currentCreativeCodeManager); 
                    drawCampaignChartGestor(data, currentChartTypeManager); 
                }
            });

            // garante que o estado inicial da visualizacao de nicho esteja na tabela
            switchNicheViewManager('table');
        };
    </script>
    <script>
        function handleGestorModalOpen(button) {
            const name = button.dataset.name;
            // MOCKANDO OS DADOS, MAS VIRAM TODOS DA INTEGRACAO DO FACE
            const email = 'email@titan.com'; // placeholder
            const json = '[]'; // Placeholder, deve vir do botao
            const copies = '0'; // Placeholder
            const clicks = '0'; // Placeholder
            const profit = '0'; // Placeholder
            const roi = '0'; // Placeholder

            // na integracao com o face provavelmente segue essa linha ai dos codigos copy/editors
            // const email = button.dataset.email;
            // const json = button.dataset.json;
            // const copies = button.dataset.copies;
            // const clicks = button.dataset.clicks;
            // const profit = button.dataset.profit;
            // const roi = button.dataset.roi;

            openGestorDetailsModal(name, clicks, copies, profit, roi, email, json);

        }
    </script>

    {{-- biblioteca Chart.js - GRAFICO JA FEITO - APENAS SO REPLIQUEI --}}
    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endpush
    @endonce



</x-layout>