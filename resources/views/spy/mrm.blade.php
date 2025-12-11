<x-spy-component>
    {{-- CSS --}}
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/spy-mrm.css') }}">
    @endpush

    <div class="mrm-container">

        {{-- FILTROS DE PERIODO E NICHOS --}}
        <div class="mrm-filters-bar glass-floating">
            <div class="filter-group">
                <label for="periodo"><i class="fas fa-calendar-alt"></i> Período</label>
                <div class="fake-select glass-input">
                    <span>01/10/2023 - 31/10/2023</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="filter-group">
                <label for="nicho"><i class="fas fa-layer-group"></i> Nicho</label>
                <div class="fake-select glass-input">
                    <span>Todos os Nichos</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
             <div class="filter-actions">
                <button class="btn-titan-glow"><i class="fas fa-filter"></i> Filtrar Dados</button>
            </div>
        </div>

        {{-- CARD VISAO GERAL --}}
        <div class="mrm-hero-card glass-panoramic titan-glow-border">
            <div class="hero-header">
                <h2 class="hero-title"><i class="fas fa-tachometer-alt"></i> Visão Geral de Performance</h2>
                <span class="hero-badge" style="color: #fff">Última atualização em: 06/12/2025 12:45:14</span>
            </div>
            
            <div class="hero-metrics-grid">
                {{-- METRICAS PRINCIPAIS --}}
                <div class="metric-box main-metric revenue">
                    <span class="metric-label">Faturamento Total</span>
                    <h3 class="metric-value">$ 12.450.900,00</h3>
                    <div class="metric-trend positive" style="color: #0f53ff;">
                    <i class="fas fa-arrow-up" style="color: #0f53ff;"></i> +15% este mês
                </div>

                </div>
                
                <div class="metric-box main-metric cost">
                    <span class="metric-label">Custo Total</span>
                    <h3 class="metric-value">$ 4.200.500,00</h3>
                </div>

                <div class="metric-box main-metric profit titan-highlight">
                    <span class="metric-label">Profit Bruto</span>
                    <h3 class="metric-value titan-text">$ 8.250.400,00</h3>
                    <span class="metric-subtext">(Faturamento - Custo)</span>
                </div>

                 {{-- METRICAS SEGUNDARIAS --}}
                <div class="secondary-metrics-container glass-inner-card">
                    <div class="metric-box secondary">
                        <span class="metric-label">Imposto (7,68% Fat.)</span>
                        <h4 class="metric-value-sm">$ 956.229,12</h4>
                    </div>
                    <div class="metric-separator"></div>
                    <div class="metric-box secondary">
                        <span class="metric-label">Taxas (1,5% Custo)</span>
                        <h4 class="metric-value-sm">$ 63.007,50</h4>
                    </div>
                    <div class="metric-separator"></div>
                    <div class="metric-box secondary net-profit">
                        <span class="metric-label">Lucro Líquido Final</span>
                        <h4 class="metric-value-sm titan-text-glow">$ 7.231.163,38</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- SESSAO MRM1 E MRM2 SEPARADAS --}}
        <div class="mrm-split-section">

            {{-- BLOCO MRM1 --}}
            <div class="mrm-block glass-card titan-accent-left">
                <div class="block-header">
                    <h3 class="section-title titan-title">MRM1 <span class="commission-badge">2% de comissão</span></h3>
                </div>

                {{-- METRICAS CRIATIVO MRM1 --}}
                <div class="block-metrics-grid glass-inner-card">
                    <div class="mini-metric">
                        <span>Faturamento</span>
                        <strong>R$ 6.225.450,00</strong>
                    </div>
                    <div class="mini-metric">
                        <span>Custo</span>
                        <strong>R$ 2.100.250,00</strong>
                    </div>
                    
                    {{-- METRICAS DE TAXA IMPOSTO E LUCRO LIQUIDO  --}}
                    <div class="mini-metric">
                        <span>Imposto (7,68% Fat.)</span>
                        <strong class="text-muted-dark">R$ 478.074,24</strong>
                    </div>
                    <div class="mini-metric">
                        <span>Taxas (1,5% Custo)</span>
                        <strong class="text-muted-dark">R$ 31.503,75</strong>
                    </div>

                    <div class="mini-metric highlight">
                        <span>Profit Bruto</span>
                        <strong class="titan-text">R$ 4.125.200,00</strong>
                    </div>
                    <div class="mini-metric commission-box final-liquid-box">
                        <span>Lucro Líquido Final</span>
                        <strong class="titan-text-glow">R$ 3.615.622,01</strong>
                    </div>

                    <div class="mini-metric commission-box">
                        <span>Sua Comissão (2% Profit)</span>
                        <strong class="titan-text-glow">R$ 82.504,00</strong>
                    </div>
                </div>

                {{-- TABELA MRM1 METRICAS --}}
                <div class="table-container">
                    <h4 class="table-title"><i class="fas fa-photo-video"></i> Top Criativos</h4>
                    <table class="mrm-table glass-table">
                        <thead>
                            <tr>
                                <th>Nome do Criativo</th>
                                <th>Status</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="creative-name">WLAD639 H1</span></td>
                                <td><span class="status-badge active">Profit</span></td>
                                <td>$</td>
                            </tr>
                             <tr>
                                <td><span class="creative-name">WLAD694 H1</span></td>
                                <td><span class="status-badge active">Profit</span></td>
                                <td>$</td>
                            </tr>
                             <tr>
                                <td><span class="creative-name">WLAD640 H1</span></td>
                                <td><span class="status-badge paused">Profit</span></td>
                                <td>$</td>
                            </tr>
                             <tr>
                                <td><span class="creative-name">MMAD69 H2</span></td>
                                <td><span class="status-badge active">Profit</span></td>
                                <td>$</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                 {{-- TABALA VSLS MRM1 --}}
                 <div class="table-container mt-4">
                    <h4 class="table-title"><i class="fas fa-video"></i> Performance VSLs</h4>
                    <table class="mrm-table glass-table">
                        <thead>
                            <tr>
                                <th>Nome da VSL</th>
                                <th>Status</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="vsl-name">WLAD639 H1</span></td>
                                <td><span class="status-badge active">Profit</span></td>
                                <td>$</td>
                            </tr>
                            <tr>
                                <td><span class="vsl-name">WLAD640 H1</span></td>
                                <td><span class="status-badge testing">Profit</span></td>
                                <td>$</td>
                            </tr>
                              <tr>
                                <td><span class="vsl-name">WLAD540 H2</span></td>
                                <td><span class="status-badge inactive">Profit</span></td>
                                <td>$</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- BLOCO MRM2 --}}
            <div class="mrm-block glass-card violet-accent-left">
                <div class="block-header">
                    <h3 class="section-title violet-title">MRM2 <span class="commission-badge violet">1% de comissão</span></h3>
                </div>

                 {{-- METRICAS CRIATIVO MRM2 --}}
                <div class="block-metrics-grid glass-inner-card">
                    <div class="mini-metric">
                        <span>Faturamento</span>
                        <strong>R$ 6.225.450,00</strong>
                    </div>
                    <div class="mini-metric">
                        <span>Custo</span>
                        <strong>R$ 2.100.250,00</strong>
                    </div>

                    {{-- METRICAS DE TAXA IMPOSTO E LUCRO LIQUIDO --}}
                    <div class="mini-metric">
                        <span>Imposto (7,68% Fat.)</span>
                        <strong class="text-muted-dark">R$ 478.074,24</strong>
                    </div>
                    <div class="mini-metric">
                        <span>Taxas (1,5% Custo)</span>
                        <strong class="text-muted-dark">R$ 31.503,75</strong>
                    </div>
                    
                    <div class="mini-metric highlight">
                        <span>Profit Bruto</span>
                        <strong class="violet-text">R$ 4.125.200,00</strong>
                    </div>
                    <div class="mini-metric commission-box final-liquid-box violet-glow">
                        <span>Lucro Líquido Final</span>
                        <strong class="violet-text-glow">R$ 3.615.622,01</strong>
                    </div>

                    <div class="mini-metric commission-box violet-glow">
                        <span>Sua Comissão (1% Profit)</span>
                        <strong 
                        class="violet-text-glow"
                        style="
                            color: #8400ff; 
                            text-shadow: 
                                0 0 10px #0000005b,
                                0 0 20px #0202023b,
                                0 0 30px #0000008a;
                        "
                    >
                        R$ 41.252,00
                    </strong>
                    </div>
                </div>

                 {{-- TABELA MRM2 METRICAS --}}
                 <div class="table-container">
                    <h4 class="table-title"><i class="fas fa-photo-video"></i> Top Criativos</h4>
                    <table class="mrm-table glass-table">
                        <thead>
                            <tr>
                                <th>Nome do Criativo</th>
                                <th>Status</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                                <td><span class="creative-name">PRAD22 H1</span></td>
                                <td><span class="status-badge active">Profit</span></td>
                                <td>$</td>
                            </tr>
                             <tr>
                                <td><span class="creative-name">WLAD540 H3</span></td>
                                <td><span class="status-badge active">Profit</span></td>
                                <td>$</td>
                            </tr>
                            <tr>
                                <td><span class="creative-name">PRAD22 H2</span></td>
                                <td><span class="status-badge active">Profit</span></td>
                                <td>$</td>
                            </tr>
                             <tr>
                                <td><span class="creative-name">PRAD24 H1</span></td>
                                <td><span class="status-badge inactive">Profit</span></td>
                                <td>$</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                 {{--TABALA VSLS MRM2 --}}
                 <div class="table-container mt-4">
                    <h4 class="table-title"><i class="fas fa-video"></i> Performance VSLs</h4>
                    <table class="mrm-table glass-table">
                        <thead>
                            <tr>
                                <th>Nome da VSL</th>
                                <th>Status</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="vsl-name">WLAD681 H1</span></td>
                                <td><span class="status-badge active">Profit</span></td>
                                <td>$</td>
                            </tr>
                            <tr>
                                <td><span class="vsl-name">WLAD733 H1</span></td>
                                <td><span class="status-badge testing">Profit</span></td>
                                <td>$</td>
                            </tr>
                             <tr>
                                <td><span class="vsl-name">MMAD20 H2</span></td>
                                <td><span class="status-badge testing">Profit</span></td>
                                <td>$</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> {{-- FIM SPLIT SECTION --}}
    </div> {{-- FIM CONTAINER --}}
</x-spy-component>