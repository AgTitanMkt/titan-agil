<x-layout>

    <style>
        /* -------------------------
   Seta animada
-------------------------- */
        :root {
            --bar-width: 60px;
            /* tamanho inicial */
        }

        .alias-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .arrow-icon {
            font-size: 0.9rem;
            opacity: 0.7;
            transition: transform 0.25s ease, opacity 0.2s ease;
        }

        .arrow-icon.open {
            transform: rotate(180deg);
            opacity: 1;
        }


        /* -------------------------
   Container expandido
-------------------------- */
        .accounts-expand {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 18px 22px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(8px);
            margin-top: 12px;
        }


        /* -------------------------
   Linhas internas (contas)
-------------------------- */
        .account-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr;
            padding: 14px 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(6px);
            transition: all 0.25s;
            align-items: center;
        }

        .account-row:hover {
            background: rgba(255, 255, 255, 0.10);
            transform: translateX(4px);
        }


        /* -------------------------
   Colunas
-------------------------- */
        .acc-col {
            color: #e4e6eb;
            font-size: 0.85rem;
        }

        /* Nome da conta */
        .acc-main span {
            font-size: 0.92rem;
            font-weight: 600;
        }

        /* ROI Text */
        .positive {
            color: #34d399;
            font-weight: 600;
        }

        .negative {
            color: #ef4444;
            font-weight: 600;
        }


        /* -------------------------
   Destaque Visual ROI Negativo
-------------------------- */
        .row-negative {
            background: rgba(255, 60, 60, 0.10);
            border-left: 3px solid rgba(255, 80, 80, 0.5);
        }

        .row-negative:hover {
            background: rgba(255, 60, 60, 0.18);
        }

        /* ROI positivo */
        .row-positive {
            background: rgba(0, 255, 150, 0.06);
        }

        .row-positive:hover {
            background: rgba(0, 255, 150, 0.10);
        }


        /* -------------------------
   Responsividade
-------------------------- */
        @media (max-width: 1100px) {
            .account-row {
                grid-template-columns: 1.5fr 1fr 1fr 1fr 1fr;
            }

            .acc-col:nth-child(6),
            .acc-col:nth-child(7) {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .account-row {
                grid-template-columns: 1fr 1fr;
            }

            .acc-col:nth-child(n+3) {
                display: none;
            }
        }

        /* Linha neutra — ROI = 0 */
        .row-neutral {
            background: rgba(255, 255, 255, 0.06);
            border-left: 3px solid transparent;
        }

        .row-neutral:hover {
            background: rgba(255, 255, 255, 0.10);
        }

        /* filtros */
        .filters-dataset {
            padding: 20px;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .filters-title {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .filters-grid-dataset {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .filter-field label {
            font-size: 0.85rem;
            opacity: .8;
        }

        .filter-field input,
        .filter-field select {
            width: 100%;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 8px 10px;
            border-radius: 6px;
            color: white;
        }

        .filter-multi {
            height: 90px;
        }

        .dual-input {
            display: flex;
            gap: 10px;
        }

        .filter-actions {
            grid-column: span 4;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-clear {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 14px;
            border-radius: 6px;
            color: white;
        }

        .btn-filter {
            background: var(--accent-blue);
            padding: 8px 14px;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            border: none;
        }

        #metricsFilterForm label {
            color: white;
            font-size: 0.85rem;
        }

        .zoom-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 20px;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            transition: background .2s ease;
        }

        .zoom-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .zoom-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: end;
        }

        .bar {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: flex-end;
        }

        .bar-label {
            position: absolute;
            top: -18px;
            /* distância acima da barra */
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            pointer-events: none;
            text-align: center;
        }

        .chart-legend {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 10px;
            justify-content: end;
            flex-wrap: wrap;
        }

        .chart-legend div {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            display: inline-block;
        }

        /* cores de cada plataforma */
        .legend-color.facebook {
            background: #1877f2;
        }

        .legend-color.tiktok {
            background: #000;
        }

        .legend-color.taboola {
            background: #1b75bc;
        }

        .legend-color.google {
            background: #009ed3;
        }

        .legend-color.native {
            background: #8b5cf6;
        }

        /* estado empilhado */
        .bars.stacked {
            flex-direction: column;
            align-items: stretch;
        }

        .bars.stacked .bar {
            width: var(--bar-width);
            margin-bottom: 1px;
            border-radius: 0;
        }

        .bars.stacked .bar:first-child {
            border-radius: 6px 6px 0 0;
        }

        .bars.stacked .bar:last-child {
            border-radius: 0 0 6px 6px;
        }
        .chart-mode-controls {
            margin-bottom: 10px;
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .btn-modes {
            background: rgba(255, 255, 255, 0.16);
            color: white;
            padding: 7px;
            border-radius: 10px;
        }

        .expected-trendline {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 30;
        }

        .expected-trendline svg {
            width: 100%;
            height: 100%;
        }

        .expected-trendline path {
            fill: none;
            stroke: #FFD700;
            stroke-width: 0.3px;
            stroke-linecap: round;
            stroke-linejoin: round;

            /* REMOVA a linha pontilhada */
            stroke-dasharray: none;

            /* glow */
            filter: drop-shadow(0 0 4px rgba(255, 215, 0, 0.5));

            opacity: 0;
            /* só aparece no empilhado */
            transition: opacity .25s ease;
        }
    </style>

    <h2 class="dashboard-page-title">Faturamento</h2>
    <p class="dashboard-page-subtitle">Monitoramento completo da saúde financeira da Titan de
        <b>{{ $startDate->format('d/m/Y') }} à {{ $endDate->format('d/m/Y') }}</b>.
        Ultima atualização em: {{ $lastUpdate }}

    </p>

    <div class="metrics-top-grid">
        <div class="metric-card glass-card">
            <p class="metric-title">Faturamento Total</p>
            <h3 class="metric-value">@dollar($totals['cost'] + $totals['profit'])</h3>
            {{-- <div class="metric-footer">
                <span class="metric-change positive">13,4%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                    <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder">
                    <path d="M0 25 L20 15 L40 20 L60 10 L80 18 L100 5" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
                </div>
            </div> --}}
        </div>

        <div class="metric-card glass-card">
            <p class="metric-title">Custo Total</p>
            <h3 class="metric-value">@dollar($totals['cost'])</h3>
            {{-- <div class="metric-footer">
                <span class="metric-change negative">-5,1%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder">
                     <path d="M0 5 L20 15 L40 10 L60 20 L80 15 L100 25" fill="none" stroke="#ff4d4d" stroke-width="2"/></svg>
                </div>
            </div> --}}
        </div>

        <div class="metric-card glass-card">
            <p class="metric-title">Lucro Total</p>
            <h3 class="metric-value">@dollar($totals['profit'])</h3>
            {{-- <div class="metric-footer">
                <span class="metric-change positive">18,5%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder">
                     <path d="M0 10 L20 20 L40 15 L60 25 L80 12 L100 5" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
                </div>
            </div> --}}
        </div>

        <div class="metric-card glass-card">
            <p class="metric-title">ROI (%)</p>
            <h3 class="metric-value">{{ number_format($totals['roi'] * 100, 2, ',', '.') }}</h3>
            {{-- <div class="metric-footer">
                <span class="metric-change positive">10,1%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder">
                     <path d="M0 10 L20 15 L40 8 L60 20 L80 15 L100 25" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="filters-dataset glass-card">
        <h3 class="filters-title">Filtrar métricas</h3>

        <form method="GET" id="metricsFilterForm" class="filters-grid-dataset">
            <x-date-range name="date" :from="$startDate" :to="$endDate" label="Intervalo de Datas" />

            {{-- Plataformas (alias) --}}
            <x-multiselect name="alias" label="Plataformas" :options="$sources->pluck('alias')->toArray()" :selected="request('alias', [])"
                placeholder="Selecione uma ou mais plataformas">
            </x-multiselect>
            {{-- Contas (source) --}}
            {{-- <x-multiselect name="sources" label="Contas" :options="$allSources" :selected="request('sources', [])"
                placeholder="Selecione uma ou mais contas">
            </x-multiselect> --}}

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Aplicar</button>
                <a href="{{ route('admin.dashboard') }}" class="btn-clear">Limpar</a>
            </div>

        </form>
    </div>


    <div class="chart-section glass-card">
        <h3 class="section-title">Gráfico de Faturamento</h3>
        <div class="chart-mode-controls">
            <button id="modeSeparated" class="btn-modes">Separado</button>
            <button id="modeStacked" class="btn-modes">Empilhado</button>
        </div>

        <div class="zoom-controls">
            <button id="zoomOut" class="zoom-btn">−</button>
            <span style="color:white; opacity:.8;">Zoom</span>
            <button id="zoomIn" class="zoom-btn">+</button>
            <button id="zoomReset" class="zoom-reset-btn">Reset</button>
        </div>

        <div class="chart-legend">
            <span class="legend-label">Plataformas</span>

            <button type="button" class="legend-item" data-alias="Facebook">
                <span class="legend-color facebook"></span> Facebook
            </button>
            <button type="button" class="legend-item" data-alias="TikTok">
                <span class="legend-color tiktok"></span> TikTok
            </button>
            <button type="button" class="legend-item" data-alias="Taboola">
                <span class="legend-color taboola"></span> Taboola
            </button>
            <button type="button" class="legend-item" data-alias="Native">
                <span class="legend-color native"></span> Native
            </button>
        </div>


        <p class="chart-subtitle">Visualização completa do faturamento mensal ao longo do ano</p>

        <div class="revenue-chart-placeholder">
            <div id="chart-tooltip" class="chart-tooltip"></div>
            <div class="chart-bar-grid">
                <div class="expected-trendline" id="expectedTrendline"></div>
                @foreach ($chartData as $month => $platforms)
                    @php
                        // soma todas as plataformas do mês
                        $monthTotal = array_sum($platforms);
                    @endphp
                    <div class="month-group" data-total="{{ $monthTotal }}">
                        <div class="bars">
                            @foreach ($aliases as $alias)
                                @php
                                    $value = $platforms[$alias];
                                    $height = ($value / $maxValue) * 18.6; // rem
                                @endphp

                                @if ($height > 0)
                                    <div class="bar {{ strtolower($alias) }}-bar" style="height: {{ $height }}rem"
                                        data-alias="{{ $alias }}" data-month="{{ $month }}"
                                        data-value="@dollar($value)" data-raw="{{ $value }}">
                                        <span class="bar-label">@dollar($value)</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <span class="month-label">{{ $month }}</span>

                    </div>
                @endforeach


            </div>
        </div>
    </div>

    {{-- CSS do grafico --}}
    <style>
        .chart-section {
            position: relative;
            overflow: visible !important;
        }

        .revenue-chart-placeholder {
            height: 27rem;
            padding-bottom: 10px;
            position: relative;
            overflow-x: auto;
            overflow-y: visible !important; /* impede o SVG de sumir */
        }

        .chart-bar-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            /* 12 meses */
            align-items: end;
            height: 24.5rem;
            padding: 20px 30px 10px 30px;
            column-gap: 2rem;
            /* menor espaçamento */
            position: relative;
            z-index: 10;
        }

        .month-group {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            gap: 6px;
            position: relative;
        }

        .month-group .bars {
            display: flex;
            gap: 6px;
            align-items: flex-end;
        }

        .month-group .bar {
            width: var(--bar-width);
            border-radius: 6px 6px 0 0;
            transition: transform .18s ease, width .18s ease, opacity .18s ease, box-shadow .18s ease;
            position: relative;
        }

        .month-group .bar:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.35);
        }

        .bar-label {
            position: absolute;
            top: -22px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
            white-space: nowrap;
            pointer-events: none;
            padding: 2px 6px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(6px);
        }

        .month-label {
            margin-top: 6px;
            font-size: 0.8rem;
            opacity: 0.8;
        }

        /* paleta ajustada */
        .facebook-bar {
            background: #1877F2;
        }

        .tiktok-bar {
            background: #000000;
        }

        .taboola-bar {
            background: #21A0FF;
            /* azul mais ciano pra diferenciar */
        }

        .native-bar {
            background: #9C4DFF;
            /* roxo mais vibrante */
        }

        /* estado "apagado" quando highlight de legenda estiver ativo */
        .bar.dimmed {
            opacity: 0.25;
        }

        .chart-legend {
            display: flex;
            gap: 1rem;
            margin-bottom: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
            align-items: center;
        }

        .legend-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-right: auto;
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 999px;
            border: 1px solid transparent;
            background: transparent;
            transition: background .18s ease, border-color .18s ease, transform .1s ease;
        }

        .legend-item:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateY(-1px);
        }

        .legend-item.active {
            border-color: rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.06);
        }

        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            display: inline-block;
        }

        .legend-color.facebook {
            background: #1877f2;
        }

        .legend-color.tiktok {
            background: #000;
        }

        .legend-color.taboola {
            background: #21A0FF;
        }

        .legend-color.native {
            background: #9C4DFF;
        }

        .zoom-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: flex-end;
        }

        .zoom-btn,
        .zoom-reset-btn {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 14px;
            min-width: 32px;
            height: 32px;
            border-radius: 999px;
            cursor: pointer;
            transition: background .18s ease, transform .1s ease;
            padding: 0 12px;
        }

        .zoom-btn:hover,
        .zoom-reset-btn:hover {
            background: rgba(255, 255, 255, 0.16);
            transform: translateY(-1px);
        }

        /* tooltip custom do gráfico */
        .chart-tooltip {
            position: absolute;
            z-index: 999;
            pointer-events: none;
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.45);
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .15s ease, transform .15s ease;
            white-space: nowrap;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdowns = document.querySelectorAll('.custom-dropdown-filter');

            dropdowns.forEach(dropdown => {
                const header = dropdown.querySelector('.dropdown-header');
                const display = dropdown.querySelector('.selected-items-display');
                const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
                // pega o texto base ('Canais' 'Canais de Tráfego')
                const baseText = dropdown.querySelector('label')?.textContent || display.textContent.split(
                    '(')[0].trim();

                // atualiza o texto de selecao
                const updateDisplay = () => {
                    const checked = Array.from(checkboxes).filter(c => c.checked);
                    if (checked.length === 0) {
                        // mantem a primeira palavra e adiciona (0)
                        display.textContent = `${baseText.split(' ')[0]} (0)`;
                    } else if (checked.length === 1) {
                        display.textContent = `${baseText.split(' ')[0]} (1)`;
                    } else {
                        display.textContent = `${baseText.split(' ')[0]} (${checked.length})`;
                    }
                };

                // inicia o display
                updateDisplay();

                // tiggle do dropdown
                header.addEventListener('click', () => {
                    // fecha outros dropdowns abertos antes de abrir este
                    document.querySelectorAll('.custom-dropdown-filter.open').forEach(
                        openDropdown => {
                            if (openDropdown !== dropdown) {
                                openDropdown.classList.remove('open');
                            }
                        });
                    dropdown.classList.toggle('open');
                });

                // atualiza o display ao marcar/desmarcar
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateDisplay);
                });

                // fecha o dropdown ao clicar fora
                document.addEventListener('click', (event) => {
                    if (!dropdown.contains(event.target)) {
                        dropdown.classList.remove('open');
                    }
                });
            });
        });
    </script>

    <div class="metrics-table-section glass-card">
        <h3 class="section-title">Métricas por plataforma</h3>
        <div class="table-responsive">
            <table class="metrics-main-table">
                <thead>
                    <tr>
                        <th class="col-main">FONTE</th>
                        <th>CUSTO</th>
                        <th>RECEITA TOTAL</th>
                        <th>LUCRO</th>
                        <th class="col-roi">ROI (%)</th>
                        <th>COMPRAS</th>
                        <th>CLIQUES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sources as $index => $source)
                        @php
                            $aliasKey = Str::slug($source->alias);
                            $accounts = $accountsByAlias[$source->alias] ?? collect();
                        @endphp

                        {{-- Linha principal (alias) --}}
                        <tr class="campaign-row" onclick="toggleDetails('{{ $aliasKey }}')">
                            <td class="col-main-data">
                                <div class="alias-cell">
                                    <span class="arrow-icon" id="arrow-{{ $aliasKey }}">▼</span>
                                    <p class="item-name fw-bold">{{ $source->alias }}</p>
                                </div>
                            </td>

                            <td>@dollar($source->total_cost)</td>
                            <td>@dollar($source->total_cost + $source->total_profit)</td>
                            <td>@dollar($source->total_profit)</td>

                            <td class="col-roi-value {{ $source->roi >= 0 ? 'positive' : 'negative' }}">
                                {{ number_format($source->roi * 100, 4, ',', '.') }}%
                            </td>

                            <td>{{ $source->total_conversions }}</td>
                            <td>{{ $source->total_clicks }}</td>
                        </tr>


                        {{-- Linhas detalhadas (contas) --}}
                        <tr id="details-{{ $aliasKey }}" class="details-row" style="display: none;">
                            <td colspan="7">
                                <div class="accounts-expand">
                                    @foreach ($accounts as $acc)
                                        <div
                                            class="account-row
                                            @if ($acc->roi > 0) row-positive
                                            @elseif ($acc->roi < 0)
                                                row-negative
                                            @else
                                                row-neutral @endif
                                        ">
                                            <div class="acc-col acc-main"><span>{{ $acc->source }}</span></div>
                                            <div class="acc-col">@dollar($acc->total_cost)</div>
                                            <div class="acc-col">@dollar($acc->total_cost + $acc->total_profit)</div>
                                            <div class="acc-col">@dollar($acc->total_profit)</div>

                                            <div class="acc-col {{ $acc->roi >= 0 ? 'positive' : 'negative' }}">
                                                {{ number_format($acc->roi * 100, 2, ',', '.') }}%
                                            </div>

                                            <div class="acc-col">{{ $acc->total_conversions }}</div>
                                            <div class="acc-col">{{ $acc->total_clicks }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- <div class="pagination-area">
            <span class="pagination-text">Páginas:</span>
            <div class="pagination-numbers">
                <a href="#" class="page-number active">1</a>
                <a href="#" class="page-number">2</a>
                <a href="#" class="page-number">3</a>
                <a href="#" class="page-number">4</a>
                <span>...</span>
                <a href="#" class="page-number">17</a>
                <a href="#" class="page-number">18</a>
                <a href="#" class="page-number">19</a>
                <a href="#" class="page-number next">Próxima &gt;</a>
            </div>
        </div> --}}
    </div>


    <tr class="campaign-row" data-id="1" onclick="toggleDetails(1)">
    </tr>

    <tr class="details-row" id="details-row-1">
        <td colspan="9" class="details-content-v2">
        </td>
    </tr>

    <script>
        function toggleDetails(campaignId) {
            const row = document.getElementById(`details-row-${campaignId}`);
            row.classList.toggle('active');
        }
    </script>

    <script>
        function toggleDetails(aliasKey) {
            const detailsRow = document.getElementById(`details-${aliasKey}`);
            if (detailsRow.style.display === "none") {
                detailsRow.style.display = "table-row";
            } else {
                detailsRow.style.display = "none";
            }
        }
    </script>
    <script>
        function toggleDetails(aliasKey) {
            const row = document.getElementById(`details-${aliasKey}`);
            const arrow = document.getElementById(`arrow-${aliasKey}`);

            if (row.style.display === "none") {
                row.style.display = "table-row";
                arrow.classList.add("open");
            } else {
                row.style.display = "none";
                arrow.classList.remove("open");
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const minWidth = 20;
            const maxWidth = 260;
            const defaultWidth = 60;

            let currentWidth = defaultWidth;

            const chartWrapper = document.querySelector(".revenue-chart-placeholder");
            const zoomInBtn = document.getElementById('zoomIn');
            const zoomOutBtn = document.getElementById('zoomOut');
            const zoomResetBtn = document.getElementById('zoomReset');

            function updateZoom() {
                document.documentElement.style.setProperty('--bar-width', currentWidth + "px");
            }

            // ZOOM botões
            zoomInBtn.addEventListener('click', function() {
                if (currentWidth < maxWidth) {
                    currentWidth += 10;
                    updateZoom();
                }
            });

            zoomOutBtn.addEventListener('click', function() {
                if (currentWidth > minWidth) {
                    currentWidth -= 10;
                    updateZoom();
                }
            });

            zoomResetBtn.addEventListener('click', function() {
                currentWidth = defaultWidth;
                updateZoom();
            });

            updateZoom(); // estado inicial

            // TOOLTIP GRAFIGO
            // ---------------- TOOLTIP DINÂMICO (CLICK) ------------------
            const tooltip = document.getElementById('chart-tooltip');
            const chartGrid = document.querySelector('.chart-bar-grid');
            const bars = document.querySelectorAll('.chart-bar-grid .bar');

            function positionTooltipClick(event) {
                const gridRect = chartGrid.getBoundingClientRect();

                // Posição EXATA do clique dentro da grid
                const x = event.clientX - gridRect.left;
                const y = event.clientY - gridRect.top - 12; // 12px acima do clique

                tooltip.style.left = `${x}px`;
                tooltip.style.top = `${y}px`;
            }

            bars.forEach(bar => {

                bar.addEventListener('click', (event) => {
                    event.stopPropagation(); // evita fechar ao clicar no gráfico

                    const alias = bar.dataset.alias;
                    const month = bar.dataset.month;
                    const value = bar.dataset.value;

                    tooltip.innerHTML = `<strong>${month}</strong> · ${alias}<br>${value}`;

                    positionTooltipClick(event);

                    tooltip.style.opacity = '1';
                    tooltip.style.transform = 'translateY(0)';
                });

                bar.addEventListener('mouseleave', () => {
                    tooltip.style.opacity = '0';
                    tooltip.style.transform = 'translateY(6px)';
                });
            });

            // Ocultar tooltip ao clicar fora
            document.addEventListener("click", (e) => {
                if (!e.target.classList.contains('bar')) {
                    tooltip.style.opacity = '0';
                    tooltip.style.transform = 'translateY(6px)';
                }
            });


            // ---------- Highlight por legenda ----------
            const legendItems = document.querySelectorAll('.legend-item');

            legendItems.forEach(item => {
                item.addEventListener('click', () => {
                    const alias = item.dataset.alias;
                    const isActive = item.classList.contains('active');

                    // se clicar em um já ativo -> limpa tudo
                    if (isActive) {
                        legendItems.forEach(li => li.classList.remove('active'));
                        bars.forEach(b => b.classList.remove('dimmed'));
                        return;
                    }

                    // marca ativo
                    legendItems.forEach(li => li.classList.remove('active'));
                    item.classList.add('active');

                    // aplica dim em quem não é da plataforma
                    bars.forEach(b => {
                        if (b.dataset.alias === alias) {
                            b.classList.remove('dimmed');
                        } else {
                            b.classList.add('dimmed');
                        }
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const modeSeparated = document.getElementById('modeSeparated');
            const modeStacked = document.getElementById('modeStacked');
            const expectedContainer = document.getElementById('expectedTrendline');

            const monthGroups = document.querySelectorAll('.month-group');
            const barsGroups = document.querySelectorAll('.month-group .bars');

            // default: separado
            let stackedMode = false;

            function buildPath(points) {
                if (!points.length) return "";
                let d = `M ${points[0].x} ${points[0].y}`;
                for (let i = 1; i < points.length; i++) {
                    d += ` L ${points[i].x} ${points[i].y}`;
                }
                return d;
            }

            // --------- Linha de META FIXA (1.000.000) ----------
            const expected = 1000000; // fixo por enquanto

            function updateExpectedTrendline(show) {
                if (!show) {
                    expectedContainer.innerHTML = "";
                    return;
                }

                let max = 0;

                monthGroups.forEach((mg) => {
                    const total = parseFloat(mg.dataset.total || 0);
                    if (total > max) max = total;
                });

                if (max <= 0) {
                    expectedContainer.innerHTML = "";
                    return;
                }

                const n = monthGroups.length;

                // normaliza o valor esperado em relação ao maior mês do gráfico
                const percent = expected / max;
                const y = 100 - (percent * 80) - 10; // mesma escala da trendline

                const points = [];
                for (let i = 0; i < n; i++) {
                    const x = ((i + 0.5) / n) * 100;
                    points.push({
                        x,
                        y
                    });
                }

                const d = buildPath(points);

                expectedContainer.innerHTML = `
            <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="${d}"></path>
            </svg>
        `;

                // mostra a linha com fade
                const path = expectedContainer.querySelector('path');
                if (path) {
                    requestAnimationFrame(() => {
                        path.style.opacity = "1";
                    });
                }
            }

            // não mostra meta por padrão (apenas no empilhado)
            updateExpectedTrendline(false);

            // ----------- Botão: Separado -----------
            modeSeparated.addEventListener('click', () => {
                stackedMode = false;

                barsGroups.forEach(bg => {
                    bg.classList.remove('stacked');
                });

                updateExpectedTrendline(false); // esconde linha de meta
            });

            // ----------- Botão: Empilhado -----------
            modeStacked.addEventListener('click', () => {
                stackedMode = true;

                barsGroups.forEach(bg => {
                    bg.classList.add('stacked');
                });

                updateExpectedTrendline(true); // mostra linha de meta
            });
        });
    </script>


</x-layout>
