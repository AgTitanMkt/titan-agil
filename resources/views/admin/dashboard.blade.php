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
            margin-bottom: 0.5rem;
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
            z-index: 50;
        }

        /* COLOCA O FILTRO DE PLATAFORMAS ACIMA DO GRAFICO, ELE ESTAVA SOBREPOSTO */
        .multiselect__content,
        .multiselect__content-wrapper,
        .select-dropdown,
        .multiselect__menu {
            position: absolute !important;
            z-index: 2147483647 !important;
            /* maior numero possivel */
        }

        /* para o dropdown nao ficar desalinhado forca overflow visivel nos pais proximos a ele */
        .filters-dataset,
        .filters-grid-dataset,
        .chart-container,
        .chart-wrapper {
            overflow: visible !important;
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

    {{--  ADICIONAR AQUI O COPA PROFIT  --}}
    <div class="chart-dashboard-wrapper-vertical">

        {{--  COPA PROFIT VISUAL AAA/BATTLE PASS --}}
        <div class="copa-profit-section">
            {{-- header da copa --}}
            <div class="copa-header">
                <div class="copa-title-wrapper">
                    <h1 class="copa-main-title">COPA PROFIT <span class="year">{{ $copaYear }}</span></h1>
                    <p class="copa-subtitle">
                        {{ implode(' • ', $copaMonths) }}
                    </p>
                </div>
                <div class="total-prize-pool">
                    <span class="prize-label">PREMIAÇÃO TOTAL EM JOGO</span>
                    <span class="prize-value">@real($copaPrize)</span>
                </div>
            </div>


            {{-- PODIO 1: plataformas --}}
            <div class="podium-grid-container">

                @php
                    // Reorganiza visualmente o pódio
                    $orderedPlatforms = [
                        $podium[1], // 2º lugar
                        $podium[0], // 1º lugar
                        $podium[2], // 3º lugar
                    ];
                @endphp

                <div class="podium-structure">

                    @foreach ($orderedPlatforms as $item)
                        @php
                            $placeClass = match ($item['rank']) {
                                1 => 'place-1 champion',
                                2 => 'place-2',
                                3 => 'place-3',
                                default => '',
                            };
                        @endphp

                        <div class="podium-place {{ $placeClass }}">

                            @if ($item['rank'] === 1)
                                <div class="champion-crown-floater"><i class="fas fa-crown"></i></div>
                            @endif

                            <div class="avatar-container @if ($item['rank'] === 1) champion-avatar @endif">
                                <i class="fas fa-dragon"></i>
                            </div>

                            <div class="place-info">
                                <span class="place-rank">
                                    @if ($item['rank'] === 1)
                                        #1 CAMPEÃO
                                    @else
                                        #{{ $item['rank'] }}
                                    @endif
                                </span>

                                <span class="place-name">{{ $item['name'] }}</span>
                                <div class="flex price-podio">
                                    <span class="place-profit @if ($item['rank'] === 1) gold-text @endif">
                                        @dollar($item['profit'])
                                    </span>
                                    <span class="roi-badge positive">{{ $item['roi'] * 100 }}% <i
                                            class="fas fa-caret-up"></i></span>
                                </div>
                            </div>

                            <div class="pedestal-block @if ($item['rank'] === 1) champion-pedestal @endif">
                            </div>
                        </div>
                    @endforeach
                </div>


                {{-- PODIO 2: COPYS (R$ 20K) --}}
                <div class="podium-category copy-category">
                    <div class="category-header-podium">
                        <h3 class="category-title"><i class="fas fa-pen-nib"></i> MELHOR COPY</h3>
                        <div class="category-prize silver">@real($copiePrize)</div>
                    </div>

                    <div class="podium-structure compact-podium">
                        <div class="podium-flat-list">

                            @foreach ($copiesPodium as $copy)
                                <div class="flat-item rank-{{ $copy['rank'] }}">

                                    {{-- Ícone apenas para o primeiro lugar --}}
                                    @if ($copy['rank'] === 1)
                                        <i class="fas fa-crown gold-icon"></i>
                                    @else
                                        <div class="flat-rank">#{{ $copy['rank'] }}</div>
                                    @endif

                                    <div class="flat-avatar gen-avatar">
                                        {{ $copy['avatar'] }}
                                    </div>

                                    <div class="flat-info">
                                        <strong>{{ $copy['name'] }}</strong>
                                        <span>@dollar($copy['profit'])</span>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>


                {{-- PODIO 3: EDITORES --}}
                <div class="podium-category editor-category">
                    <div class="category-header-podium">
                        <h3 class="category-title"><i class="fas fa-video"></i> MELHOR EDITOR</h3>
                        <div class="category-prize bronze">@real($editorPrize)</div>
                    </div>

                    <div class="podium-structure compact-podium">
                        <div class="podium-flat-list">

                            @foreach ($editorsPodium as $editor)
                                <div class="flat-item rank-{{ $editor['rank'] }}">

                                    @if ($editor['rank'] === 1)
                                        <i class="fas fa-crown gold-icon"></i>
                                    @else
                                        <div class="flat-rank">#{{ $editor['rank'] }}</div>
                                    @endif

                                    <div class="flat-avatar gen-avatar editor-av">
                                        {{ $editor['avatar'] }}
                                    </div>

                                    <div class="flat-info">
                                        <strong>{{ $editor['name'] }}</strong>
                                        <span>@dollar($editor['profit'])</span>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div> {{-- fim copa profit --}}


    <style>
        :root {


            /* CORES VISUAL COPA PROFIT */
            --gold-1: #FFD700;
            --gold-2: #FFA500;
            --gold-glow: rgba(255, 215, 0, 0.5);
            --silver-1: #E0E0E0;
            --silver-2: #B0B0B0;
            --bronze-1: #CD7F32;
            --bronze-2: #A0522D;
            --neon-purple: #bc13fe;
            --neon-blue: #0f53ff;
            --bg-copa: linear-gradient(135deg, rgba(10, 10, 15, 0.8) 0%, rgba(20, 20, 30, 0.6) 100%);
        }

        /* COPA PROFIT CONTEINER/SECTITON */
        .copa-profit-section {
            background: var(--bg-copa);
            border-radius: 30px;
            padding: 40px;
            position: relative;
            border: 1px solid rgba(15, 83, 255, 0.3);
            box-shadow: 0 0 50px rgba(15, 83, 255, 0.15);
            overflow: hidden;
            margin-bottom: 30px;
        }

        /* fundo padarao - da para adicionar um backgorund */
        .copa-profit-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(15, 83, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .copa-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 20px;
        }

        .copa-main-title {
            font-size: 3.5rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -2px;
            line-height: 1;
            text-shadow: 0 0 20px rgba(15, 83, 255, 0.8);
        }

        .copa-main-title .year {
            color: transparent;
            -webkit-text-stroke: 2px var(--color-primary);
            font-style: italic;
            margin-left: 10px;
        }

        .copa-subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-top: 5px;
            font-weight: 500;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .total-prize-pool {
            text-align: right;
        }

        .prize-label {
            display: block;
            font-size: 0.8rem;
            color: var(--color-success);
            font-weight: 700;
            letter-spacing: 2px;
        }

        .prize-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(90deg, #fff, var(--color-success));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* grid dos podios */
        .price-podio {
            display: flex;
            column-gap: 0.5rem;
        }

        .price-podio .roi-badge {
            font-size: 7pt;
            color: white !important;
            align-items: center;
        }

        .podium-grid-container {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 30px;
            position: relative;
            z-index: 1;
        }

        .category-header-podium {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 20px;
            border-radius: 12px;
        }

        .category-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .category-title i {
            color: var(--color-primary);
        }

        .category-prize {
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #000;
        }

        .category-prize.gold {
            background: var(--gold-1);
            box-shadow: 0 0 10px var(--gold-glow);
        }

        .category-prize.silver {
            background: var(--silver-1);
        }

        .category-prize.bronze {
            background: var(--bronze-1);
        }

        /* estrutura do podium dos squads */
        .podium-structure {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            height: 300px;
            gap: 10px;
        }

        .podium-place {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 30%;
            position: relative;
        }

        /* avatares para representar o squad/nome de todo mundo */
        .avatar-container {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #333;
            border: 3px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            z-index: 2;
        }

        .champion-avatar {
            width: 80px;
            height: 80px;
            border-color: var(--gold-1);
            background: linear-gradient(135deg, #000, #333);
            font-size: 2.5rem;
            box-shadow: 0 0 25px var(--gold-glow);
        }

        .place-info {
            text-align: center;
            margin-bottom: 10px;
            z-index: 2;
        }

        .place-rank {
            font-size: 0.8rem;
            font-weight: 900;
            color: var(--text-muted);
            display: block;
        }

        .place-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            display: block;
            white-space: nowrap;
        }

        .place-profit {
            font-size: 0.8rem;
            color: var(--color-success);
            font-weight: 600;
        }

        .gold-text {
            color: var(--gold-1);
        }

        /* pedestral */
        .pedestal-block {
            width: 100%;
            background: linear-gradient(180deg, rgba(15, 83, 255, 0.6) 0%, rgba(15, 83, 255, 0.1) 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px 8px 0 0;
            position: relative;
        }

        .place-2 .pedestal-block {
            height: 100px;
        }

        .place-3 .pedestal-block {
            height: 70px;
        }

        .champion-pedestal {
            height: 140px;
            background: linear-gradient(180deg, var(--color-primary) 0%, rgba(15, 83, 255, 0.3) 100%);
            box-shadow: 0 0 30px rgba(15, 83, 255, 0.4);
        }

        .champion-crown-floater {
            position: absolute;
            top: -40px;
            color: var(--gold-1);
            font-size: 2.5rem;
            animation: floatCrown 3s ease-in-out infinite;
            filter: drop-shadow(0 0 10px var(--gold-1));
        }

        /* copy e editores flat list*/
        .podium-flat-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        .flat-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: 0.2s;
            position: relative;
            overflow: hidden;
        }

        .flat-item.rank-1 {
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.1), transparent);
            border-color: rgba(255, 215, 0, 0.3);
        }

        .flat-item:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.08);
        }

        .gold-icon {
            color: var(--gold-1);
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .flat-rank {
            width: 30px;
            font-weight: 800;
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .gen-avatar {
            width: 35px;
            height: 35px;
            background: #333;
            border-radius: 50%;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .flat-info {
            display: flex;
            flex-direction: column;
        }

        .flat-info strong {
            color: #fff;
            font-size: 0.95rem;
        }

        .flat-info span {
            color: var(--color-success);
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* animacao padrao */
        @keyframes floatCrown {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes pulseGold {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 215, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
            }
        }

        /* responsividade */
        @media (max-width: 1200px) {
            .chart-dashboard-wrapper {
                grid-template-columns: 1fr;
            }

            .podium-grid-container {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- SCRIPTS JS  --}}
    <script>
        // VAI USAR O SCRIPT DO GRAFICO + TOP PLATAFORMAS 
    </script>
    {{--  FIM DA COPA PROFIT  --}}


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


    {{-- GRAFICO + TOP PLATAFORMAS AQUI --}}
    <div class="chart-dashboard-wrapper-vertical">

        {{-- GRAFICO PRINCIPAL PERFOMANCE --}}
        <div class="chart-section glass-card-premium main-chart-area full-width-chart">
            <div class="chart-header">
                <h3 class="section-title-premium">Performance Financeira & Metas</h3>
                <p class="chart-subtitle-premium">Acompanhamento mensal de faturamento & Meta ($1M/Mês)</p>
            </div>

            <div class="chart-controls-wrapper">
                <div class="control-group mode-group">
                    <button id="modeSeparated" class="btn-mode active" onclick="setChartMode('separated')"><i
                            class="fas fa-chart-bar"></i> Separado</button>
                    <button id="modeStacked" class="btn-mode" onclick="setChartMode('stacked')"><i
                            class="fas fa-layer-group"></i> Empilhado</button>
                    <button id="modeTargets" class="btn-mode btn-mode-target" onclick="toggleTargets()"><i
                            class="fas fa-bullseye"></i> Metas</button>
                </div>
                <div class="control-group zoom-group">
                    <button id="zoomOut" class="btn-icon"><i class="fas fa-minus"></i></button> <span
                        class="zoom-label">ZOOM</span> <button id="zoomIn" class="btn-icon"><i
                            class="fas fa-plus"></i></button> <button id="zoomReset"
                        class="btn-reset">Resetar</button>
                </div>
            </div>

            <div class="chart-legend-premium">
                <span class="legend-title">Plataformas:</span>
                <button type="button" class="legend-pill facebook active" data-alias="Facebook"
                    onclick="togglePlatform(this)"><span class="dot"></span> Facebook</button>
                <button type="button" class="legend-pill tiktok active" data-alias="TikTok"
                    onclick="togglePlatform(this)"><span class="dot"></span> TikTok</button>
                <button type="button" class="legend-pill google active" data-alias="Google"
                    onclick="togglePlatform(this)"><span class="dot"></span> Google</button>
                <button type="button" class="legend-pill native active" data-alias="Native"
                    onclick="togglePlatform(this)"><span class="dot"></span> Native</button>
            </div>

            <div class="revenue-chart-container custom-scrollbar-x" id="chartContainer">
                <div id="chart-tooltip" class="chart-tooltip-premium"></div>
                <div class="chart-grid-wrapper" id="chartGridWrapper">
                    <div class="target-line-wrapper" id="targetLine">
                        <div class="target-line"></div>
                        <div class="target-label"><i class="fas fa-flag-checkered"></i> META: $1M</div>
                    </div>
                    <div class="chart-bars-area">
                        @foreach ($chartData as $month => $platforms)
                            @php
                                // Total do mês baseado no PROFIT
                                $monthTotal = array_sum(array_column($platforms, 'profit'));

                                // ROI do mês (média do mês)
                                $monthROI = 0;
                                $totalCostForROI = array_sum(array_column($platforms, 'cost'));

                                if ($totalCostForROI > 0) {
                                    $monthROI = round(($monthTotal / $totalCostForROI) * 100, 2);
                                }

                                $roiClass = $monthROI >= 0 ? 'positive' : 'negative';

                                // Escala visual (18rem = 1.5M)
                                $maxValueVisual = 1500000;
                                $scaleFactor = 18 / $maxValueVisual;

                                // meta do mês
                                $hitTarget = $monthTotal >= 1000000;
                            @endphp

                            <div class="month-column-group" data-month="{{ $month }}"
                                data-total="{{ $monthTotal }}">

                                {{-- Selo meta batida --}}
                                @if ($hitTarget)
                                    <div class="month-achievement-crown target-element">
                                        <i class="fas fa-crown"></i><span>META BATIDA</span>
                                    </div>
                                @endif

                                {{-- ROI --}}
                                <div class="roi-badge {{ $roiClass }}">
                                    {{ $monthROI }}%
                                    <i class="fas fa-caret-{{ $monthROI >= 0 ? 'up' : 'down' }}"></i>
                                </div>

                                {{-- Total do mês --}}
                                <div class="total-value-label">@dollar($monthTotal)</div>

                                <div class="bars-stack">
                                    @foreach ($aliases as $alias)
                                        @php
                                            $profit = $platforms[$alias]['profit'] ?? 0;
                                            $height = $profit * $scaleFactor;
                                            $hitIndividualTarget = $profit >= 1000000;
                                        @endphp

                                        @if ($profit > 0)
                                            <div class="bar-segment {{ strtolower($alias) }}-segment"
                                                style="height: {{ $height }}rem;"
                                                data-value="@dollar($profit)" data-platform="{{ ucfirst($alias) }}"
                                                onmouseenter="showTooltip(this, event)" onmouseleave="hideTooltip()">

                                                {{-- Selo de meta individual --}}
                                                @if ($hitIndividualTarget)
                                                    <div class="mini-crown target-element-item"><i
                                                            class="fas fa-crown"></i></div>
                                                @endif

                                                {{-- Valor dentro da barra se tiver altura suficiente --}}
                                                @if ($height > 1.5)
                                                    <span class="inner-value">@dollar($profit)</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- Nome do mês --}}
                                <div class="month-label">{{ $month }}</div>
                                <div class="grid-line-vertical"></div>

                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        {{-- TOP PLATAFORMAS --}}
        <div class="ranking-section-fullwidth glass-card-premium">
            <div class="ranking-header-full">
                <div class="header-left">
                    <h4 class="ranking-title"><i class="fas fa-trophy"></i> Top Plataformas do Mês</h4>
                    <p class="ranking-subtitle">Liderança em faturamento acumulado</p>
                </div>
                {{-- status geral movido para o header para economizar espaco vertical --}}
                <div class="status-card-inline">
                    <div class="status-info-inline">
                        <i class="fas fa-bullseye"></i> <span>Meta Global: {{ $target }}%</span>
                    </div>
                    <div class="progress-bar-wrapper inline-bar">
                        <div class="progress-bar-fill" style="width: {{ $target }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- lista em grid horizontal --}}
            <div class="ranking-list-horizontal">

                @foreach ($aliasRanking as $index => $item)
                    @php
                        $rank = $index + 1;
                        $profit = $item['profit'];
                        $alias = ucfirst($item['alias']);
                    @endphp

                    <div class="ranking-item {{ $rank === 1 ? 'rank-1' : '' }}">
                        <div class="rank-pos">{{ $rank }}</div>

                        <div class="rank-info">
                            <span class="rank-name">{{ $alias }}</span>
                            <span class="rank-val">@dollar($profit)</span>
                        </div>

                        @if ($rank === 1)
                            <div class="rank-crown"><i class="fas fa-crown"></i></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div> {{-- FIM DO GRAFICO PERFOMANCE + FIM DO TOP PLATAFORMAS --}}



    <style>
        /* CORES GRAFICO + TOP PLATAFORMAS  */
        :root {
            --color-primary: #0f53ff;
            --color-primary-dark: #012c99;
            --color-success: #4ADE80;
            --color-danger: #F87171;
            --color-gold: #FFD700;
            --color-gold-glow: rgba(255, 215, 0, 0.6);
            --bg-card: rgba(255, 255, 255, 0.03);
            --border-card: rgba(255, 255, 255, 0.1);
            --text-main: #F0F0F0;
            --text-muted: #9CA3AF;
            --color-fb: #1877F2;
            --color-tiktok: #000000;
            --color-tiktok-bg: #252525;
            --color-google: #e0043b;
            --color-native: #9C4DFF;
        }

        .chart-dashboard-wrapper-vertical {
            display: flex;
            flex-direction: column;
            /* empilha um abaixo do outro */
            width: 100%;
            /* max-width: 1600px; removido para ficar 100% da largura */
            margin: 0 auto;
            gap: 30px;
        }

        /* BASE PADRAO GLASS */
        .glass-card-premium {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(12px);
            position: relative;
            width: 100%;
            /* garante largura total */
            box-sizing: border-box;
        }

        /* GRAFICO PERFOMANCE */
        .chart-header {
            margin-bottom: 25px;
        }

        .section-title-premium {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 0 0 5px 0;
            background: linear-gradient(90deg, #fff, #a0a0a0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .chart-subtitle-premium {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        .chart-controls-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 15px;
        }

        .control-group {
            display: flex;
            align-items: center;
            gap: 5px;
            background: rgba(0, 0, 0, 0.2);
            padding: 4px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn-mode {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }


        .btn-mode.active,
        .btn-mode:hover {
            background: var(--color-primary);
            color: white;
        }

        .btn-mode-target.active {
            background: var(--color-gold);
            color: #000;
            box-shadow: 0 0 15px var(--color-gold-glow);
            animation: pulseGold 2s infinite;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .zoom-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            margin: 0 5px;
        }

        .btn-reset {
            background: transparent;
            border: 1px solid var(--color-primary);
            color: var(--color-primary);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            cursor: pointer;
        }

        .btn-reset:hover {
            background: var(--color-primary);
            color: white;
        }

        .chart-legend-premium {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .legend-pill {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-muted);
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: 0.3s;
        }

        .legend-pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .legend-pill.facebook .dot {
            background: var(--color-fb);
        }

        .legend-pill.tiktok .dot {
            background: var(--color-tiktok);
        }

        .legend-pill.native .dot {
            background: var(--color-native);
        }

        .legend-pill.google .dot {
            background: var(--color-google);
        }


        .legend-pill.active {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border-color: rgba(255, 255, 255, 0.3);
        }

        .legend-pill:not(.active) {
            opacity: 0.5;
            filter: grayscale(1);
        }

        .revenue-chart-container {
            position: relative;
            height: 450px;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 10px;
        }

        .chart-grid-wrapper {
            display: flex;
            height: 100%;
            align-items: flex-end;
            padding: 60px 20px 20px 20px;
            min-width: 100%;
            width: max-content;
        }

        .chart-bars-area {
            display: flex;
            height: 100%;
            align-items: flex-end;
            gap: 2.5rem;
        }

        .month-column-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            height: 100%;
            position: relative;
            min-width: 70px;
        }

        .grid-line-vertical {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 1px;
            height: 100%;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.03), transparent);
            z-index: 0;
            pointer-events: none;
        }

        .target-element,
        .target-element-item {
            display: none;
        }

        .show-targets .target-element {
            display: flex;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .show-targets .month-column-group.separated .target-element-item {
            display: block;
        }

        .target-line-wrapper {
            position: absolute;
            left: 0;
            right: 0;
            bottom: calc((1000000 / 1500000) * 100%);
            height: 2px;
            z-index: 5;
            pointer-events: none;
            display: none;
        }

        .target-line {
            width: 100%;
            height: 100%;
            border-top: 2px dashed var(--color-gold);
            box-shadow: 0 0 10px var(--color-gold);
        }

        .target-label {
            position: absolute;
            right: 0;
            top: -25px;
            background: var(--color-gold);
            color: #000;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            box-shadow: 0 0 10px var(--color-gold-glow);
        }

        .month-achievement-crown {
            position: absolute;
            top: -50px;
            display: none;
            flex-direction: column;
            align-items: center;
            color: var(--color-gold);
            z-index: 20;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
        }

        .month-achievement-crown i {
            font-size: 2rem;
            filter: drop-shadow(0 0 15px var(--color-gold));
            animation: floatCrown 3s ease-in-out infinite;
        }

        .month-achievement-crown span {
            font-size: 0.6rem;
            font-weight: 900;
            background: #000;
            padding: 2px 4px;
            border-radius: 4px;
            margin-top: -5px;
        }

        .mini-crown {
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%);
            color: var(--color-gold);
            font-size: 0.8rem;
            display: none;
            z-index: 15;
        }

        .roi-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 3px;
            z-index: 5;
        }

        .roi-badge.positive {
            background: rgba(74, 222, 128, 0.15);
            color: var(--color-success);
            width: min-content;
        }

        .roi-badge.negative {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-danger);
        }

        .total-value-label {
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 4px;
            text-shadow: 0 2px 4px #000;
            z-index: 5;
        }

        .month-label {
            margin-top: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .bars-stack {
            display: flex;
            flex-direction: column-reverse;
            align-items: center;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .month-column-group.separated .bars-stack {
            flex-direction: row;
            align-items: flex-end;
            gap: 2px;
        }

        .bar-segment {
            width: 100%;
            min-height: 0;
            transition: height 0.4s, opacity 0.2s;
            position: relative;
            cursor: pointer;
        }

        .bars-stack .bar-segment:last-child {
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }

        .month-column-group.separated .bar-segment {
            width: 16px;
            border-radius: 4px 4px 0 0;
        }

        .bar-segment:hover {
            filter: brightness(1.3);
            z-index: 10;
        }

        .facebook-segment {
            background: var(--color-fb);
        }

        .tiktok-segment {
            background: var(--color-tiktok-bg);
            border: 1px solid #555;
        }

        .google-segment {
            background: var(--color-google);
        }

        .native-segment {
            background: var(--color-native);
        }

        .inner-value {
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.9);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            font-weight: 600;
            opacity: 0;
            transition: 0.2s;
        }

        .bar-segment:hover .inner-value {
            opacity: 1;
        }

        .chart-tooltip-premium {
            position: absolute;
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid var(--color-primary);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            pointer-events: none;
            opacity: 0;
            transform: translate(-50%, -10px);
            transition: 0.2s;
            z-index: 100;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .custom-scrollbar-x::-webkit-scrollbar {
            height: 8px;
        }

        .custom-scrollbar-x::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 4px;
        }

        .custom-scrollbar-x::-webkit-scrollbar-thumb {
            background: var(--color-primary);
            border-radius: 4px;
        }

        @keyframes floatCrown {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        @keyframes pulseGold {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 215, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
            }
        }

        @keyframes popIn {
            from {
                transform: scale(0);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* TOP PLATAFROMAS AQUI */
        .ranking-section-fullwidth {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-bottom: 30px;
            /* espaco para o conteiner abaixo - metricas */
        }

        .ranking-header-full {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-card);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-left {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .ranking-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--color-gold);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .ranking-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* status bar inline */
        .status-card-inline {
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .status-info-inline {
            color: var(--text-muted);
            font-size: 0.85rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-info-inline i {
            color: var(--color-primary);
        }

        .progress-bar-wrapper.inline-bar {
            width: 150px;
            height: 8px;
            background: #333;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: var(--color-primary);
            border-radius: 4px;
        }

        /* lista horizontal grid */
        .ranking-list-horizontal {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            /* responsivo */
            gap: 15px;
        }

        .ranking-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid transparent;
            transition: all 0.3s;
            position: relative;
        }

        .ranking-item:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .ranking-item.rank-1 {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), transparent);
            border-color: var(--color-gold);
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.15);
        }

        .rank-pos {
            font-size: 1.4rem;
            font-weight: 800;
            margin-right: 15px;
            color: var(--text-muted);
        }

        .rank-1 .rank-pos {
            color: var(--color-gold);
            font-size: 1.8rem;
        }

        .rank-info {
            flex: 1;
        }

        .rank-name {
            display: block;
            font-weight: 700;
            color: #fff;
            font-size: 1rem;
        }

        .rank-val {
            font-size: 0.9rem;
            color: var(--color-success);
            font-weight: 600;
            margin-top: 4px;
            display: block;
        }

        .rank-crown {
            font-size: 1.5rem;
            color: var(--color-gold);
            position: absolute;
            top: 10px;
            right: 15px;
            opacity: 0;
        }

        .rank-1 .rank-crown {
            opacity: 1;
            animation: floatCrown 3s infinite ease-in-out;
        }

        /* responsividade */
        @media (max-width: 768px) {
            .ranking-header-full {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .status-card-inline {
                width: 100%;
                justify-content: space-between;
            }

            .progress-bar-wrapper.inline-bar {
                width: 100px;
            }
        }
        .sub-view-font{
            margin-left: 1rem; 
        }
    </style>

    {{-- SCRIPT DO GRAFICO PERFOMANCE FINANCEIRO + TOP PLATAFORMAS DO MES --}}
    <script>
        window.rankingData = {
            @foreach ($aliasRanking as $item)
                "{{ ucfirst($item['alias']) }}": {{ $item['profit'] }} ",
            @endforeach
        };
    </script>

    <script>
        // DADOS DE EXEMPLO (meta 1 milhao)
        let rankingData = window.rankingData;


        let isTargetMode = false;

        function setChartMode(mode) {
            const groups = document.querySelectorAll('.month-column-group');
            const btnSep = document.getElementById('modeSeparated');
            const btnStk = document.getElementById('modeStacked');
            if (mode === 'separated') {
                groups.forEach(g => g.classList.add('separated'));
                btnSep.classList.add('active');
                btnStk.classList.remove('active');
            } else {
                groups.forEach(g => g.classList.remove('separated'));
                btnStk.classList.add('active');
                btnSep.classList.remove('active');
            }
        }

        function toggleTargets() {
            isTargetMode = !isTargetMode;
            const btn = document.getElementById('modeTargets');
            const container = document.getElementById('chartContainer');
            const line = document.getElementById('targetLine');
            if (isTargetMode) {
                btn.classList.add('active');
                container.classList.add('show-targets');
                line.style.display = 'block';
            } else {
                btn.classList.remove('active');
                container.classList.remove('show-targets');
                line.style.display = 'none';
            }
        }

        function togglePlatform(btn) {
            btn.classList.toggle('active');
            const alias = btn.getAttribute('data-alias').toLowerCase();
            const segments = document.querySelectorAll(`.${alias}-segment`);
            segments.forEach(seg => {
                if (btn.classList.contains('active')) {
                    seg.style.display = 'block';
                    setTimeout(() => seg.style.opacity = '1', 10);
                } else {
                    seg.style.opacity = '0';
                    setTimeout(() => seg.style.display = 'none', 300);
                }
            });
        }

        const tooltip = document.getElementById('chart-tooltip');

        function showTooltip(el, event) {
            const value = el.getAttribute('data-value');
            const platform = el.getAttribute('data-platform');
            tooltip.innerHTML = `<strong style="color:var(--color-primary)">${platform}</strong>: ${value}`;
            tooltip.style.opacity = '1';
            const rect = el.getBoundingClientRect();
            const containerRect = document.querySelector('.revenue-chart-container').getBoundingClientRect();
            const leftPos = rect.left - containerRect.left + (rect.width / 2);
            const topPos = rect.top - containerRect.top;
            tooltip.style.left = `${leftPos}px`;
            tooltip.style.top = `${topPos}px`;
            tooltip.style.transform = 'translate(-50%, -120%)';
        }

        function hideTooltip() {
            tooltip.style.opacity = '0';
        }

        let currentZoom = 1;
        const gridWrapper = document.getElementById('chartGridWrapper');
        document.getElementById('zoomIn').addEventListener('click', (e) => {
            e.preventDefault();
            currentZoom += 0.1;
            updateZoom();
        });
        document.getElementById('zoomOut').addEventListener('click', (e) => {
            e.preventDefault();
            if (currentZoom > 0.5) currentZoom -= 0.1;
            updateZoom();
        });
        document.getElementById('zoomReset').addEventListener('click', (e) => {
            e.preventDefault();
            currentZoom = 1;
            updateZoom();
        });

        function updateZoom() {
            gridWrapper.style.transform = `scale(${currentZoom})`;
            gridWrapper.style.transformOrigin = 'bottom left';
        }
    </script>

    {{-- FIM DO GRAFICO PERFOMANCE + TOP PLATAFORMAS --}}

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
                    @php
                        // plataformas isoladas
                        $soloPlatforms = ['facebook', 'google', 'tiktok'];

                        // tudo que não for facebook/google/tiktok vai para native
                        $nativeGroup = $sources->filter(fn($s) => !in_array(strtolower($s->alias), $soloPlatforms));

                        // somente plataformas principais (fb/google/tiktok)
                        $mainPlatforms = $sources->filter(fn($s) => in_array(strtolower($s->alias), $soloPlatforms));
                    @endphp

                    {{-- -------------------------- --}}
                    {{-- 1) PLATAFORMAS PRINCIPAIS --}}
                    {{-- -------------------------- --}}
                    @foreach ($mainPlatforms as $source)
                        @php
                            $aliasKey = Str::slug($source->alias);
                            $accounts = $accountsByAlias[$source->alias] ?? collect();
                        @endphp

                        {{-- Linha principal --}}
                        <tr class="campaign-row" onclick="toggleDetails('{{ $aliasKey }}')">
                            <td class="col-main-data">
                                <div class="alias-cell">
                                    <span class="arrow-icon" id="arrow-{{ $aliasKey }}">▼</span>
                                    <p class="item-name fw-bold">{{ ucfirst($source->alias) }}</p>
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

                        {{-- Contas da plataforma --}}
                        <tr id="details-{{ $aliasKey }}" class="details-row" style="display: none;">
                            <td colspan="7">
                                <div class="accounts-expand">
                                    @foreach ($accounts as $acc)
                                        <div
                                            class="account-row 
                        @if ($acc->roi > 0) row-positive
                        @elseif ($acc->roi < 0) row-negative
                        @else row-neutral @endif">
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


                    {{-- -------------------------- --}}
                    {{-- 2) AGRUPAMENTO NATIVE --}}
                    {{-- -------------------------- --}}
                    @php
                        $nativeKey = 'native';
                    @endphp

                    <tr class="campaign-row" onclick="toggleDetails('{{ $nativeKey }}')">
                        <td class="col-main-data">
                            <div class="alias-cell">
                                <span class="arrow-icon" id="arrow-{{ $nativeKey }}">▼</span>
                                <p class="item-name fw-bold">Native</p>
                            </div>
                        </td>

                        {{-- soma geral das nativas --}}
                        <td>@dollar($nativeGroup->sum('total_cost'))</td>
                        <td>@dollar($nativeGroup->sum('total_cost') + $nativeGroup->sum('total_profit'))</td>
                        <td>@dollar($nativeGroup->sum('total_profit'))</td>

                        @php
                            $totalNativeCost = $nativeGroup->sum('total_cost');
                            $totalNativeProfit = $nativeGroup->sum('total_profit');
                            $nativeROI = $totalNativeCost > 0 ? $totalNativeProfit / $totalNativeCost : 0;
                        @endphp

                        <td class="col-roi-value {{ $nativeROI >= 0 ? 'positive' : 'negative' }}">
                            {{ number_format($nativeROI * 100, 4, ',', '.') }}%
                        </td>

                        <td>{{ $nativeGroup->sum('total_conversions') }}</td>
                        <td>{{ $nativeGroup->sum('total_clicks') }}</td>
                    </tr>

                    {{-- Subfonte dentro de Native --}}
                    <tr id="details-{{ $nativeKey }}" class="details-row" style="display: none;">
                        <td colspan="7">
                            <div class="accounts-expand">

                                @foreach ($nativeGroup as $sub)
                                    @php
                                        $subKey = Str::slug('native-' . $sub->alias);
                                        $accounts = $accountsByAlias[$sub->alias] ?? collect();
                                    @endphp

                                    {{-- subtítulo da subfonte --}}
                                    <div class="account-row row-neutral"
                                        onclick="toggleDetails('{{ $subKey }}')" style="cursor:pointer;">
                                        <div class="acc-col acc-main"><span>{{ ucfirst($sub->alias) }}</span></div>
                                        <div class="acc-col">@dollar($sub->total_cost)</div>
                                        <div class="acc-col">@dollar($sub->total_cost + $sub->total_profit)</div>
                                        <div class="acc-col">@dollar($sub->total_profit)</div>

                                        <div class="acc-col {{ $sub->roi >= 0 ? 'positive' : 'negative' }}">
                                            {{ number_format($sub->roi * 100, 2, ',', '.') }}%
                                        </div>

                                        <div class="acc-col">{{ $sub->total_conversions }}</div>
                                        <div class="acc-col">{{ $sub->total_clicks }}</div>
                                    </div>

                                    {{-- contas da subfonte --}}
                                    <div class="sub-view-font" id="details-{{ $subKey }}" style="display:none;">
                                        @foreach ($accounts as $acc)
                                            <div
                                                class="account-row 
                            @if ($acc->roi > 0) row-positive
                            @elseif ($acc->roi < 0) row-negative
                            @else row-neutral @endif">

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
                                @endforeach

                            </div>
                        </td>
                    </tr>

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
