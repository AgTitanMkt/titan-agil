<x-layout>

    {{-- CSS PARA TER CERTEZA QUE TA FUNCIONANDO --}}
    <link rel="stylesheet" href="{{ asset('css/admin-editors.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    
    <div class="header-container">
        <h2 class="dashboard-page-title">Produção Editores</h2>
        <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>
    </div>

    {{-- filtros de perfomance --}}
    <div class="production-filters-section glass-card filters-shadow">
        <h3 class="section-title">Filtros de Performance</h3>

        <form class="filters-grid filters-grid-production">
            <div class="filter-group">
                {{-- componente tem que ser definido --}}
                <x-date-range name="date" :from="$startDate" :to="$endDate" label="Intervalo de Datas" />
            </div>
            <div class="filter-group">
                <x-multiselect name="editors" label="Editores" :options="$allEditors" :selected="request('editors', [])"
                    placeholder="Selecione um ou mais editores">
                </x-multiselect>
            </div>

            <div class="filter-submit-area filter-submit-area-production">
                <button type="submit" class="btn-filter">FILTRAR</button>
            </div>
        </form>
    </div>

    {{-- EDICOES Produzidas --}}
    <div class="copy-production-section glass-card table-shadow">
        <h3 class="section-title">Edições Produzidas</h3>

        <div class="table-responsive"> 
            <table class="metrics-main-table">
                <thead>
                    <tr>
                        <th class="header-editor">Editor</th> 
                        <th class="header-metrics">Criativos</th>
                        <th class="header-metrics">Cliques</th>
                        <th class="header-metrics">Conversões</th>
                        <th class="header-metrics">Custo</th>
                        <th class="header-metrics">Lucro</th>
                        <th class="header-metrics">ROI (%)</th>
                        <th class="header-action">Detalhes</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($editors as $editor)
                        @php
                            // SIMULACAO criando um array JSON dos criativos para uso no JS
                            $creativesJson = json_encode($creativesByEditor[$editor->user_id] ?? collect());
                            $key = 'editor-' . $editor->user_id;
                        @endphp
                        
                        {{-- linha principal --}}
                        <tr class="editor-row clickable-row" data-editor-id="{{ $editor->user_id }}" onclick="toggleDetails('{{ $key }}')">
                            <td class="editor-name-cell">
                                <span class="arrow-indicator"><i class="fas fa-chevron-right"></i></span>
                                <span class="fw-bold">{{ $editor->editor_name }}</span>
                            </td>
                            <td>{{ $editor->total_creatives }}</td>
                            <td>{{ $editor->total_clicks }}</td>
                            <td>{{ $editor->total_conversions }}</td>
                            <td>@dollar($editor->total_cost)</td>
                            {{-- lucro com cor condicional --}}
                            <td class="{{ $editor->total_profit >= 0 ? 'positive-value' : 'negative-value' }}">
                                @dollar($editor->total_profit)
                            </td>
                            {{-- ROI com cor condicional --}}
                            <td class="{{ $editor->total_roi >= 0 ? 'positive-value' : 'negative-value' }}">
                                {{ number_format($editor->total_roi * 100, 2, ',', '.') }}%
                            </td>
                            {{-- CTA para a Sub Visualizacao 2.0 (MODAL CENTRALIZADO) --}}
                            <td class="action-cell">
                                <button class="btn-subview-cta" 
                                    onclick="event.stopPropagation(); openEditorDetailsModal('{{ $editor->editor_name }}', '{{ $creativesJson }}');" 
                                    title="Ver Sub Visualização 2.0">
                                    <i class="fas fa-box-open"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- DETALHE DE CADA EDITOR INICIAL  --}}
                        <tr id="details-{{ $key }}" class="details-row" style="display: none;">
                            <td colspan="8" class="details-cell">
                                <div class="nested-table-container custom-scrollbar">
                                    <h4 class="nested-table-title">Criativos de {{ $editor->editor_name }} TESTE </h4>
                                    <table class="nested-table">
                                        <thead>
                                            <tr>
                                                <th data-sort-key="creative_code" class="sortable">Criativo <i class="fas fa-sort"></i></th>
                                                <th data-sort-key="clicks" class="sortable">Cliques <i class="fas fa-sort"></i></th>
                                                <th data-sort-key="conversions" class="sortable">Conversões <i class="fas fa-sort"></i></th>
                                                <th data-sort-key="cost" class="sortable">Custo <i class="fas fa-sort"></i></th>
                                                <th data-sort-key="profit" class="sortable">Lucro <i class="fas fa-sort"></i></th>
                                                <th data-sort-key="revenue" class="sortable">Receita <i class="fas fa-sort"></i></th>
                                                <th data-sort-key="roi" class="sortable">ROI <i class="fas fa-sort"></i></th>
                                                <th class="text-center">Gráfico</th>
                                                <th class="text-center">
                                                    <i class="fas fa-box-open" title="Sub Visualização 2.0"></i>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($creativesByEditor[$editor->user_id] ?? collect() as $cr)
                                                <tr class="creative-detail-row">
                                                    <td class="creative-code">{{ $cr->creative_code }}</td>
                                                    <td>{{ $cr->clicks }}</td>
                                                    <td>{{ $cr->conversions }}</td>
                                                    <td>@dollar($cr->cost)</td>
                                                    <td class="{{ $cr->profit >= 0 ? 'positive-value' : 'negative-value' }}">
                                                        @dollar($cr->profit)
                                                    </td>
                                                    <td>@dollar($cr->revenue)</td>
                                                    <td class="{{ $cr->roi >= 0 ? 'positive-value' : 'negative-value' }}">
                                                        {{ number_format($cr->roi * 100, 2, ',', '.') }}%
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn-chart"
                                                            onclick="event.stopPropagation(); openCreativeChart('{{ $cr->creative_code }}', '{{ $cr->creative_code }}');" title="Ver Gráfico">
                                                            <i class="fas fa-chart-line"></i>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn-subview-cta-item" 
                                                            onclick="event.stopPropagation(); openCreativeSubView('{{ $cr->creative_code }}');" title="Ver Sub Visualização do Criativo">
                                                            <i class="fas fa-box-open"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if (($creativesByEditor[$editor->user_id] ?? collect())->isEmpty())
                                        <p class="no-data-message">Nenhum criativo encontrado para o editor neste período.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- PARTE DE SUB VISUALIZACAO 2.0 --}}

{{-- MODAL DO GRAFICO DE CRIATIVO (MANTIDO) --}}
<div id="creativeChartModal" class="creative-modal" style="display: none;">
    <div class="creative-modal-content">
        <div class="creative-modal-header">
            <h3 id="chartCreativeTitle">Gráfico do Criativo</h3>
            <div class="chart-controls">
                <button id="toggleChartTypeBtn" class="btn-chart-control" title="Alternar para Barras">
                    <i class="fas fa-chart-bar"></i>
                </button>
                <button id="refreshChartBtn" class="btn-chart-control" title="Atualizar Dados">
                    <i class="fas fa-redo"></i>
                </button>
            </div>
            <button class="creative-modal-close" onclick="closeCreativeChart()">×</button>
        </div>
        <div class="creative-chart-wrapper">
            <canvas id="creativeChart"></canvas>
        </div>
    </div>
</div>

{{-- SUB VISUALIZACAO 2.0 DETALHES DO EDITOR  --}}
<div id="editorDetailsModal" class="creative-modal subview-modal" style="display: none;">
    <div class="creative-modal-content subview-modal-content">
        
        <div class="creative-modal-header">
            <h3 id="editorDetailsTitle" class="main-title-hidden">Detalhes do Editor: [Nome]</h3>
            <button class="creative-modal-close" onclick="closeEditorDetailsModal()">×</button>
        </div>

        <div class="subview-body-container">
            
            {{-- CARD DE INFORMACOES DO EDITOR  --}}
            <div class="editor-info-card glass-card">
                <div class="editor-info-header">
                    <h3 id="editorNameTitle" class="editor-name-title">Thamiris Prado Felício Ramos</h3>
                    <p id="editorRoleEmail" class="editor-role-email">Função: Editor | Email: thamiris.prado.felicio.ramos@corp.com</p>
                </div>
            </div>

            {{-- CARD DE DESTAQUE TOTAL, CLIQUES, LUCRO, ROI COM CORES PARA CADA UM  --}}
            <div class="details-cards-grid">
                <div class="metric-card glass-card card-creatives">
                    <span class="card-icon"><i class="fas fa-palette"></i></span>
                    <p class="card-title">Total de Criativos</p>
                    <h4 id="cardTotalCreatives" class="card-value">0</h4>
                </div>
                <div class="metric-card glass-card card-clicks">
                    <span class="card-icon"><i class="fas fa-hand-pointer"></i></span>
                    <p class="card-title">Total de Cliques</p>
                    <h4 id="cardTotalClicks" class="card-value">0</h4>
                </div>
                <div class="metric-card glass-card card-profit">
                    <span class="card-icon"><i class="fas fa-coins"></i></span>
                    <p class="card-title">Lucro Total</p>
                    <h4 id="cardTotalProfit" class="card-value">R$ 0,00</h4>
                </div>
                <div class="metric-card glass-card card-roi">
                    <span class="card-icon"><i class="fas fa-chart-line"></i></span>
                    <p class="card-title">ROI Médio</p>
                    <h4 id="cardAverageROI" class="card-value">0.00%</h4>
                </div>
            </div>

            {{-- GRAFICO DIARIO LINHAS E COLUNAS --}}
            <div class="editor-chart-section glass-card">
                <div class="editor-chart-header">
                    <h4 class="chart-section-title">Performance Diária (Lucro vs Custo) - Por Criativo</h4>
                    <div class="chart-controls">
                        {{-- BOTAO PARA ALTERNAR ENTRE BARRA E LINHA --}}
                        <button id="toggleDailyChartTypeBtn" class="btn-chart-control" title="Alternar para Linhas">
                            <i class="fas fa-chart-bar"></i>
                        </button>
                        {{-- BOTAO PARA ATUALIZAR --}}
                        <button id="refreshDailyChartBtn" class="btn-chart-control" title="Atualizar Dados">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                </div>
                <div class="creative-chart-wrapper">
                    <canvas id="editorDailyChart"></canvas>
                </div>
            </div>

            <script>
                
            </script>
                
                {{-- VISUALIZACAO POR NICHO COM TABS --}}
                <div class="niche-data-section glass-card">
                    <h4 class="chart-section-title">Análise de Performance por Nicho</h4>
                    
                    {{-- tabs de visualizacao --}}
                    <div class="niche-tabs">
                        <button class="tab-button active" onclick="switchNicheView('table')">
                            <i class="fas fa-table"></i> Tabela
                        </button>
                        <button class="tab-button" onclick="switchNicheView('chart')">
                            <i class="fas fa-chart-bar"></i> Gráfico
                        </button>
                    </div>
                    
                    {{-- tabela por nicho --}}
                    <div id="nicheTableView" class="niche-content-view active custom-scrollbar" style="max-height: 400px; overflow-y: auto; margin-top: 15px;">
                        <table id="detailsNicheTable" class="nested-table details-subview-table">
                            <thead>
                                <tr>
                                    <th data-sort-key="niche_name" class="sortable details-sortable">Nicho <i class="fas fa-sort"></i></th>
                                    <th data-sort-key="total_creatives" class="sortable details-sortable">Criativos <i class="fas fa-sort"></i></th>
                                    <th data-sort-key="clicks" class="sortable details-sortable">Cliques <i class="fas fa-sort"></i></th>
                                    <th data-sort-key="conversions" class="sortable details-sortable">Conv. <i class="fas fa-sort"></i></th>
                                    <th data-sort-key="cost" class="sortable details-sortable">Custo <i class="fas fa-sort"></i></th>
                                    <th data-sort-key="profit" class="sortable details-sortable">Lucro <i class="fas fa-sort"></i></th>
                                    <th data-sort-key="roi" class="sortable details-sortable">ROI <i class="fas fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="detailsNicheTableBody">
                                {{-- dados via JS --}}
                            </tbody>
                        </table>
                        <p id="nicheNoDataMessage" class="no-data-message" style="display: none; text-align: center;">Nenhum dado de nicho encontrado.</p>
                    </div>
                    
                    {{-- conteudo do grafico por nicho --}}
                    <div id="nicheChartView" class="niche-content-view" style="display: none; margin-top: 15px;">
                        <div class="creative-chart-wrapper" style="height: 350px;">
                            <canvas id="editorNicheChart"></canvas>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


    {{-- logica de interacao JavaScript - TUDO --}}
    <script>
        // toggleDetails, openCreativeSubView, closeCreativeChart
        function toggleDetails(key) {
            const row = document.getElementById(`details-${key}`);
            const editorRow = document.querySelector(`tr.clickable-row[data-editor-id="${key.replace('editor-', '')}"]`);
            const icon = editorRow.querySelector('.arrow-indicator i');
            
            if (row.style.display === "none") {
                // fechar outros se estiver expandido
                document.querySelectorAll('.details-row').forEach(r => {
                    if (r.id !== `details-${key}` && r.style.display !== "none") {
                        r.style.display = "none";
                        const otherKey = r.id.replace('details-', '');
                        const otherEditorRow = document.querySelector(`tr.clickable-row[data-editor-id="${otherKey.replace('editor-', '')}"]`);
                        if(otherEditorRow) {
                            otherEditorRow.classList.remove('details-expanded');
                            otherEditorRow.querySelector('.arrow-indicator i').classList.remove('fa-chevron-down');
                            otherEditorRow.querySelector('.arrow-indicator i').classList.add('fa-chevron-right');
                        }
                    }
                });

                row.style.display = "table-row";
                editorRow.classList.add('details-expanded'); 
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                row.style.display = "none";
                editorRow.classList.remove('details-expanded'); 
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }
        
        function openCreativeSubView(creativeCode) {
            alert(`Abrir Sub Visualização 2.0 para o Criativo: ${creativeCode}. (a ser implementado)`);
        }

        function closeCreativeChart() {
            document.getElementById("creativeChartModal").style.display = "none";
        }
        
        
        // LOGICA DA SUB VISUALIZACAO 2.0 
        

        let currentEditorData = [];
        let editorDetailsChart = null;
        let editorNicheChart = null; // grafico por nicho
        
        // varival de estado para grafico linhas ou colunas
        let editorDailyChartType = 'line'; 

        function closeEditorDetailsModal() {
            document.getElementById("editorDetailsModal").style.display = "none";
            
            if (editorDetailsChart) {
                editorDetailsChart.destroy();
                editorDetailsChart = null;
            }
            if (editorNicheChart) { // DESTROI O NOVO GRAFICO
                editorNicheChart.destroy();
                editorNicheChart = null;
            }
            // reseta o tipo de grafico ao fechar o modal
            editorDailyChartType = 'line';
        }
        
        // formatCurrency, formatRoi
        function formatCurrency(value) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 2,
            }).format(value);
        }

        function formatRoi(value) {
            return `${(value * 100).toFixed(2).replace('.', ',')}%`;
        }

        // fillSummaryCards
        function fillSummaryCards(data) {
            const totalCreatives = data.length;
            const totalClicks = data.reduce((sum, item) => sum + item.clicks, 0);
            const totalProfit = data.reduce((sum, item) => sum + item.profit, 0);
            const totalCost = data.reduce((sum, item) => sum + item.cost, 0);
            const avgROI = totalCost > 0 ? totalProfit / totalCost : 0;
            
            document.getElementById('cardTotalCreatives').innerText = totalCreatives;
            document.getElementById('cardTotalClicks').innerText = totalClicks.toLocaleString('pt-BR');
            
            const profitElement = document.getElementById('cardTotalProfit');
            profitElement.innerText = formatCurrency(totalProfit);
            // valores cores do css
            profitElement.classList.toggle('positive-value', totalProfit >= 0);
            profitElement.classList.toggle('negative-value', totalProfit < 0);
            
            const roiElement = document.getElementById('cardAverageROI');
            roiElement.innerText = formatRoi(avgROI);
            roiElement.classList.toggle('positive-value', avgROI >= 0);
            roiElement.classList.toggle('negative-value', avgROI < 0);
        }

        // drawEditorDailyChart adicionado parametro chartType e logica de estilo
        function drawEditorDailyChart(data, chartType = 'line') {
            
            if (editorDetailsChart) {
                editorDetailsChart.destroy();
            }

            const ctx = document.getElementById("editorDailyChart").getContext("2d");
            
            
            
            
            // SIMULACAO DE AGRUPAMENTO POR DATA PARA O GRAFICO DIARIO
            // supondo que 'data' é uma lista de criativos e nao uma lista de performance diaria pois nao tenho info das datas diarias 
            
            // vou manter o agrupamento original por criativo, mas renomear a variavel para ser clara
            const labels = data.map(item => item.creative_code.substring(0, 10) + '...');
            const profitData = data.map(item => item.profit);
            const costData = data.map(item => item.cost);

            editorDetailsChart = new Chart(ctx, {
                type: chartType, 
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Lucro",
                        data: profitData,
                        borderColor: chartType === 'line' ? COLOR_SUCCESS_VERDE : 'transparent',
                        backgroundColor: chartType === 'line' ? `rgba(74,222,128,0.15)` : COLOR_SUCCESS_VERDE,
                        fill: chartType === 'line',
                        tension: chartType === 'line' ? 0.35 : 0,
                        borderWidth: 2,
                        pointRadius: chartType === 'line' ? 4 : 0,
                        yAxisID: 'y'
                    }, {
                        label: "Custo",
                        data: costData,
                        borderColor: chartType === 'line' ? COLOR_DANGER_VERMELHO : 'transparent',
                        backgroundColor: chartType === 'line' ? `rgba(248,113,113,0.15)` : COLOR_DANGER_VERMELHO,
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
                        legend: { labels: { color: "#ddd" } },
                        tooltip: { 
                            backgroundColor: "rgba(10, 10, 10, 0.95)",
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += formatCurrency(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { stacked: false, ticks: { color: "#aaa" }, grid: { display: false } },
                        y: { 
                            stacked: false, 
                            ticks: { color: "#aaa", callback: function(value) { return formatCurrency(value); } }, 
                            grid: { color: "rgba(255,255,255,0.08)" } 
                        }
                    }
                }
            });

            // BOTAO PARA ALTERNAR
            const toggleBtn = document.getElementById('toggleDailyChartTypeBtn');
            if (chartType === 'line') {
                toggleBtn.innerHTML = '<i class="fas fa-chart-bar"></i>';
                toggleBtn.title = "Alternar para Colunas";
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-chart-line"></i>';
                toggleBtn.title = "Alternar para Linhas";
            }
        }
        
        // FUNCAO PARA ALTERNAR O TIPO, LINHAS OU BARRAS
        function toggleEditorDailyChartType() {
            editorDailyChartType = editorDailyChartType === 'line' ? 'bar' : 'line';
            drawEditorDailyChart(currentEditorData, editorDailyChartType);
        }

        // FUNCAO PARA ATUALIZAR O GRAFICO DIARIO 
        
        
        function refreshEditorDailyChart() {
             // LOGICA DE REQUISICAO AJAX AQUI 
             // (por enquanto apenas redesenha com os dados atuais)
             
             // redesenha o grafico com ALERTA
             drawEditorDailyChart(currentEditorData, editorDailyChartType);
             alert("Gráfico de Performance Diária atualizado (redesenhado com dados existentes, pois precisa atualizar a logica.).");
        }
        
        // AGRUPAR DADOS POR NICHO - NAO TEM AINDA 
        function groupDataByNiche(data) {
            const nicheMap = {};
            
            data.forEach(item => {
                const niche = item.niche || 'Não Especificado ainda'; 
                
                if (!nicheMap[niche]) {
                    nicheMap[niche] = {
                        niche_name: niche,
                        total_creatives: 0,
                        clicks: 0,
                        conversions: 0,
                        cost: 0,
                        profit: 0,
                        roi: 0
                    };
                }
                
                nicheMap[niche].total_creatives += 1;
                nicheMap[niche].clicks += item.clicks || 0;
                nicheMap[niche].conversions += item.conversions || 0;
                nicheMap[niche].cost += item.cost || 0;
                nicheMap[niche].profit += item.profit || 0;
            });
            
            // calcular ROI apos a soma
            Object.keys(nicheMap).forEach(key => {
                const nicheData = nicheMap[key];
                nicheData.roi = nicheData.cost > 0 ? nicheData.profit / nicheData.cost : 0;
            });
            
            return Object.values(nicheMap);
        }
        
        // PREENCHER TABELA POR NICHO
        function fillNicheTable(nicheData) {
            const tableBody = document.getElementById('detailsNicheTableBody');
            const noDataMessage = document.getElementById('nicheNoDataMessage');
            tableBody.innerHTML = '';
            
            if (nicheData.length === 0) {
                noDataMessage.style.display = 'block';
                return;
            }

            noDataMessage.style.display = 'none';

            nicheData.forEach(item => {
                const row = document.createElement('tr');
                row.className = 'niche-detail-row';
                
                const profitClass = item.profit >= 0 ? 'positive-value' : 'negative-value';
                const roiClass = item.roi >= 0 ? 'positive-value' : 'negative-value';
                
                row.innerHTML = `
                    <td class="niche-name">${item.niche_name}</td>
                    <td>${item.total_creatives}</td>
                    <td>${item.clicks.toLocaleString('pt-BR')}</td>
                    <td>${item.conversions.toLocaleString('pt-BR')}</td>
                    <td>${formatCurrency(item.cost)}</td>
                    <td class="${profitClass}">${formatCurrency(item.profit)}</td>
                    <td class="${roiClass}">${formatRoi(item.roi)}</td>
                `;
                tableBody.appendChild(row);
            });
            
            // ORDENACAO DA TABELA POR NIHCO
            addNicheTableSorting();
        }

        // DESENHA GRAFICO POR NICHO
        function drawEditorNicheChart(nicheData) {
            if (editorNicheChart) {
                editorNicheChart.destroy();
            }

            const ctx = document.getElementById("editorNicheChart").getContext("2d");
            
            const labels = nicheData.map(item => item.niche_name);
            const profitData = nicheData.map(item => item.profit);
            const costData = nicheData.map(item => item.cost);

            editorNicheChart = new Chart(ctx, {
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
                        legend: { labels: { color: "#ddd" } },
                        tooltip: { 
                            backgroundColor: "rgba(10, 10, 10, 0.95)",
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += formatCurrency(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { stacked: false, ticks: { color: "#aaa" }, grid: { display: false } },
                        y: { 
                            stacked: false, 
                            ticks: { color: "#aaa", callback: function(value) { return formatCurrency(value); } }, 
                            grid: { color: "rgba(255,255,255,0.08)" } 
                        }
                    }
                }
            });
        }
        
        // ORDENACAO DAS TABELAS
        function switchNicheView(viewType) {
            document.querySelectorAll('.niche-content-view').forEach(view => {
                view.style.display = 'none';
                view.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            document.getElementById(`niche${viewType.charAt(0).toUpperCase() + viewType.slice(1)}View`).style.display = 'block';
            document.getElementById(`niche${viewType.charAt(0).toUpperCase() + viewType.slice(1)}View`).classList.add('active');
            document.querySelector(`.tab-button:nth-child(${viewType === 'table' ? 1 : 2})`).classList.add('active');
            
            // se for para o grafico garante que ele seja desenhado/redesenhado
            if (viewType === 'chart' && currentEditorData.length > 0) {
                const nicheData = groupDataByNiche(currentEditorData);
                drawEditorNicheChart(nicheData);
            }
        }
        
        function sortNicheTable(key) {
             sortDetailsTable(key, 'detailsNicheTableBody');
        }

        function addNicheTableSorting() {
            document.querySelectorAll('#detailsNicheTable .details-sortable').forEach(header => {
                header.replaceWith(header.cloneNode(true)); // limpa listeners antigos
            });
            
            document.querySelectorAll('#detailsNicheTable .details-sortable').forEach(header => {
                header.addEventListener('click', () => {
                    sortNicheTable(header.dataset.sortKey);
                });
            });
        }

        // MANTIDA E AJUSTADA: sortDetailsTable 
        function sortDetailsTable(key, tableBodyId) {
            const tableBody = document.getElementById(tableBodyId);
            const rows = Array.from(tableBody.querySelectorAll('tr'));
            const header = document.querySelector(`#${tableBodyId}`).closest('table').querySelector(`th[data-sort-key="${key}"]`);
            
            let direction = header.dataset.sortDirection === 'asc' ? 'desc' : 'asc';
            
            // reseta icones de ordenacao, mas apenas para a tabela sendo ordenada
            document.querySelectorAll(`#${tableBodyId}`).closest('table').querySelectorAll('.details-sortable i').forEach(icon => {
                icon.className = 'fas fa-sort';
            });
            
            const icon = header.querySelector('i');
            icon.className = direction === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
            header.dataset.sortDirection = direction;

            rows.sort((a, b) => {
                const aValRaw = a.querySelector(`td:nth-child(${Array.from(header.parentNode.children).indexOf(header) + 1})`).innerText;
                const bValRaw = b.querySelector(`td:nth-child(${Array.from(header.parentNode.children).indexOf(header) + 1})`).innerText;
                
                let aVal, bVal;
                
                // trata a conversao de valores para ordenacao numerica
                if (key !== 'niche_name' && key !== 'creative_code') {
                    aVal = parseFloat(aValRaw.replace(/[^0-9,-]/g, '').replace(',', '.'));
                    bVal = parseFloat(bValRaw.replace(/[^0-9,-]/g, '').replace(',', '.'));
                } else {
                    aVal = aValRaw;
                    bVal = bValRaw;
                }
                
                let comparison = 0;
                
                if (typeof aVal === 'number' && typeof bVal === 'number') {
                    if (aVal < bVal) { comparison = -1; }
                    if (aVal > bVal) { comparison = 1; }
                } else {
                    if (aVal < bVal) { comparison = -1; }
                    if (aVal > bVal) { comparison = 1; }
                }

                return direction === 'asc' ? comparison : comparison * -1;
            });

            rows.forEach(row => tableBody.appendChild(row));
        }
        
        // removido - tabela de criativos
        function addDetailsTableSorting() {
            // tabela de criativos removida 
        }

        // openEditorDetailsModal (agora processa dados por nicho)
        async function openEditorDetailsModal(editorName, creativesJson, editorRole, editorEmail) {
            document.getElementById("editorDetailsModal").style.display = "flex";
            
            // preenche o card de informacoes do editor
            document.getElementById("editorNameTitle").innerText = editorName;
            document.getElementById("editorRoleEmail").innerText = `Função: ${editorRole || 'Não Definida'} | Email: ${editorEmail || 'Não Disponível'}`;

            
            try {
                currentEditorData = JSON.parse(creativesJson); 
                
            } catch (e) {
                console.error("Erro ao parsear dados dos criativos.", e);
                currentEditorData = [];
            }
            
            fillSummaryCards(currentEditorData);
            // chama o grafico com o 'line'
            drawEditorDailyChart(currentEditorData, editorDailyChartType); 
            
            // processa e preenche os dados por nicho
            const nicheData = groupDataByNiche(currentEditorData);
            fillNicheTable(nicheData); 
            drawEditorNicheChart(nicheData); 
            
            
            
        }


        window.onload = () => {
            // inicializa a ordenacao das tabelas aninhadas in-line (mantida para a tabela principal)
            document.querySelectorAll('.nested-table th.sortable').forEach(header => {
                header.addEventListener('click', function() {
                    alert('Função de ordenação para a tabela in-line a ser implementada, use a ordenação do modal Sub-View!');
                });
            });
            
            // listeners para o grafico diario
            document.getElementById('toggleDailyChartTypeBtn').addEventListener('click', toggleEditorDailyChartType);
            document.getElementById('refreshDailyChartBtn').addEventListener('click', refreshEditorDailyChart);
        };
        
    </script>
    
    {{-- GRAFICO JA FEITO - APENAS SO REPLIQUEI (Chart.js) --}}
    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // IGUAL
                window.creativeChart = null;
                let currentCreativeCode = null;
                let currentChartType = 'line'; 
                
                //  IGUAL
                const COLOR_PRIMARY_AZUL = '#0f53ff';
                const COLOR_SUCCESS_VERDE = '#4ADE80';
                const COLOR_DANGER_VERMELHO = '#F87171';
                
                // IGUAL
                function fetchChartData(creativeCode) {
                    document.getElementById("chartCreativeTitle").innerText = `Carregando dados para ${creativeCode}...`;
                    // SIMULACAO DE DADOS
                    return new Promise(resolve => {
                        setTimeout(() => {
                            document.getElementById("chartCreativeTitle").innerText = creativeCode;
                            const mockData = [
                                { date: '2025-11-10', profit: 150.50, cost: 50.00 },
                                { date: '2025-11-11', profit: -20.00, cost: 75.00 },
                                { date: '2025-11-12', profit: 300.20, cost: 100.00 },
                                { date: '2025-11-13', profit: 80.00, cost: 40.00 },
                                { date: '2025-11-14', profit: 10.00, cost: 10.00 },
                            ];
                            resolve(mockData);
                        }, 500);
                    });
                }
                
                // DESENHAR O GRAFICO IGUAL
                function drawCreativeChart(data, chartType) {
                    const labels = data.map(item => item.date);
                    const profitData = data.map(item => item.profit);
                    const costData = data.map(item => item.cost);

                    const ctx = document.getElementById("creativeChart").getContext("2d");

                    if (window.creativeChart instanceof Chart) {
                        window.creativeChart.destroy();
                    }

                    window.creativeChart = new Chart(ctx, {
                        type: chartType,
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Lucro",
                                data: profitData,
                                borderColor: chartType === 'line' ? COLOR_SUCCESS_VERDE : 'transparent',
                                backgroundColor: chartType === 'line' ? `rgba(74,222,128,0.15)` : COLOR_SUCCESS_VERDE,
                                fill: chartType === 'line',
                                tension: chartType === 'line' ? 0.35 : 0,
                                borderWidth: 2,
                                pointRadius: chartType === 'line' ? 4 : 0,
                                yAxisID: 'y'
                            }, {
                                label: "Custo",
                                data: costData,
                                borderColor: chartType === 'line' ? COLOR_DANGER_VERMELHO : 'transparent',
                                backgroundColor: chartType === 'line' ? `rgba(248,113,113,0.15)` : COLOR_DANGER_VERMELHO,
                                fill: chartType === 'line',
                                tension: chartType === 'line' ? 0.35 : 0,
                                borderWidth: 2,
                                pointRadius: chartType === 'line' ? 4 : 0,
                                yAxisID: 'y'
                            }, ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            
                            animation: { duration: 1000, easing: 'easeInOutQuart' },
                            plugins: {
                                legend: {
                                    labels: { color: "#ddd", font: { size: 14, weight: '600' } }
                                },
                                tooltip: {
                                    backgroundColor: "rgba(10, 10, 10, 0.95)",
                                    titleColor: "#fff",
                                    bodyColor: "#fff",
                                    borderColor: COLOR_PRIMARY_AZUL, // borda AZUL - adicionada
                                    borderWidth: 1,
                                    cornerRadius: 6,
                                    padding: 12,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) { label += ': '; }
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
                                    ticks: { color: "#aaa", maxRotation: 45, minRotation: 45 },
                                    grid: { color: "rgba(255,255,255,0.08)" }
                                },
                                y: {
                                    position: 'left',
                                    ticks: { color: "#aaa", callback: function(value) { return formatCurrency(value); } },
                                    grid: { color: "rgba(255,255,255,0.08)" },
                                    title: {
                                        display: true,
                                        text: 'Valor (R$)',
                                        color: "#ccc",
                                        font: { size: 14, weight: '600' }
                                    }
                                }
                            }
                        }
                    });

                    // atualiza o estado do botao IGUAL
                    const toggleBtn = document.getElementById('toggleChartTypeBtn');
                    if (chartType === 'line') {
                        toggleBtn.innerHTML = '<i class="fas fa-chart-bar"></i>';
                        toggleBtn.title = "Alternar para Colunas";
                    } else {
                        toggleBtn.innerHTML = '<i class="fas fa-chart-line"></i>';
                        toggleBtn.title = "Alternar para Linhas";
                    }
                }

                // ABRE O MODAL DO GRAFICO
                async function openCreativeChart(creativeCode, creativeTitle) {
                    currentCreativeCode = creativeCode;
                    document.getElementById("creativeChartModal").style.display = "flex";
                    document.getElementById("chartCreativeTitle").innerText = creativeTitle;

                    const data = await fetchChartData(creativeCode);
                    if (data.length > 0) {
                        drawCreativeChart(data, currentChartType);
                    } else {
                        if (window.creativeChart instanceof Chart) {
                            window.creativeChart.destroy();
                        }
                        document.getElementById("chartCreativeTitle").innerText = `${creativeTitle} (Sem dados no período)`;
                    }
                }

                // event listeners IGUAL
                document.getElementById('refreshChartBtn').addEventListener('click', async () => {
                    if (currentCreativeCode) {
                        const data = await fetchChartData(currentCreativeCode);
                        drawCreativeChart(data, currentChartType);
                    }
                });

                document.getElementById('toggleChartTypeBtn').addEventListener('click', async () => {
                    currentChartType = currentChartType === 'line' ? 'bar' : 'line';
                    
                    if (currentCreativeCode) {
                        const data = await fetchChartData(currentCreativeCode);
                        drawCreativeChart(data, currentChartType); 
                    }
                });
            </script>
        @endpush
    @endonce
</x-layout>



