<x-layout>

    @php
        $isCopy = $type === 'copywriters';
    @endphp

    <div class="copy-main-wrapper">

        <header class="titan-header-container">
            <div class="header-main-nav">
                <div class="header-brand">
                    <img src="/img/img-admin/logo titan.png" alt="Titan Logo" class="sidebar-logo">
                    <span class="brand-name">
                        {{ $isCopy ? 'Copywriters' : 'Editores' }}
                    </span>
                </div>

                <div class="header-metric-selector">
                    <span class="header-label">Escolha qual métrica deseja visualizar?</span>
                    <div class="header-button-group">
                        <button id="btn-dashboard" class="btn-toggle active"
                            onclick="switchView('dashboard')">Dashboard</button>
                        <button id="btn-creatives" class="btn-toggle inactive"
                            onclick="switchView('creatives')">Criativos</button>
                    </div>
                </div>

                {{-- Andre verifica esse codigo para mim, estou com duvidas se esta correto, para copy, sim. Mas para Editors eu nao sei --}}

                <div class="header-filter-area">
                    <form action="{{ route('admin.agents', $type) }}" class="header-filter-form">
                        <div class="filter-wrapper">
                            <x-date-range name="date" :from="$startDate" :to="$endDate" />
                        </div>
                        <button type="submit" class="btn-header-filter">
                            <i class="fas fa-filter"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>


        {{-- SECTION NO HEADER AGORA NA OTIMIZACAO --}}
        {{-- <div class="title-section">
            <h1 class="main-title">Produção De Editores</h1>
            <p class="sub-title">Métricas De Editor</p>
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
        </div> --}}
        {{-- FIM DA SECTION QUE ESTA NO HEADER AGORA OTIMIZACAO --}}

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
                            @if ($totalTestado > 0)
                                <span class="mini-value">@percent0($emPotencial/$totalTestado) | @int_number($emPotencial)
                                    Ads</span>
                            @else
                                <span class="mini-value">0% | 0 Ads</span>
                            @endif
                        </div>
                        <div class="mini-card-outline secondary-border">
                            <span class="mini-label">Taxa de Acerto</span>
                            @if ($totalTestado > 0)
                                <span class="mini-value">@percent0($validados/$totalTestado) | @int_number($validados)
                                    Ads</span>
                            @else
                                <span class="mini-value">0 | 0 Ads</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="secondary-metrics-grid">
                <div class="small-metric-card">
                    <span class="small-label">Melhor Nicho</span>
                    @foreach ($topRoiNicho as $topRoi)
                        <span class="small-data">{{ $topRoi->sigla }} | <span
                                class="highlight-roi">@percent0($topRoi->roi) ROI</span></span> <br>
                    @endforeach
                </div>
                <div class="small-metric-card">
                    <span class="small-label">Maior Nicho</span>
                    @foreach ($topProfitNicho as $topNicho)
                        <span class="small-data">{{ $topNicho->sigla }} | <span
                                class="highlight-profit">@percent0($topNicho->total_profit/$totalProfitNichos)
                                do Profit</span></span> <br>
                    @endforeach
                </div>

                <div class="small-metric-card">
                    <span class="small-label">
                        {{ $isCopy ? 'Melhor Copywriter' : 'Melhor Editor' }}
                    </span>
                    @foreach ($topAgentsRoi as $topRoi)
                        <span class="small-data">{{ $topRoi->name }} | <span class="highlight-roi">@percent($topRoi->metrics->sum('total_profit') / $topRoi->metrics->sum('total_cost'))
                                ROI</span></span> <br>
                    @endforeach
                </div>
                <div class="small-metric-card">
                    <span class="small-label">
                        {{ $isCopy ? 'Maior Copywriter' : 'Maior Editor' }}
                    </span>
                    @foreach ($topAgentsProfit as $topProfit)
                        <span class="small-data"> {{ $topProfit->name }} | <span class="highlight-profit">
                                @if ($totalProfitAgents)
                                    @percent($topProfit->metrics->sum('total_profit') / $totalProfitAgents)
                                @else
                                    0%
                                @endif
                                do Profit
                            </span></span> <br>
                    @endforeach
                </div>

                <div class="small-metric-card">
                    <span class="small-label">Melhor Dupla</span>
                    @foreach ($topDuplaRoi as $topRoi)
                        <span class="small-data">{{ $topRoi->dupla }} | <span class="highlight-roi">@percent($topRoi->roi)
                                ROI</span></span> <br>
                    @endforeach
                </div>
                <div class="small-metric-card">
                    <span class="small-label">Maior Dupla</span>
                    @foreach ($topDuplaProfit as $topProfit)
                        <span class="small-data">{{ $topProfit->dupla }} | <span class="highlight-profit">
                                @if ($totalProfitAgents)
                                    @percent($topProfit->total_profit / $totalProfitAgents)
                                @else
                                    0%
                                @endif
                                do Profit
                            </span></span> <br>
                    @endforeach
                </div>
            </div>

            <div class="analytics-charts-section">

                <div class="section-divider">
                    <h2 class="display-title-performance">Performance Individual <span
                            class="title-italic-light">Geral</span></h2>
                </div>

                <div class="niche-selector-bar">
                    <div class="niche-block all active" data-niche="all" data-name="all" style="width: 10%;">
                        <div class="niche-badge">
                            <span class="perc">100%</span>
                            <span class="name">Todos</span>
                        </div>
                    </div>

                    @foreach ($nichosBar as $nicho)
                        <div class="niche-block {{ strtolower($nicho->sigla) }}"
                            data-niche="{{ strtolower($nicho->sigla) }}" data-name="{{ $nicho->nicho }}"
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
                        <form method="GET" action="{{ route('admin.agents', $type) }}" id="copySelectForm">

                            {{-- manter filtros de data --}}
                            <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                            <input type="hidden" name="date_to" value="{{ request('date_to') }}">

                            <select name="{{ $isCopy ? 'copy_id' : 'editor_id' }}" class="copy-select"
                                onchange="updateSynergyChart(this.value)">

                                @foreach ($agents as $editor)
                                    <option value="{{ $editor->id }}"
                                        {{ ($selectedEditorId ?? null) == $editor->id ? 'selected' : '' }}>
                                        {{ $editor->name }}
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
                <h3 class="section-title">
                    Produção {{ $isCopy ? 'Copywriters' : 'Editores' }}
                </h3>

                <form class="filters-grid filters-grid-production">
                    {{-- <div class="filter-group">
                <x-date-range name="date" :from="$startDate" :to="$endDate" label="Intervalo de Datas" />
            </div> --}}
                    <div class="filter-group">
                        {{-- Adaptado para Copywriters --}}
                        <x-multiselect name="{{ $isCopy ? 'copywriters' : 'editors' }}"
                            label="{{ $isCopy ? 'Copywriters' : 'Editores' }}" :options="$allAgents" />
                    </div>

                    <div class="filter-submit-area filter-submit-area-production">
                        <button type="submit" class="btn-filter">FILTRAR</button>
                    </div>
                </form>
            </div>

            {{-- COPIES produzidas (taabela principal) --}}
            <div class="copy-production-section glass-card table-shadow">
                <h3 class="section-title">
                    Copies Produzidas por {{ $isCopy ? 'Copywriters' : 'Editores' }}
                </h3>

                <div class="table-responsive">
                    <table class="metrics-main-table">
                        <thead>
                            <tr>
                                <th class="header-editor sortable-main" data-sort-key="name">
                                    Nome <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="produced">
                                    Produzido <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="tested">
                                    Testado <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="potential">
                                    Potencial <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="validated">
                                    Validados <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="winrate">
                                    Win/Rate <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="clicks">
                                    Cliques <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="conversions">
                                    Conversões <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="cost">
                                    Custo <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="profit">
                                    Lucro <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-metrics sortable-main" data-sort-key="roi">
                                    ROI (%) <i class="fas fa-sort"></i>
                                </th>

                                <th class="header-action">Detalhes</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- $copies --}}
                            @foreach ($agents as $editor)
                                @php
                                    // adaptando para usar as variaveis de Copywriter

                                    // $creativesByAgent baseado no codigo padrao usado, mas precisa verificar no controller para as info vir corretas
                                    $creativesJson = json_encode($editor->metrics ?? collect());
                                    $key = 'copywriter-' . $editor->id;
                                @endphp

                                {{-- linha principal --}}
                                <tr class="editor-row clickable-row" data-editor-id="{{ $editor->id }}"
                                    onclick="toggleDetails('{{ $key }}')">
                                    <td class="editor-name-cell">
                                        <span class="arrow-indicator"><i class="fas fa-chevron-right"></i></span>
                                        <span class="fw-bold">{{ $editor->name }}</span>
                                    </td>
                                    <td>{{ $editor->produzidos }}</td>
                                    <td>{{ $editor->metrics->sum('testados') }}
                                    <td>@int_number($editor->metrics->sum('em_potencial'))</td>
                                    <td>@int_number($editor->metrics->sum('validados'))</td>
                                    <td>
                                        @if (count($editor->metrics))
                                            @percent($editor->metrics->sum('validados') / count($editor->metrics))
                                        @else
                                            @percent(0)
                                        @endif
                                    </td>
                                    <td>@int_number($editor->metrics->sum('total_clicks'))</td>
                                    <td>@int_number($editor->metrics->sum('total_conversions'))</td>
                                    <td>@dollar($editor->metrics->sum('total_cost'))</td>
                                    {{-- lucro com cor condicional --}}
                                    <td
                                        class="{{ $editor->metrics->sum('total_profit') >= 0 ? 'positive-value' : 'negative-value' }}">
                                        @dollar($editor->metrics->sum('total_profit'))
                                    </td>
                                    {{-- ROI com cor condicional --}}
                                    <td
                                        class="{{ $editor->metrics->sum('total_profit') >= 0 ? 'positive-value' : 'negative-value' }}">
                                        {{ $editor->metrics->sum('total_cost') > 0
                                            ? number_format(($editor->metrics->sum('total_profit') / $editor->metrics->sum('total_cost')) * 100, 2, ',', '.')
                                            : 0 }}%
                                    </td>
                                    {{-- Botao CTA para a Sub Visualizacao 2.0 --}}
                                    <td class="action-cell">
                                        <button class="btn-subview-cta" data-name="{{ $editor->name }}"
                                            data-email="{{ $editor->email }}"
                                            data-json='@json($editor->metrics ?? [])' data-clicks="@int_number($editor->metrics->sum('total_clicks'))"
                                            data-copies="@int_number(count($editor->metrics->where('status', 'ok'))) / {{ count($editor->metrics) }}"
                                            data-profit="@dollar($editor->metrics->sum('total_profit'))"
                                            data-roi="{{ $editor->metrics->sum('total_cost') > 0
                                                ? number_format(($editor->metrics->sum('total_profit') / $editor->metrics->sum('total_cost')) * 100, 2, ',', '.')
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
                                            <h4 class="nested-table-title">Criativos de {{ $editor->name }}</h4>
                                            <table class="nested-table">
                                                <thead>
                                                    <tr>
                                                        <th data-sort-key="creative_code" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Criativo<i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="date" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Data <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="potential" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Potencial <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="clicks" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Cliques <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="conversions" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Conversões <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="conversions" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            CPC <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="conversions" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            EPC <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="cost" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Custo <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="profit" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Lucro <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="revenue" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            Receita <i class="fas fa-sort"></i>
                                                        </th>
                                                        <th data-sort-key="roi" class="sortable"
                                                            style="font-size: 0.8rem;">
                                                            ROI <i class="fas fa-sort"></i>
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
                            @foreach ($editor->metrics as $cr)
                                <tr
                                    class="creative-detail-row {{ $cr->total_profit > 0 ? 'creative-green' : ($cr->total_profit < 0 ? 'creative-red' : '') }}">
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
                    @if (($creativesByAgent[$editor->user_id] ?? collect())->isEmpty())
                        <p class="no-data-message">Nenhum criativo encontrado para este editor
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
                <h3 id="copyDetailsTitle" class="main-title-hidden">Detalhes do Editor: [Nome]</h3>
                <button class="creative-modal-close" onclick="closeCopyDetailsModal()">×</button>
            </div>

            <div class="subview-body-container">

                {{-- CARD DE INFORMACOES DO COPY --}}
                <div class="editor-info-card glass-card">
                    <div class="editor-info-header">
                        <h3 id="copyNameTitle" class="editor-name-title">Nome do Editor</h3>
                        <p id="copyRoleEmail" class="editor-role-email">Função: Editor | Email: email@corp.com</p>
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
                        <h4 class="chart-section-title">Performance Diária (Lucro vs Custo) - Por Editor</h4>
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
        function buildChartDataByNiche(nicho = null) {

            // 🔹 maiores valores reais (separados)
            const profits = rawEditorsData.flatMap(editor =>
                Object.values(editor.by_niche || {}).map(n => n.total_profit || 0)
            );

            const maxPositiveProfit = Math.max(...profits.filter(p => p > 0), 1);
            const maxNegativeProfit = Math.min(...profits.filter(p => p < 0), -1);

            const minR = 6;
            const maxRPositive = 50;
            const maxRNegative = 22; // 👈 prejuízo nunca domina o gráfico

            return rawEditorsData.map(editor => {

                let data;

                // 🔹 soma geral
                if (!nicho || nicho === 'all') {

                    const totals = Object.values(editor.by_niche || {});

                    const total_profit = totals.reduce((s, n) => s + (n.total_profit || 0), 0);
                    const total_cost = totals.reduce((s, n) => s + (n.total_cost || 0), 0);
                    const produced = totals.reduce((s, n) => s + (n.produced || 0), 0);
                    const tested = totals.reduce((s, n) => s + (n.tested || 0), 0);

                    data = {
                        total_profit,
                        total_cost,
                        produced,
                        tested
                    };

                } else {
                    data = editor.by_niche?.[nicho];
                    if (!data) return null;
                }

                const profit = data.total_profit || 0;
                const cost = data.total_cost || 0;

                let r;

                if (profit > 0) {
                    // 🟢 lucro → escala log normalizada
                    r =
                        minR +
                        Math.pow(
                            Math.log10(profit) / Math.log10(maxPositiveProfit),
                            1.3
                        ) * (maxRPositive - minR);

                } else if (profit < 0) {
                    // 🔴 prejuízo → escala própria e limitada
                    r =
                        minR +
                        Math.pow(
                            Math.log10(Math.abs(profit)) / Math.log10(Math.abs(maxNegativeProfit)),
                            1.1
                        ) * (maxRNegative - minR);

                } else {
                    r = minR;
                }

                return {
                    x: cost > 0 ? +(profit / cost).toFixed(2) : 0,
                    y: data.produced || 0,
                    r,

                    label: editor.label,
                    name: editor.name,
                    profit: +profit.toFixed(2),
                    tested: data.tested || 0,

                    // 👇 cor por sinal
                    backgroundColor: profit >= 0 ?
                        'rgba(34,197,94,0.8)' // verde
                        :
                        'rgba(239,68,68,0.8)' // vermelho
                };

            }).filter(Boolean);
        }

        function updateIndividualChart(nicho = 'all') {
            const newData = buildChartDataByNiche(nicho);

            window.chart1.data.datasets[0].data = newData;
            window.chart1.update();
        }
    </script>

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
            // pega a details-row atualmente aberta
            const visibleDetailsRow = document.querySelector('.details-row[style*="table-row"]');
            if (!visibleDetailsRow) return;

            const table = visibleDetailsRow.querySelector('.nested-table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            const header = table.querySelector(`th[data-sort-key="${key}"]`);

            // estado por tabela
            let direction = header.dataset.sortDir === 'asc' ? 'desc' : 'asc';
            header.dataset.sortDir = direction;

            // reseta ícones só se existirem
            table.querySelectorAll('th.sortable i').forEach(i => {
                i.className = 'fas fa-sort';
            });

            // seta ícone apenas se o <i> existir
            const icon = header.querySelector('i');
            if (icon) {
                icon.className =
                    direction === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
            }


            const colIndex = Array.from(header.parentNode.children).indexOf(header);

            rows.sort((a, b) => {
                let aVal = a.children[colIndex].innerText.trim();
                let bVal = b.children[colIndex].innerText.trim();

                // texto
                if (key === 'creative_code' || key === 'date') {
                    aVal = aVal.toLowerCase();
                    bVal = bVal.toLowerCase();
                }
                // número
                else {
                    aVal = parseFloat(aVal.replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;
                    bVal = parseFloat(bVal.replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;
                }

                if (aVal < bVal) return direction === 'asc' ? -1 : 1;
                if (aVal > bVal) return direction === 'asc' ? 1 : -1;
                return 0;
            });

            rows.forEach(row => tbody.appendChild(row));
        }

        function addInlineCreativesSorting() {
            document.querySelectorAll('.nested-table th.sortable').forEach(th => {
                th.addEventListener('click', () => {
                    sortInlineCreativesTable(th.dataset.sortKey);
                });
            });
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

            //iniciando grafico de sinergia
            updateSynergyChart({{ $selectedEditorId ?? 'null' }});
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
            },
            db: {
                color: '#0E336C',
                class: 'glow-db'
            }

        };

        // dados individuais grafico 
        const rawEditorsData = @json($chartIndividualData);

        const chartSynergyData = @json($chartSynergyData);

        const ctx1 = document.getElementById('chartIndividual').getContext('2d');

        window.chart1 = new Chart(ctx1, {
            type: 'bubble',
            data: {
                datasets: [{
                    label: 'Editores',
                    data: buildChartDataByNiche('all'),
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
                                    `Testados: ${d.tested}`,
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

        // dados para o grafico de sinergia
        const maxSynergyProfit = Math.max(
            ...chartSynergyData.map(d => Math.abs(d.profit || 0)),
            1
        );

        const synergyBubbleData = chartSynergyData.map(d => {
            const minR = 6;
            const maxR = 42;

            const profit = Math.abs(d.profit || 0);

            const r =
                profit <= 0 ?
                minR :
                minR +
                Math.pow(
                    Math.log10(profit) / Math.log10(maxSynergyProfit),
                    1.2
                ) * (maxR - minR);

            return {
                ...d,
                r
            };
        });


        window.chartSynergy = new Chart(ctxSynergy, {
            type: 'bubble',
            data: {
                datasets: [{
                    label: 'Duplas',
                    data: synergyBubbleData,
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
                                    `Produzidos: ${d.produced}`,
                                    `Testados: ${d.testados}`,
                                    `ROI: ${(d.roi * 100).toFixed(2)}%`,
                                    `Profit: $${d.profit.toLocaleString('en-US')}`
                                ];
                            }
                        }
                    }

                }
            }
        });


        //atualizando nichos
        document.querySelectorAll('.niche-block').forEach(nicho => {
            nicho.addEventListener('click', () => {
                updateIndividualChart(nicho.dataset.name);
                updateNiche(nicho.dataset.niche);
            });
        });


        // funcao para trocar de nicho e glow
        function updateNiche(nicheKey) {

            // fallback seguro
            const config = nicheConfigs[nicheKey] ?? {
                color: '#888',
                class: 'glow-mrm'
            };

            // ativa botão correto
            document.querySelectorAll('.niche-block')
                .forEach(b => b.classList.remove('active'));

            const active = document.querySelector(`[data-niche="${nicheKey}"]`);
            if (active) active.classList.add('active');

            // troca glow
            document.querySelectorAll('.graph-main-container').forEach(c => {
                c.className = 'graph-main-container ' + config.class;
            });

            // atualiza cor das bolhas
            if (window.chart1) {
                window.chart1.data.datasets[0].backgroundColor = config.color;
                window.chart1.update();
            }
        }
    </script>

    <script>
        function buildChartDataByNiche(nicho = null) {

            // 🔹 maiores valores reais (separados)
            const profits = rawEditorsData.flatMap(editor =>
                Object.values(editor.by_niche || {}).map(n => n.total_profit || 0)
            );

            const maxPositiveProfit = Math.max(...profits.filter(p => p > 0), 1);
            const maxNegativeProfit = Math.min(...profits.filter(p => p < 0), -1);

            const minR = 6;
            const maxRPositive = 50;
            const maxRNegative = 22; // 👈 prejuízo nunca domina o gráfico

            return rawEditorsData.map(editor => {

                let data;

                // 🔹 soma geral
                if (!nicho || nicho === 'all') {

                    const totals = Object.values(editor.by_niche || {});

                    const total_profit = totals.reduce((s, n) => s + (n.total_profit || 0), 0);
                    const total_cost = totals.reduce((s, n) => s + (n.total_cost || 0), 0);
                    const produced = totals.reduce((s, n) => s + (n.produced || 0), 0);
                    const tested = totals.reduce((s, n) => s + (n.tested || 0), 0);

                    data = {
                        total_profit,
                        total_cost,
                        produced,
                        tested,
                    };

                } else {
                    data = editor.by_niche?.[nicho];
                    if (!data) return null;
                }

                const profit = data.total_profit || 0;
                const cost = data.total_cost || 0;

                let r;

                if (profit > 0) {
                    // 🟢 lucro → escala log normalizada
                    r =
                        minR +
                        Math.pow(
                            Math.log10(profit) / Math.log10(maxPositiveProfit),
                            1.3
                        ) * (maxRPositive - minR);

                } else if (profit < 0) {
                    // 🔴 prejuízo → escala própria e limitada
                    r =
                        minR +
                        Math.pow(
                            Math.log10(Math.abs(profit)) / Math.log10(Math.abs(maxNegativeProfit)),
                            1.1
                        ) * (maxRNegative - minR);

                } else {
                    r = minR;
                }

                return {
                    x: cost > 0 ? +(profit / cost).toFixed(2) : 0,
                    y: data.produced || 0,
                    r,

                    label: editor.label,
                    name: editor.name,
                    profit: +profit.toFixed(2),
                    tested: data.tested || 0,

                    // 👇 cor por sinal
                    backgroundColor: profit >= 0 ?
                        'rgba(34,197,94,0.8)' // verde
                        :
                        'rgba(239,68,68,0.8)' // vermelho
                };

            }).filter(Boolean);
        }




        function updateIndividualChart(nicho = 'all') {
            const newData = buildChartDataByNiche(nicho);
            window.chart1.data.datasets[0].data = newData;
            window.chart1.update();
        }
    </script>

    {{-- atualiza grafico de sinergia --}}
    <script>
        async function updateSynergyChart(editorId = null) {

            const params = new URLSearchParams({
                {{ $isCopy ? 'copy_id' : 'editor_id' }}: editorId ?? '',
                date_from: document.querySelector('input[name="date_from"]')?.value ?? '',
                date_to: document.querySelector('input[name="date_to"]')?.value ?? '',
            });

            const response = await fetch(`/admin/{{ $type }}/synergy?${params.toString()}`);

            const data = await response.json();

            // normaliza raio (igual você já faz)
            const maxProfit = Math.max(...data.map(d => Math.abs(d.profit)), 1);

            const bubbleData = data.map(d => {

                const minR = 6;
                const maxR = 42;

                const profit = Math.abs(d.profit || 0);

                const r = profit <= 0 ?
                    minR :
                    minR + Math.pow(
                        Math.log10(profit) / Math.log10(maxProfit),
                        1.2
                    ) * (maxR - minR);

                return {
                    x: d.roi,
                    y: d.produced,
                    r,

                    label: d.label,
                    editor: d.editor,

                    produced: d.produced ?? 0,
                    testados: d.testados ?? 0,

                    profit: d.profit ?? 0,
                    roi: d.roi ?? 0
                };
            });


            window.chartSynergy.data.datasets[0].data = bubbleData;
            window.chartSynergy.update();
        }
    </script>

    {{-- script de ordenação da tabela --}}
    <script>
        let mainSortKey = '';
        let mainSortDirection = 'asc';

        function sortMainTable(key) {
            const table = document.querySelector('.metrics-main-table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr.editor-row'));

            const header = table.querySelector(`th[data-sort-key="${key}"]`);

            // alterna direção
            if (mainSortKey === key) {
                mainSortDirection = mainSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                mainSortKey = key;
                mainSortDirection = 'asc';
            }

            // reset ícones
            table.querySelectorAll('th.sortable-main i').forEach(i => i.className = 'fas fa-sort');

            header.querySelector('i').className =
                mainSortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';

            rows.sort((a, b) => {
                const aCell = a.children[header.cellIndex].innerText.trim();
                const bCell = b.children[header.cellIndex].innerText.trim();

                let aVal, bVal;

                if (key === 'name') {
                    aVal = aCell.toLowerCase();
                    bVal = bCell.toLowerCase();
                } else {
                    aVal = parseFloat(aCell.replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;
                    bVal = parseFloat(bCell.replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;
                }

                if (aVal < bVal) return mainSortDirection === 'asc' ? -1 : 1;
                if (aVal > bVal) return mainSortDirection === 'asc' ? 1 : -1;
                return 0;
            });

            rows.forEach(row => tbody.appendChild(row));
        }

        // listeners
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('th.sortable-main').forEach(th => {
                th.addEventListener('click', () => sortMainTable(th.dataset.sortKey));
            });
        });
    </script>


    {{-- FIM DE SCRIPT DASHBOARD --}}

</x-layout>
