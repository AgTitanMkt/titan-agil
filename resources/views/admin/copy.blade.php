<x-layout>
    {{-- <link rel="stylesheet" href="{{ asset('css/admin-copy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-copy-dashboard.css') }}"> --}}

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

        <div class="filter-control-panel">
            <div class="filter-inner-box">
                <h3 class="filter-main-title">Seleção de Filtro</h3>
                <p class="filter-sub-title">Escolha o período desejado e veja as novas métricas.</p>

                <form action="{{ route('admin.copywriters') }}" class="filters-grid filters-grid-production">
                    <div class="filter-group">
                        <x-date-range name="date" :from="$startDate" :to="$endDate" label="Intervalo de Datas" />
                    </div>
                    <button type="submit" class="btn-filter-action">Filtrar</button>
                </form>
            </div>
        </div>

        {{-- COMECO DASHBOARD --}}
        <section id="section-dashboard" class="content-section">

            {{-- <div class="filter-control-panel">
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
    </div> --}}

            <div class="section-divider">
                <h2 class="display-title">Performance Geral</h2>
            </div>

            <div class="main-metrics-row">
                <div class="metric-card-primary glow-blue">
                    <div class="card-icon-top">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="internal-stack">
                        <div class="mini-card-outline">
                            <span class="mini-label">Total Produzido</span>
                            <span class="mini-value">{{ $totalProduzido }} Ads</span>
                        </div>
                        <div class="mini-card-outline">
                            <span class="mini-label">Total Testado</span>
                            <span class="mini-value">{{ $totalTestado }} Ads</span>
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
                            <span class="mini-value">@percent0($emPotencial/$totalTestado) | @int_number($emPotencial)
                                Ads</span>
                        </div>
                        <div class="mini-card-outline secondary-border">
                            <span class="mini-label">Taxa de Acerto</span>
                            <span class="mini-value">@percent0($validados/$totalTestado) | @int_number($validados) Ads</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="secondary-metrics-grid">
                <div class="small-metric-card">
                    <span class="small-label">Melhor Nicho</span>
                    <span class="small-data">{{ $topRoiNicho->sigla }} | <span
                            class="highlight-roi">@percent0($topRoiNicho->roi) ROI</span></span>
                </div>
                <div class="small-metric-card">
                    <span class="small-label">Maior Nicho</span>
                    <span class="small-data">{{ $topProfitNicho->sigla }} | <span
                            class="highlight-profit">@percent0($topProfitNicho->total_profit/$totalProfitNichos) do
                            Profit</span></span>
                </div>

                <div class="small-metric-card">
                    <span class="small-label">Melhor Copy</span>
                    <span class="small-data">{{ $topCopiesRoi->name }} | <span class="highlight-roi">@percent($topCopiesRoi->metrics->sum('total_profit') / $topCopiesRoi->metrics->sum('total_cost'))
                            ROI</span></span>
                </div>
                <div class="small-metric-card">
                    <span class="small-label">Maior Copy</span>
                    <span class="small-data"> {{ $topCopiesProfit->name }} | <span
                            class="highlight-profit">@percent($topCopiesProfit->metrics->sum('total_profit') / $totalProfitCopies) do Profit</span></span>
                </div>

                <div class="small-metric-card">
                    <span class="small-label">Melhor Dupla</span>
                    <span class="small-data">{{ $topDuplaRoi->dupla }} | <span class="highlight-roi">@percent($topDuplaRoi->roi)
                            ROI</span></span>
                </div>
                <div class="small-metric-card">
                    <span class="small-label">Maior Dupla</span>
                    <span class="small-data">{{ $topDuplaProfit->dupla }} | <span
                            class="highlight-profit">@percent($topDuplaProfit->total_profit / $totalProfitCopies) do Profit</span></span>
                </div>
            </div>

            <div class="analytics-charts-section">

                <div class="section-divider">
                    <h2 class="display-title-performance">Performance Individual <span
                            class="title-italic-light">Geral</span></h2>
                </div>

                <div class="niche-selector-bar">
                    @foreach ($nichosBar as $nicho)
                        <div class="niche-block {{ strtolower($nicho->sigla) }}"
                            data-niche="{{ strtolower($nicho->sigla) }}"
                            onclick="updateNiche('{{ strtolower($nicho->sigla) }}')"
                            style="width: {{ $nicho->percent }}%;">
                            <div class="niche-badge">
                                <span class="perc">{{ $nicho->percent }}%</span>
                                <span class="name">{{ $nicho->sigla }}</span>
                            </div>
                        </div>
                    @endforeach
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
                        <form method="GET" action="{{ route('admin.copywriters') }}" id="copySelectForm">
                            {{-- manter filtros de data --}}
                            <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                            <input type="hidden" name="date_to" value="{{ request('date_to') }}">

                            <select name="copy_id" class="copy-select"
                                onchange="document.getElementById('copySelectForm').submit()">
                                @foreach ($copies as $copy)
                                    <option value="{{ $copy->id }}"
                                        {{ ($selectedCopyId ?? null) == $copy->id ? 'selected' : '' }}>
                                        {{ $copy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

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
        <section id="section-creatives" class="content-section" style="display: none;">

            {{-- ARQUIVO --}}
            <link rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

            {{-- <div class="header-container">
        <h2 class="dashboard-page-title">Produção Copywriters</h2>
        <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>
    </div> --}}

            {{-- filtros de performance --}}
            <div class="production-filters-section glass-card filters-shadow">
                <h3 class="section-title">Produção Copywriters</h3>

                <form class="filters-grid filters-grid-production">
                    {{-- <div class="filter-group">
                <x-date-range name="date" :from="$startDate" :to="$endDate" label="Intervalo de Datas" />
            </div> --}}
                    <div class="filter-group">
                        {{-- Adaptado para Copywriters --}}
                        <x-multiselect name="copywriters" label="Copywriters" :options="$allCopywriters" :selected="request('copywriters', [])"
                            placeholder="Selecione um ou mais copywriters">
                        </x-multiselect>
                    </div>

                    <div class="filter-submit-area filter-submit-area-production">
                        <button type="submit" class="btn-filter">FILTRAR</button>
                    </div>
                </form>
            </div>

            {{-- COPIES produzidas (taabela principal) --}}
            <div class="copy-production-section glass-card table-shadow">
                <h3 class="section-title">Copies Produzidas por Copywriter</h3>

                <div class="table-responsive">
                    <table class="metrics-main-table">
                        <thead>
                            <tr>
                                <th class="header-editor">Copywriter</th>
                                <th class="header-metrics">Produzido</th>
                                <th class="header-metrics">Testado</th> {{-- adaptado para copy --}}
                                <th class="header-metrics">Potencial</th>
                                <th class="header-metrics">Validados</th>
                                <th class="header-metrics">Win/Rate</th>
                                <th class="header-metrics">Cliques</th>
                                <th class="header-metrics">Conversões</th>
                                <th class="header-metrics">Custo</th>
                                <th class="header-metrics">Lucro</th>
                                <th class="header-metrics">ROI (%)</th>
                                <th class="header-action">Detalhes</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- $copies --}}
                            @foreach ($copies as $copy)
                                @php
                                    // adaptando para usar as variaveis de Copywriter

                                    // $creativesByAgent baseado no codigo padrao usado, mas precisa verificar no controller para as info vir corretas
                                    $creativesJson = json_encode($copy->metrics ?? collect());
                                    $key = 'copywriter-' . $copy->id;
                                @endphp

                                {{-- linha principal --}}
                                <tr class="editor-row clickable-row" data-editor-id="{{ $copy->id }}"
                                    onclick="toggleDetails('{{ $key }}')">
                                    <td class="editor-name-cell">
                                        <span class="arrow-indicator"><i class="fas fa-chevron-right"></i></span>
                                        <span class="fw-bold">{{ $copy->name }}</span>
                                    </td>
                                    <td>{{ count($copy->subTasks) }}</td>
                                    <td>{{ count($copy->metrics) }}
                                    <td>@int_number($copy->metrics->sum('em_potencial'))</td>
                                    <td>@int_number($copy->metrics->sum('validados'))</td>
                                    <td>@percent($copy->metrics->sum('validados') / count($copy->metrics))</td>
                                    <td>@int_number($copy->metrics->sum('total_clicks'))</td>
                                    <td>@int_number($copy->metrics->sum('total_conversions'))</td>
                                    <td>@dollar($copy->metrics->sum('total_cost'))</td>
                                    {{-- lucro com cor condicional --}}
                                    <td
                                        class="{{ $copy->metrics->sum('total_profit') >= 0 ? 'positive-value' : 'negative-value' }}">
                                        @dollar($copy->metrics->sum('total_profit'))
                                    </td>
                                    {{-- ROI com cor condicional --}}
                                    <td
                                        class="{{ $copy->metrics->sum('total_profit') >= 0 ? 'positive-value' : 'negative-value' }}">
                                        {{ $copy->metrics->sum('total_cost') > 0
                                            ? number_format(($copy->metrics->sum('total_profit') / $copy->metrics->sum('total_cost')) * 100, 2, ',', '.')
                                            : 0 }}%
                                    </td>
                                    {{-- Botao CTA para a Sub Visualizacao 2.0 --}}
                                    <td class="action-cell">
                                        <button class="btn-subview-cta" data-name="{{ $copy->name }}"
                                            data-email="{{ $copy->email }}" data-json='@json($copy->metrics ?? [])'
                                            data-clicks="@int_number($copy->metrics->sum('total_clicks'))"
                                            data-copies="@int_number(count($copy->metrics->where('status', 'ok'))) / {{ count($copy->metrics) }}"
                                            data-profit="@dollar($copy->metrics->sum('total_profit'))"
                                            data-roi="{{ $copy->metrics->sum('total_cost') > 0
                                                ? number_format(($copy->metrics->sum('total_profit') / $copy->metrics->sum('total_cost')) * 100, 2, ',', '.')
                                                : 0 }}%"
                                            onclick="event.stopPropagation(); handleCopyModalOpen(this);"
                                            title="Ver Sub Visualização 2.0">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    </td>

                                </tr>

                                {{-- detalhes DO COPY (Modal In-line) --}}
                                <tr id="details-{{ $key }}" class="details-row" style="display: none;">
                                    <td colspan="13" class="details-cell"> {{-- ANTES ESTAVA COM 8 --}}
                                        <div class="nested-table-container custom-scrollbar">
                                            <h4 class="nested-table-title">Criativos de {{ $copy->name }}</h4>
                                            <table class="nested-table">
                                                <thead>
                                                    <tr>
                                                        <th data-sort-key="creative_code" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Criativo</i>
                                                        </th>
                                                        <th data-sort-key="date" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Data </i>
                                                        </th>
                                                        <th data-sort-key="em-potencial" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Potencial </i>
                                                        </th>
                                                        <th data-sort-key="clicks" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Cliques </i>
                                                        </th>
                                                        <th data-sort-key="conversions" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Conversões </i>
                                                        </th>
                                                        <th data-sort-key="conversions" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            CPC </i>
                                                        </th>
                                                        <th data-sort-key="conversions" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            EPC </i>
                                                        </th>
                                                        <th data-sort-key="cost" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Custo </i>
                                                        </th>
                                                        <th data-sort-key="profit" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Lucro </i>
                                                        </th>
                                                        <th data-sort-key="revenue" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Receita </i>
                                                        </th>
                                                        <th data-sort-key="roi" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            ROI </i>
                                                        </th>
                                                        <th class="text-center" style="font-size: 0.8rem;">Gráfico
                                                        </th>
                                                        <th class="text-center" style="font-size: 0.8rem;">
                                                            <i class="fas fa-box-open"
                                                                title="Sub Visualização 2.0"></i>
                                                        </th>
                                                    </tr>
                                </tr>
                                </thead>
                        <tbody>
                            {{-- loop adaptado para os criativos do agente --}}
                            @foreach ($copy->metrics as $cr)
                                <tr class="creative-detail-row">
                                    <td class="creative-code">{{ $cr->code }}</td>
                                    <td>{{ $cr->first_redtrack_date }}</td>
                                    <td>
                                        @if ($cr->em_potencial)
                                            <span class="badge-yes">SIM</span> {{--  ADICINADO LAYOUT PARA SECTIONDE BAGDES YES/NO --}}
                                        @else
                                            <span class="badge-no">NÃO</span>
                                        @endif
                                    </td>
                                    <td>{{ $cr->total_clicks }}</td>
                                    <td>{{ $cr->total_conversions }}</td>
                                    <td>
                                        @if ($cr->total_clicks > 0)
                                            @dollar($cr->total_cost / $cr->total_clicks)
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        @if ($cr->total_clicks > 0)
                                            @dollar(($cr->total_cost + $cr->total_profit) / $cr->total_clicks)
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>@dollar($cr->total_cost)</td>
                                    <td class="{{ $cr->total_profit >= 0 ? 'positive-value' : 'negative-value' }}">
                                        @dollar($cr->total_profit)
                                    </td>
                                    <td>@dollar($cr->total_profit + $cr->total_cost)</td>
                                    <td class="{{ $cr->roi >= 0 ? 'positive-value' : 'negative-value' }}">
                                        {{ number_format($cr->roi * 100, 2, ',', '.') }}%
                                    </td>
                                    <td class="text-center">
                                        <button class="btn-chart"
                                            onclick="event.stopPropagation(); openCreativeChart('{{ $cr->code }}', '{{ $cr->code }}');"
                                            title="Ver Gráfico">
                                            <i class="fas fa-chart-line"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn-subview-cta-item"
                                            onclick="event.stopPropagation(); openCreativeSubView('{{ $cr->code }}');"
                                            title="Ver Sub Visualização do Criativo">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (($creativesByAgent[$copy->user_id] ?? collect())->isEmpty())
                        <p class="no-data-message">Nenhum criativo encontrado para este copywriter
                            neste
                            período.</p>
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

    {{-- MODAL DO GRAFICO DE CRIATIVO COPY --}}
    <div id="creativeChartModalCopy" class="creative-modal" style="display: none;">
        <div class="creative-modal-content">
            <div class="creative-modal-header">
                <h3 id="chartCreativeTitleCopy">Gráfico do Criativo</h3>
                <div class="chart-controls">
                    <button id="toggleChartTypeBtnCopy" class="btn-chart-control" title="Alternar para Barras">
                        <i class="fas fa-chart-bar"></i>
                    </button>
                    <button id="refreshChartBtnCopy" class="btn-chart-control" title="Atualizar Dados">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
                <button class="creative-modal-close" onclick="closeCreativeChartCopy()">×</button>
            </div>
            <div class="creative-chart-wrapper">
                <canvas id="creativeChartCopy"></canvas>
            </div>
        </div>
    </div>

    {{-- SUB VISUALIZACAO 2.0 DETALHES DO COPY IGUAL --}}
    <div id="copyDetailsModal" class="creative-modal subview-modal" style="display: none;">
        <div class="creative-modal-content subview-modal-content">

            <div class="creative-modal-header">
                <h3 id="copyDetailsTitle" class="main-title-hidden">Detalhes do Copy: [Nome]</h3>
                <button class="creative-modal-close" onclick="closeCopyDetailsModal()">×</button>
            </div>

            <div class="subview-body-container">

                {{-- CARD DE INFORMACOES DO COPY --}}
                <div class="editor-info-card glass-card">
                    <div class="editor-info-header">
                        <h3 id="copyNameTitle" class="editor-name-title">Nome do Copywriter</h3>
                        <p id="copyRoleEmail" class="editor-role-email">Função: Copywriter | Email: email@corp.com</p>
                    </div>
                </div>

                {{-- CARDS DE TOTAL, CLIQUES, LUCRO, ROI IGUAL --}}
                <div class="details-cards-grid">
                    <div class="metric-card glass-card card-creatives">
                        <span class="card-icon"><i class="fas fa-palette"></i></span>
                        <p class="card-title">Total de Copies</p>
                        <h4 id="cardTotalCopies" class="card-value">0</h4>
                    </div>
                    <div class="metric-card glass-card card-clicks">
                        <span class="card-icon"><i class="fas fa-hand-pointer"></i></span>
                        <p class="card-title">Total de Cliques</p>
                        <h4 id="cardTotalClicksCopy" class="card-value">0</h4>
                    </div>
                    <div class="metric-card glass-card card-profit">
                        <span class="card-icon"><i class="fas fa-coins"></i></span>
                        <p class="card-title">Lucro Total</p>
                        <h4 id="cardTotalProfitCopy" class="card-value">R$ 0,00</h4>
                    </div>
                    <div class="metric-card glass-card card-roi">
                        <span class="card-icon"><i class="fas fa-chart-line"></i></span>
                        <p class="card-title">ROI Médio</p>
                        <h4 id="cardAverageROICopy" class="card-value">0.00%</h4>
                    </div>
                </div>

                {{-- GRAFICO DIARIO IGUAL  --}}
                <div class="editor-chart-section glass-card">
                    <div class="editor-chart-header">
                        <h4 class="chart-section-title">Performance Diária (Lucro vs Custo) - Por Copy</h4>
                        <div class="chart-controls">
                            <button id="toggleDailyChartTypeBtnCopy" class="btn-chart-control"
                                title="Alternar para Linhas">
                                <i class="fas fa-chart-bar"></i>
                            </button>
                            <button id="refreshDailyChartBtnCopy" class="btn-chart-control" title="Atualizar Dados">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="creative-chart-wrapper">
                        <canvas id="copyDailyChart"></canvas>
                    </div>
                </div>

                {{-- ABA DE NICHO IGUAL --}}
                <div class="niche-data-section glass-card">
                    <h4 class="chart-section-title">Análise de Performance por Nicho</h4>

                    <div class="niche-tabs">
                        <button class="tab-button active" onclick="switchNicheViewCopy('table')">
                            <i class="fas fa-table"></i> Tabela
                        </button>
                        <button class="tab-button" onclick="switchNicheViewCopy('chart')">
                            <i class="fas fa-chart-bar"></i> Gráfico
                        </button>
                    </div>

                    <div id="nicheTableViewCopy" class="niche-content-view active custom-scrollbar"
                        style="max-height: 400px; overflow-y: auto; margin-top: 15px;">
                        <table id="detailsNicheTableCopy" class="nested-table details-subview-table">
                            <thead>
                                <tr>
                                    <th data-sort-key="niche_name" class="sortable details-sortable">Nicho <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="total_copies" class="sortable details-sortable">Produzido <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="clicks" class="sortable details-sortable">Cliques <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="conversions" class="sortable details-sortable">Conv. <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="cost" class="sortable details-sortable">Custo <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="profit" class="sortable details-sortable">Lucro <i
                                            class="fas fa-sort"></i></th>
                                    <th data-sort-key="roi" class="sortable details-sortable">ROI <i
                                            class="fas fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="detailsNicheTableBodyCopy">
                                {{-- DADOS VIA JS --}}
                            </tbody>
                        </table>
                        <p id="nicheNoDataMessageCopy" class="no-data-message"
                            style="display: none; text-align: center;">Nenhum dado de nicho encontrado.</p>
                    </div>

                    <div id="nicheChartViewCopy" class="niche-content-view" style="display: none; margin-top: 15px;">
                        <div class="creative-chart-wrapper" style="height: 350px;">
                            <canvas id="copyNicheChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- COMECO SCRITP COPY --}}
    <script>
        // Variáveis Globais de Cores IGUAL
        const COLOR_PRIMARY_AZUL = '#0f53ff';
        const COLOR_SUCCESS_VERDE = '#4ADE80';
        const COLOR_DANGER_VERMELHO = '#F87171';

        // Funções de formatação IGUAL
        function formatCurrency(value) {
            return new Intl.NumberFormat('en-En', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
            }).format(value);
        }

        function formatRoi(value) {
            return `${(value * 100).toFixed(2).replace('.', ',')}%`;
        }

        // TABELA PRINCIPAL (copywriters)

        // detalhes In-line por COPY IGUAL, ajustada para 'copywriter-'
        function toggleDetails(key) {
            const row = document.getElementById(`details-${key}`);
            // procurar pelo ID do usuario no atributo data-editor-id
            const copyRow = document.querySelector(`tr.clickable-row[data-editor-id="${key.replace('copywriter-', '')}"]`);
            const icon = copyRow.querySelector('.arrow-indicator i');

            if (row.style.display === "none") {
                // fechar outros se estiver expandido
                document.querySelectorAll('.details-row').forEach(r => {
                    if (r.id !== `details-${key}` && r.style.display !== "none") {
                        r.style.display = "none";
                        const otherKey = r.id.replace('details-', '');
                        const otherCopyRow = document.querySelector(
                            `tr.clickable-row[data-editor-id="${otherKey.replace('copywriter-', '')}"]`);
                        if (otherCopyRow) {
                            otherCopyRow.classList.remove('details-expanded');
                            otherCopyRow.querySelector('.arrow-indicator i').classList.remove('fa-chevron-down');
                            otherCopyRow.querySelector('.arrow-indicator i').classList.add('fa-chevron-right');
                        }
                    }
                });

                row.style.display = "table-row";
                copyRow.classList.add('details-expanded');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                row.style.display = "none";
                copyRow.classList.remove('details-expanded');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }

        // FUNCAO PARA SUB VISUALIZACAO DE CRIATIVO/COPY IGUAL ainda um placeholder
        function openCreativeSubView(creativeCode) {
            alert(`Abrir Sub Visualização 2.0 para o Criativo: ${creativeCode}. (a ser implementado, ainda)`);
        }


        // VARIAVEIS E FUNCOES DO MODAL SUB VISUALIZACAO DO COPY 

        let currentCopyData = []; // dados do copywriter atual
        let copyDetailsChart = null;
        let copyNicheChart = null;
        let copyDailyChartType = 'line'; // estado do grafico diario - padrao line

        // fechar modal (ADAPTADA)
        function closeCopyDetailsModal() {
            document.getElementById("copyDetailsModal").style.display = "none";

            if (copyDetailsChart) {
                copyDetailsChart.destroy();
                copyDetailsChart = null;
            }
            if (copyNicheChart) {
                copyNicheChart.destroy();
                copyNicheChart = null;
            }
            copyDailyChartType = 'line';
        }

        // desenhar grafico diario ADAPTADA para copy
        function drawCopyDailyChart(data, chartType = 'line') {

            if (copyDetailsChart) {
                copyDetailsChart.destroy();
            }

            const ctx = document.getElementById("copyDailyChart").getContext("2d");

            // labels e dados (utilizei Creative Code, pois nao temos dados diarios simulados, ainda)
            console.log(data);

            const labels = data.map(item => item.code.substring(0, 10) + '...');
            const profitData = data.map(item => item.total_profit);
            const costData = data.map(item => item.total_cost);

            copyDetailsChart = new Chart(ctx, {
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
            const toggleBtn = document.getElementById('toggleDailyChartTypeBtnCopy');
            if (chartType === 'line') {
                toggleBtn.innerHTML = '<i class="fas fa-chart-bar"></i>';
                toggleBtn.title = "Alternar para Colunas";
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-chart-line"></i>';
                toggleBtn.title = "Alternar para Linhas";
            }
        }

        // ALTERNAR TIPO DE GRAFICO DIARIO, LINE OU BAR
        function toggleCopyDailyChartType() {
            copyDailyChartType = copyDailyChartType === 'line' ? 'bar' : 'line';
            drawCopyDailyChart(currentCopyData, copyDailyChartType);
        }

        // RECARREGAR O GRAFICO DIARIO
        function refreshCopyDailyChart() {
            // por enquanto, apenas redesenha com os dados atuais
            drawCopyDailyChart(currentCopyData, copyDailyChartType);
            alert(
                "Gráfico de Performance Diária atualizado (redesenhado com dados existentes, pois precisa atualizar a logica.)."
            );
        }

        // AGRUPA DADOS POR NICHO IGUAL
        function groupDataByNiche(data) {
            const nicheMap = {};

            data.forEach(item => {
                const niche = item.nicho_name ?? 'Não definido';

                if (!nicheMap[niche]) {
                    nicheMap[niche] = {
                        niche_name: niche,
                        total_copies: 0,
                        clicks: 0,
                        conversions: 0,
                        cost: 0,
                        profit: 0,
                        roi: 0
                    };
                }

                nicheMap[niche].total_copies += 1;
                nicheMap[niche].clicks += Number(item.total_clicks) || 0;
                nicheMap[niche].conversions += Number(item.total_conversions) || 0;
                nicheMap[niche].cost += Number(item.total_cost) || 0;
                nicheMap[niche].profit += Number(item.total_profit) || 0;
            });

            // calcular ROI final de cada nicho
            Object.keys(nicheMap).forEach(key => {
                const n = nicheMap[key];
                n.roi = n.cost > 0 ? n.profit / n.cost : 0;
            });

            return Object.values(nicheMap);
        }


        // PREENCHER A TABELA DE NICHO 
        function fillNicheTableCopy(nicheData) {
            const tableBody = document.getElementById('detailsNicheTableBodyCopy');
            const noDataMessage = document.getElementById('nicheNoDataMessageCopy');
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
            <td>${item.total_copies}</td>
            <td>${item.clicks.toLocaleString('pt-BR')}</td>
            <td>${item.conversions.toLocaleString('pt-BR')}</td>
            <td>${formatCurrency(item.cost)}</td>
            <td class="${profitClass}">${formatCurrency(item.profit)}</td>
            <td class="${roiClass}">${formatRoi(item.roi)}</td>
        `;

                tableBody.appendChild(row);
            });

            addNicheTableSortingCopy();
        }


        // DESENHA GRAFICO DE NICHO PARA COPY, ARRUMADO
        function drawCopyNicheChart(nicheData) {
            if (copyNicheChart) {
                copyNicheChart.destroy();
            }

            const ctx = document.getElementById("copyNicheChart").getContext("2d");

            const labels = nicheData.map(item => item.niche_name);
            const profitData = nicheData.map(item => item.profit);
            const costData = nicheData.map(item => item.cost);

            copyNicheChart = new Chart(ctx, {
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

        // ALTERNAR VISAO DE NICHO, ARRUMADO PARA COPY
        function switchNicheViewCopy(viewType) {
            document.querySelectorAll('.niche-content-view').forEach(view => {
                view.style.display = 'none';
                view.classList.remove('active');
            });

            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            document.getElementById(`niche${viewType.charAt(0).toUpperCase() + viewType.slice(1)}ViewCopy`).style.display =
                'block';
            document.getElementById(`niche${viewType.charAt(0).toUpperCase() + viewType.slice(1)}ViewCopy`).classList.add(
                'active');

            // CLASS ATIVA DO CTA/BOTAO ATUALIZADA
            const tabButtons = document.querySelectorAll('.niche-tabs .tab-button');
            if (viewType === 'table' && tabButtons[0]) {
                tabButtons[0].classList.add('active');
            } else if (viewType === 'chart' && tabButtons[1]) {
                tabButtons[1].classList.add('active');
            }


            // se for para o grafico garante que ele seja desenhado/redesenhado (ADAPTADO PARA COPY)
            if (viewType === 'chart' && currentCopyData.length > 0) {
                const nicheData = groupDataByNiche(currentCopyData);
                drawCopyNicheChart(nicheData);
            }
        }

        // ORDENACAO para tabela e tabela de nicho (MANTIDA, mas ID dinamico para copy)
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
        function sortNicheTableCopy(key) {
            sortDetailsTable(key, 'detailsNicheTableBodyCopy');
        }

        function sortInlineCreativesTable(key) {
            // a tabela aninhada in-line nao tem um ID especifico no body, mas podemos encontra-lo
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

        // listener de ordenacao para a tabela de nichos no modal (ADAPTADA para COPY)
        function addNicheTableSortingCopy() {
            document.querySelectorAll('#detailsNicheTableCopy .details-sortable').forEach(header => {
                // remove listeners antigos para evitar duplicacao (clona e substitui)
                header.replaceWith(header.cloneNode(true));
            });

            document.querySelectorAll('#detailsNicheTableCopy .details-sortable').forEach(header => {
                header.addEventListener('click', () => {
                    sortNicheTableCopy(header.dataset.sortKey);
                });
            });
        }

        // adiciona o listener de ordenacao para a tabela de criativos aninhada (ADAPTADA para COPY)
        function addInlineCreativesSorting() {
            document.querySelectorAll('.nested-table:not(#detailsNicheTableCopy) th.sortable').forEach(header => {
                // remove listeners antigos
                header.replaceWith(header.cloneNode(true));
            });

            document.querySelectorAll('.nested-table:not(#detailsNicheTableCopy) th.sortable').forEach(header => {
                header.addEventListener('click', function() {
                    // a ordenacao da tabela in-line agora usa a funcao centralizada para tudo
                    sortInlineCreativesTable(header.dataset.sortKey);
                });
            });
        }


        // ABRIR MODAL DE DETALHES DO COPY, ARRUMADA
        function openEditorDetailsModal(copyName, clicks, copies, profit, roi, copyEmail, creativesJson) {

            document.getElementById("copyDetailsModal").style.display = "flex";
            document.getElementById("copyNameTitle").innerText = copyName;

            document.getElementById("copyRoleEmail").innerHTML =
                `Função: Copywriter | Email: <b>${copyEmail}</b>`;
            document.getElementById('cardTotalClicksCopy').innerHTML = clicks
            document.getElementById('cardTotalProfitCopy').innerHTML = profit
            document.getElementById('cardTotalCopies').innerHTML = copies
            document.getElementById('cardAverageROICopy').innerHTML = roi
            try {
                currentCopyData = JSON.parse(creativesJson);
            } catch (e) {
                console.error("Erro ao parsear JSON:", e);
                currentCopyData = [];
            }

            drawCopyDailyChart(currentCopyData, copyDailyChartType);

            const nicheData = groupDataByNiche(currentCopyData);
            fillNicheTableCopy(nicheData);

            switchNicheViewCopy('table');
        }



        // FUNCOES DO MODAL DE GRAFICO DO CRIATIVO (creativeChartModalCopy) 

        window.creativeChartCopy = null; // ADAPTADA VARIAVEL GLOBAL
        let currentCreativeCodeCopy = null;
        let currentChartTypeCopy = 'line';

        // fecha o grafico de criativo (ADAPTADA PARA COPY)
        function closeCreativeChartCopy() {
            document.getElementById("creativeChartModalCopy").style.display = "none";
        }

        // BUSCA DADOS POR GRAFICO, AJUSTADA
        async function fetchChartDataCopy(creativeCode) {
            document.getElementById("chartCreativeTitleCopy").innerText =
                `${creativeCode}`;

            try {
                const response = await fetch(`/admin/creative-history?creative=${creativeCode}`);

                if (!response.ok) {
                    throw new Error("Erro ao buscar dados do servidor.");
                }

                const data = await response.json();

                // Converter ROI para número (%) caso a API já não devolva
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


        // DESENHA O GRAFICO DE CRIATIVO, ARRUMADA
        function drawCreativeChartCopy(data, chartType) {
            const labels = data.map(item => item.date);
            const profitData = data.map(item => item.profit);
            const costData = data.map(item => item.cost);
            const roiData = data.map(item => item.roi);

            const ctx = document.getElementById("creativeChartCopy").getContext("2d");

            if (window.creativeChartCopy instanceof Chart) {
                window.creativeChartCopy.destroy();
            }

            window.creativeChartCopy = new Chart(ctx, {
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

                        // 📌 ESCALA ÚNICA PARA CUSTO E LUCRO
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

            // Atualiza ícone do botão de alternância
            const toggleBtn = document.getElementById('toggleChartTypeBtnCopy');
            if (chartType === 'line') {
                toggleBtn.innerHTML = '<i class="fas fa-chart-bar"></i>';
                toggleBtn.title = "Alternar para Colunas";
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-chart-line"></i>';
                toggleBtn.title = "Alternar para Linhas";
            }
        }



        // ABRIR O GRAFICO DE CRIATIVOS, ARRUMADA
        async function openCreativeChart(creativeCode, creativeTitle) {
            currentCreativeCodeCopy = creativeCode;
            document.getElementById("creativeChartModalCopy").style.display = "flex";
            document.getElementById("chartCreativeTitleCopy").innerText = creativeTitle;

            const data = await fetchChartDataCopy(creativeCode);
            if (data.length > 0) {
                drawCreativeChartCopy(data, currentChartTypeCopy);
            } else {
                if (window.creativeChartCopy instanceof Chart) {
                    window.creativeChartCopy.destroy();
                }
                document.getElementById("chartCreativeTitleCopy").innerText =
                    `${creativeTitle} (Sem dados no período ainda)`;
            }
        }

        // listeners de inicializacao
        window.onload = () => {
            // inicializa a ordenacao das tabelas aninhadas in-line (tabela de criativos)
            addInlineCreativesSorting();

            // listeners para o grafico diario (ADAPTADOS para copy)
            document.getElementById('toggleDailyChartTypeBtnCopy').addEventListener('click', toggleCopyDailyChartType);
            document.getElementById('refreshDailyChartBtnCopy').addEventListener('click', refreshCopyDailyChart);

            // listeners para o grafico de criativo (ADAPTADOS para copy)
            document.getElementById('refreshChartBtnCopy').addEventListener('click', async () => {
                if (currentCreativeCodeCopy) {
                    const data = await fetchChartDataCopy(currentCreativeCodeCopy);
                    drawCreativeChartCopy(data, currentChartTypeCopy);
                }
            });

            document.getElementById('toggleChartTypeBtnCopy').addEventListener('click', async () => {
                currentChartTypeCopy = currentChartTypeCopy === 'line' ? 'bar' : 'line';

                if (currentCreativeCodeCopy) {
                    const data = await fetchChartDataCopy(currentCreativeCodeCopy);
                    drawCreativeChartCopy(data, currentChartTypeCopy);
                }
            });

            // garante que o estado inicial da visualizacao de nicho esteja na tabela
            switchNicheViewCopy('table');
        };
    </script>
    <script>
        function handleCopyModalOpen(button) {
            const name = button.dataset.name;
            const email = button.dataset.email;
            const json = button.dataset.json;
            const copies = button.dataset.copies
            const clicks = button.dataset.clicks
            const profit = button.dataset.profit
            const roi = button.dataset.roi

            openEditorDetailsModal(name, clicks, copies, profit, roi, email, json);

        }
    </script> {{-- FIM SCRIPT COPY --}}

    {{-- biblioteca Chart.js - GRAFICO JA FEITO - APENAS SO REPLIQUEI --}}
    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endpush
    @endonce

    </section>
    {{-- FIM DA SECTION CRIATIVOS --}}

    </div> {{-- FIM DAS DUAS SCTIONS --}}


    {{-- COMECO SCRIPT DASHBOARD --}}

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
            mrm: {
                color: '#0055ff',
                class: 'glow-mrm'
            },
            ed: {
                color: '#cc0000',
                class: 'glow-ed'
            },
            wl: {
                color: '#00aa00',
                class: 'glow-wl'
            },
            tn: {
                color: '#666666',
                class: 'glow-tn'
            }
        };

        // dados individuais grafico 
        const chartIndividualData = @json($chartIndividualData);
        const chartSynergyData = @json($chartSynergyData);

        const ctx1 = document.getElementById('chartIndividual').getContext('2d');

        window.chart1 = new Chart(ctx1, {
            type: 'bubble',
            data: {
                datasets: [{
                    label: 'Copywriters',
                    data: chartIndividualData,
                    backgroundColor: 'rgba(0,85,255,0.75)',
                    hoverBackgroundColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'ROI',
                            color: '#fff'
                        },
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Copies Produzidas',
                            color: '#fff'
                        },
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label(context) {
                                const d = context.raw;
                                return [
                                    d.name,
                                    `Produzidos: ${d.y}`,
                                    `ROI: ${d.x}`,
                                    `Profit: $${d.profit.toLocaleString('en-US')}`
                                ];
                            }
                        }
                    }
                }
            }
        });

        const ctxSynergy = document.getElementById('chartSynergy').getContext('2d');

        window.chartSynergy = new Chart(ctxSynergy, {
            type: 'bubble',
            data: {
                datasets: [{
                    label: 'Duplas',
                    data: chartSynergyData,
                    backgroundColor: 'rgba(0, 170, 255, 0.75)',
                    hoverBackgroundColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'ROI',
                            color: '#fff'
                        },
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Quantidade Produzida',
                            color: '#fff'
                        },
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.05)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label(context) {
                                const d = context.raw;
                                return [
                                    `Dupla: ${d.label}`,
                                    `Editor: ${d.editor}`,
                                    `Produzidos: ${d.produced}`,
                                    `ROI: ${(d.roi * 100).toFixed(2)}%`,
                                    `Profit: $${d.profit.toLocaleString('en-US')}`
                                ];
                            }
                        }
                    }
                }
            }
        });


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

    {{-- FIM DE SCRIPT DASHBOARD --}}

</x-layout>
