<x-layout>

    @php
        $title = 'Corrida do Profit 2026';
    @endphp


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        /* cores da corrida do profit - paleta titan*/
        :root {
            --titan-900: #030b1a;
            --titan-800: #071428;
            --titan-700: #0a1e3d;
            --titan-600: #0d2b58;
            --titan-500: #0f3872;
            --titan-400: #1352a8;
            --titan-300: #1a6fd4;
            --titan-200: #3b8eea;
            --titan-100: #7db8f5;
            --titan-50: #c5dffb;

            --accent-gold: #f5c518;
            --accent-silver: #c0c7d4;
            --accent-bronze: #cd7f32;
            --accent-white: #f0f4ff;

            --glow-blue: rgba(19, 82, 168, 0.6);
            --glow-gold: rgba(245, 197, 24, 0.5);

            --font-display: 'Bebas Neue', sans-serif;
            --font-ui: 'Barlow Condensed', sans-serif;
            --font-body: 'Barlow', sans-serif;
        }

        body.sidebar-mini,
        .wrapper,
        .content-wrapper {
            background: var(--titan-900) !important;
        }

        .content-wrapper {
            padding: 0 !important;
            min-height: 100vh !important;
        }

        /* HERO SECTION */
        .cp-hero {
            position: relative;
            width: 100%;
            min-height: 380px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            overflow: hidden;
            padding-bottom: 2rem;
        }

        .cp-hero__bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 20%, rgba(19, 82, 168, 0.35) 0%, transparent 70%),
                linear-gradient(180deg, var(--titan-800) 0%, var(--titan-900) 100%);
        }

        /* pista diagonal  */
        .cp-hero__track {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: repeating-linear-gradient(90deg,
                    var(--accent-white) 0px, var(--accent-white) 30px,
                    transparent 30px, transparent 60px);
            opacity: 0.25;
        }

        /* grid F1  */
        .cp-hero__grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(19, 82, 168, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(19, 82, 168, 0.08) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* linha de luz lateral */
        .cp-hero__glow-left {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, transparent, var(--titan-300), transparent);
            opacity: 0.7;
        }

        .cp-hero__glow-right {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, transparent, var(--titan-300), transparent);
            opacity: 0.7;
        }

        .cp-hero__content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* bandeira xadrez */
        .cp-hero__flag {
            font-size: 2rem;
            letter-spacing: 0.1em;
            opacity: 0.5;
            margin-bottom: 0.5rem;
        }

        .cp-hero__title {
            font-family: var(--font-display);
            font-size: clamp(3.5rem, 8vw, 6rem);
            color: var(--accent-white);
            letter-spacing: 0.06em;
            line-height: 0.9;
            margin: 0;
            text-shadow:
                0 0 40px rgba(19, 82, 168, 0.9),
                0 0 80px rgba(19, 82, 168, 0.5),
                0 4px 20px rgba(0, 0, 0, 0.8);
        }

        .cp-hero__title span {
            color: var(--titan-200);
            display: block;
        }

        .cp-hero__year {
            font-family: var(--font-display);
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            color: var(--accent-gold);
            letter-spacing: 0.3em;
            margin-top: 0.25rem;
            text-shadow: 0 0 20px var(--glow-gold);
        }

        .cp-hero__subtitle {
            font-family: var(--font-ui);
            font-size: 1rem;
            font-weight: 600;
            color: var(--titan-100);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-top: 0.75rem;
        }

        /* BADGES DE META E PREMIAÇÃO*/
        .cp-badges {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            padding: 0 1.5rem 2rem;
        }

        .cp-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(13, 43, 88, 0.7);
            border: 1px solid rgba(59, 142, 234, 0.3);
            border-radius: 12px;
            padding: 1rem 1.75rem;
            backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, border-color 0.2s;
        }

        .cp-badge:hover {
            transform: translateY(-2px);
            border-color: rgba(59, 142, 234, 0.6);
        }

        .cp-badge::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(19, 82, 168, 0.15) 0%, transparent 60%);
            pointer-events: none;
        }

        .cp-badge__icon {
            font-size: 1.75rem;
            line-height: 1;
        }

        .cp-badge__label {
            font-family: var(--font-ui);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--titan-100);
            display: block;
        }

        .cp-badge__value {
            font-family: var(--font-display);
            font-size: 1.8rem;
            color: var(--accent-gold);
            line-height: 1;
            display: block;
            text-shadow: 0 0 16px var(--glow-gold);
        }

        .cp-badge__value.blue {
            color: var(--titan-200);
            text-shadow: 0 0 16px var(--glow-blue);
        }

        /* BARRA DE PROGRESSO GERAL DA CORRIDA */
        .cp-progress-section {
            padding: 0 1.5rem 2rem;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
        }

        .cp-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .cp-progress-title {
            font-family: var(--font-ui);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--titan-100);
        }

        .cp-progress-pct {
            font-family: var(--font-display);
            font-size: 1.4rem;
            color: var(--accent-gold);
            text-shadow: 0 0 12px var(--glow-gold);
        }

        .cp-progress-track {
            height: 12px;
            background: rgba(13, 43, 88, 0.8);
            border-radius: 6px;
            border: 1px solid rgba(59, 142, 234, 0.2);
            overflow: hidden;
            position: relative;
        }

        .cp-progress-fill {
            height: 100%;
            border-radius: 6px;
            background: linear-gradient(90deg, var(--titan-400), var(--titan-200), var(--accent-gold));
            background-size: 200% 100%;
            animation: shimmer 2s linear infinite;
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .cp-progress-fill::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 12px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 6px;
            filter: blur(4px);
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* PODIO PRINCIPAL */
        .cp-podium-section {
            padding: 0 1.5rem 3rem;
        }

        .cp-section-label {
            font-family: var(--font-display);
            font-size: 2.2rem;
            color: var(--accent-white);
            letter-spacing: 0.1em;
            text-align: center;
            margin-bottom: 0.25rem;
            text-shadow: 0 0 30px rgba(19, 82, 168, 0.6);
        }

        .cp-section-date {
            font-family: var(--font-ui);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--titan-100);
            text-align: center;
            margin-bottom: 2rem;
        }

        .cp-podium-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.25rem;
            max-width: 900px;
            margin: 0 auto;
        }

        /* card de cada squad */
        .cp-card {
            position: relative;
            border-radius: 16px;
            padding: 1.75rem 1.5rem;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
            cursor: default;
        }

        .cp-card:hover {
            transform: translateY(-4px);
        }

        /* P1 */
        .cp-card--p1 {
            background: linear-gradient(145deg, #0d1f3c, #0a2a5e);
            border: 1px solid rgba(245, 197, 24, 0.5);
            box-shadow:
                0 0 0 1px rgba(245, 197, 24, 0.15),
                0 8px 40px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(245, 197, 24, 0.2);
        }

        .cp-card--p1:hover {
            box-shadow:
                0 0 0 1px rgba(245, 197, 24, 0.35),
                0 16px 60px rgba(0, 0, 0, 0.5),
                0 0 40px rgba(245, 197, 24, 0.15);
        }

        /* P2 */
        .cp-card--p2 {
            background: linear-gradient(145deg, #0a1a30, #081a38);
            border: 1px solid rgba(192, 199, 212, 0.35);
            box-shadow:
                0 0 0 1px rgba(192, 199, 212, 0.1),
                0 8px 40px rgba(0, 0, 0, 0.5);
        }

        .cp-card--p2:hover {
            box-shadow:
                0 0 0 1px rgba(192, 199, 212, 0.25),
                0 16px 60px rgba(0, 0, 0, 0.5),
                0 0 30px rgba(192, 199, 212, 0.08);
        }

        /* P3 */
        .cp-card--p3 {
            background: linear-gradient(145deg, #0a1628, #08152a);
            border: 1px solid rgba(205, 127, 50, 0.3);
            box-shadow:
                0 0 0 1px rgba(205, 127, 50, 0.08),
                0 8px 40px rgba(0, 0, 0, 0.5);
        }

        .cp-card--p3:hover {
            box-shadow:
                0 0 0 1px rgba(205, 127, 50, 0.2),
                0 16px 60px rgba(0, 0, 0, 0.5);
        }

        /* fundo do card */
        .cp-card::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            opacity: 0.06;
            pointer-events: none;
        }

        .cp-card--p1::before {
            background: var(--accent-gold);
        }

        .cp-card--p2::before {
            background: var(--accent-silver);
        }

        .cp-card--p3::before {
            background: var(--accent-bronze);
        }

        /* rank badge (P1 P2 P3) */
        .cp-card__rank {
            font-family: var(--font-display);
            font-size: 3.5rem;
            line-height: 1;
            margin-bottom: 0.25rem;
            display: block;
        }

        .cp-card--p1 .cp-card__rank {
            color: var(--accent-gold);
            text-shadow: 0 0 20px var(--glow-gold);
        }

        .cp-card--p2 .cp-card__rank {
            color: var(--accent-silver);
            text-shadow: 0 0 16px rgba(192, 199, 212, 0.5);
        }

        .cp-card--p3 .cp-card__rank {
            color: var(--accent-bronze);
            text-shadow: 0 0 16px rgba(205, 127, 50, 0.5);
        }

        /* nome do squad */
        .cp-card__squad-sku {
            font-family: var(--font-display);
            font-size: 2.4rem;
            color: var(--accent-white);
            letter-spacing: 0.1em;
            display: block;
            line-height: 1;
        }

        .cp-card__squad-name {
            font-family: var(--font-ui);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--titan-100);
            margin-top: 0.15rem;
            display: block;
        }

        /* divisor */
        .cp-card__divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(59, 142, 234, 0.3), transparent);
            margin: 1rem 0;
        }

        /* profit */
        .cp-card__profit-label {
            font-family: var(--font-ui);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--titan-100);
            display: block;
            margin-bottom: 0.15rem;
        }

        .cp-card__profit-value {
            font-family: var(--font-display);
            font-size: 2.2rem;
            line-height: 1;
            display: block;
        }

        .cp-card--p1 .cp-card__profit-value {
            color: var(--accent-gold);
            text-shadow: 0 0 16px var(--glow-gold);
        }

        .cp-card--p2 .cp-card__profit-value {
            color: var(--accent-silver);
        }

        .cp-card--p3 .cp-card__profit-value {
            color: var(--titan-100);
        }

        /* barra de progresso individual do squad */
        .cp-card__bar-wrap {
            margin-top: 1rem;
        }

        .cp-card__bar-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.4rem;
        }

        .cp-card__bar-label {
            font-family: var(--font-ui);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--titan-100);
        }

        .cp-card__bar-pct {
            font-family: var(--font-ui);
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--accent-white);
        }

        .cp-card__bar-track {
            height: 6px;
            background: rgba(7, 20, 40, 0.8);
            border-radius: 3px;
            overflow: hidden;
        }

        .cp-card__bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 1.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cp-card--p1 .cp-card__bar-fill {
            background: linear-gradient(90deg, var(--titan-400), var(--accent-gold));
        }

        .cp-card--p2 .cp-card__bar-fill {
            background: linear-gradient(90deg, var(--titan-400), var(--titan-200));
        }

        .cp-card--p3 .cp-card__bar-fill {
            background: linear-gradient(90deg, var(--titan-600), var(--titan-300));
        }

        /* Badge "Meta atingida!" */
        .cp-card__meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(245, 197, 24, 0.15);
            border: 1px solid rgba(245, 197, 24, 0.4);
            border-radius: 20px;
            padding: 0.3rem 0.75rem;
            margin-top: 0.75rem;
            font-family: var(--font-ui);
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--accent-gold);
        }

        /* RODAPE DA TELA */
        .cp-footer {
            text-align: center;
            padding: 1.5rem;
            font-family: var(--font-body);
            font-size: 0.75rem;
            color: var(--titan-400);
            border-top: 1px solid rgba(19, 82, 168, 0.15);
        }

        /* LOADING OVERLAY (loading state enquanto job processa) */
        .cp-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 4rem;
            text-align: center;
        }

        .cp-loading__spinner {
            width: 48px;
            height: 48px;
            border: 3px solid rgba(19, 82, 168, 0.2);
            border-top-color: var(--titan-300);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .cp-loading__text {
            font-family: var(--font-ui);
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--titan-100);
        }

        /* BUTTON ATUALIZAR */
        .cp-refresh-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(19, 82, 168, 0.2);
            border: 1px solid rgba(59, 142, 234, 0.4);
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            color: var(--titan-100);
            font-family: var(--font-ui);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .cp-refresh-btn:hover {
            background: rgba(19, 82, 168, 0.4);
            border-color: rgba(59, 142, 234, 0.7);
            color: var(--accent-white);
        }

        /* ANIMACOES DE ENTRADA */
        .cp-hero__content,
        .cp-badges,
        .cp-progress-section,
        .cp-podium-section {
            animation: fadeUp 0.6s ease both;
        }

        .cp-badges {
            animation-delay: 0.1s;
        }

        .cp-progress-section {
            animation-delay: 0.2s;
        }

        .cp-podium-section {
            animation-delay: 0.3s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cp-card {
            animation: fadeUp 0.5s ease both;
        }

        .cp-card:nth-child(1) {
            animation-delay: 0.35s;
        }

        .cp-card:nth-child(2) {
            animation-delay: 0.45s;
        }

        .cp-card:nth-child(3) {
            animation-delay: 0.55s;
        }

        /* RESPONSIVO basico */
        @media (max-width: 576px) {
            .cp-hero {
                min-height: 280px;
            }

            .cp-podium-grid {
                grid-template-columns: 1fr;
            }

            .cp-badges {
                gap: 0.75rem;
            }

            .cp-badge {
                padding: 0.75rem 1.25rem;
            }
        }
    </style>




    {{-- HERO --}}
    <section class="cp-hero">
        <div class="cp-hero__bg"></div>
        <div class="cp-hero__grid"></div>
        <div class="cp-hero__glow-left"></div>
        <div class="cp-hero__glow-right"></div>
        <div class="cp-hero__track"></div>

        <div class="cp-hero__content">
            <div class="cp-hero__flag">🏁 &nbsp;&nbsp;&nbsp; 🏁</div>
            <h1 class="cp-hero__title">
                Corrida do
                <span>Profit</span>
            </h1>
            <div class="cp-hero__year">{{ $corridaYear }}</div>
            <div class="cp-hero__subtitle">Titan &nbsp;·&nbsp; Temporada Oficial</div>
        </div>
    </section>

    {{-- BADGES — META & PREMIACAO --}}
    <div class="cp-badges">
        <div class="cp-badge">
            <div class="cp-badge__icon">🏆</div>
            <div>
                <span class="cp-badge__label">Premiação</span>
                <span class="cp-badge__value">$ {{ number_format($premioCorreida, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="cp-badge">
            <div class="cp-badge__icon">🎯</div>
            <div>
                <span class="cp-badge__label">Meta de Profit</span>
                <span class="cp-badge__value blue">
                    $
                    {{ $metaProfit >= 1_000_000
                        ? number_format($metaProfit / 1_000_000, 1, ',', '.') . 'M'
                        : number_format($metaProfit, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="cp-badge">
            <div class="cp-badge__icon">📅</div>
            <div>
                <span class="cp-badge__label">Atualizado em</span>
                <span class="cp-badge__value blue" style="font-size:1.1rem">
                    {{ $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->format('d/m/Y H:i') : '—' }}
                </span>
            </div>
        </div>

        {{-- button de atualizar --}}
        <button class="cp-refresh-btn" id="cp-refresh-btn" onclick="refreshCache()">
            ↻ &nbsp; Atualizar
        </button>
    </div>

    {{-- BARRA DE PROGRESSO GERAL --}}
    <div class="cp-progress-section">
        <div class="cp-progress-header">
            <span class="cp-progress-title">🏎️ &nbsp; Progresso total da corrida</span>
            <span class="cp-progress-pct" id="cp-total-pct">{{ number_format($corridaProgress, 1, ',', '.') }}%</span>
        </div>
        <div class="cp-progress-track">
            <div class="cp-progress-fill" id="cp-total-bar" style="width: 0%"></div>
        </div>
    </div>

    {{-- PODIO --}}
    <section class="cp-podium-section">
        <div class="cp-section-label">🏁 &nbsp; Pódio dos Squads</div>
        <div class="cp-section-date">
            {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
            &nbsp;→&nbsp;
            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        </div>

        @if (empty($podium))
            {{-- loading state enquanto job processa --}}
            <div class="cp-loading">
                <div class="cp-loading__spinner"></div>
                <div class="cp-loading__text">Carregando dados da corrida…</div>
            </div>
        @else
            <div class="cp-podium-grid">
                @foreach ($podium as $squad)
                    @php
                        $rankClass = match ($squad['rank']) {
                            1 => 'cp-card--p1',
                            2 => 'cp-card--p2',
                            default => 'cp-card--p3',
                        };
                        $rankLabel = 'P' . $squad['rank'];
                        $profitFormatted =
                            $squad['profit'] >= 1_000_000
                                ? '$ ' . number_format($squad['profit'] / 1_000_000, 2, ',', '.') . 'M'
                                : '$ ' . number_format($squad['profit'] / 1_000, 1, ',', '.') . 'K';
                    @endphp

                    <div class="cp-card {{ $rankClass }}">
                        <span class="cp-card__rank">{{ $rankLabel }}</span>
                        <span class="cp-card__squad-sku">{{ $squad['sku'] }}</span>
                        <span class="cp-card__squad-name">{{ $squad['label'] }} Squad</span>

                        <div class="cp-card__divider"></div>

                        <span class="cp-card__profit-label">Profit acumulado</span>
                        <span class="cp-card__profit-value">{{ $profitFormatted }}</span>

                        <div class="cp-card__bar-wrap">
                            <div class="cp-card__bar-header">
                                <span class="cp-card__bar-label">Meta: $ 1,5M</span>
                                <span
                                    class="cp-card__bar-pct">{{ number_format($squad['progress'], 1, ',', '.') }}%</span>
                            </div>
                            <div class="cp-card__bar-track">
                                <div class="cp-card__bar-fill" data-pct="{{ $squad['progress'] }}" style="width: 0%">
                                </div>
                            </div>
                        </div>

                        @if ($squad['meta_atingida'])
                            <div class="cp-card__meta-badge">
                                🏆 &nbsp; Meta atingida!
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- RODAPE --}}
    <footer class="cp-footer">
        Corrida do Profit {{ $corridaYear }} &nbsp;·&nbsp; Titan &nbsp;·&nbsp; Dados via Redtrack
        &nbsp;·&nbsp;
        Cache atualiza a cada 10 minutos
    </footer>




    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // anima barra de progresso geral
            setTimeout(() => {
                const bar = document.getElementById('cp-total-bar');
                if (bar) {
                    bar.style.width = '{{ $corridaProgress }}%';
                }
            }, 400);

            // anima barras individuais dos squads
            document.querySelectorAll('.cp-card__bar-fill[data-pct]').forEach((el, i) => {
                setTimeout(() => {
                    el.style.width = el.dataset.pct + '%';
                }, 500 + (i * 100));
            });

        });

        // button atualizar
        function refreshCache() {
            const btn = document.getElementById('cp-refresh-btn');
            btn.disabled = true;
            btn.textContent = '⏳  Atualizando…';

            fetch('{{ route('admin.corrida-profit.refresh') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(() => {
                    setTimeout(() => window.location.reload(), 8000);
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.textContent = '↻  Atualizar';
                });
        }
    </script>



</x-layout>