<x-layout>

    <style>
        /* -------------------------
   Seta animada
-------------------------- */
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
            grid-template-columns: repeat(4, 1fr);
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
    </style>

    <h2 class="dashboard-page-title">Faturamento</h2>
    <p class="dashboard-page-subtitle">Monitoramento completo da saúde financeira da Titan de
        <b>{{ $startDate->format('d/m/Y') }} à {{ $endDate->format('d/m/Y') }}</b>
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
            <h3 class="metric-value">{{ $totals['roi'] * 100 }}</h3>
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
            <x-multiselect name="sources" label="Contas" :options="$allSources" :selected="request('sources', [])"
                placeholder="Selecione uma ou mais contas">
            </x-multiselect>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Aplicar</button>
                <a href="{{ route('admin.dashboard') }}" class="btn-clear">Limpar</a>
            </div>

        </form>
    </div>


    <div class="chart-section glass-card">
        <h3 class="section-title">Gráfico de Faturamento</h3>
        <p class="chart-subtitle">Visualização completa do faturamento mensal ao longo do ano</p>

        @php
            $maxProfit = max($profits);
        @endphp

        <div class="revenue-chart-placeholder">
            <div class="chart-bar-grid">
                @foreach ($profits as $month => $value)
                    @php
                        $height = $maxProfit > 0 ? ($value / $maxProfit) * 100 : 0;
                        $isCurrentMonth = $month === now()->translatedFormat('M');
                    @endphp

                    <div class="chart-bar {{ $isCurrentMonth ? 'chart-bar-highlight' : '' }}"
                        style="--bar-height: {{ number_format($height, 2) }}%;"
                        title="{{ $month }} — @dollar($value)">
                        <span class="chart-tooltip">@dollar($value)</span>
                    </div>
                @endforeach
            </div>

            <div class="chart-labels">
                @foreach (array_keys($profits) as $month)
                    <span>{{ $month }}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- CSS inline ou no seu arquivo de estilos --}}
    <style>
        .chart-bar-grid {
            display: flex;
            align-items: flex-end;
            gap: 0.75rem;
            height: 200px;
            position: relative;
        }

        .chart-bar {
            width: 35px;
            border-radius: 6px 6px 0 0;
            position: relative;
            height: 0;
            animation: grow-bar 1s ease forwards;
            animation-delay: calc(var(--bar-index, 0) * 0.05s);
            transition: transform 0.2s ease;
        }

        .chart-bar:hover {
            transform: scale(1.05);
        }

        /* animação de crescimento */
        @keyframes grow-bar {
            from {
                height: 0;
            }

            to {
                height: var(--bar-height);
            }
        }

        /* tooltip */
        .chart-bar .chart-tooltip {
            position: absolute;
            bottom: calc(var(--bar-height) + 5px);
            left: 50%;
            transform: translateX(-50%);
            background-color: black;
            opacity: 0;
            color: #fff;
            padding: 3px 6px;
            font-size: 0.75rem;
            border-radius: 4px;
            pointer-events: none;
            transition: opacity 0.2s ease;
            white-space: nowrap;
        }

        .chart-bar:hover .chart-tooltip {
            opacity: 1;
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

</x-layout>
