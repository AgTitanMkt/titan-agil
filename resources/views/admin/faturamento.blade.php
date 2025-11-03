<x-layout>
    <h2 class="dashboard-page-title">Faturamento</h2>
    <p class="dashboard-page-subtitle">Monitoramento completo da saúde financeira da Titan</p>

    <div class="metrics-top-grid">
        <div class="metric-card glass-card">
            <p class="metric-title">Faturamento Total</p>
            <h3 class="metric-value">R$ 390.365,22</h3>
            <div class="metric-footer">
                <span class="metric-change positive">13,4%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                    <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder">
                    <path d="M0 25 L20 15 L40 20 L60 10 L80 18 L100 5" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
                </div>
            </div>
        </div>

        <div class="metric-card glass-card">
            <p class="metric-title">Custo Total</p>
            <h3 class="metric-value">R$ 150.000,00</h3>
            <div class="metric-footer">
                <span class="metric-change negative">-5,1%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder">
                     <path d="M0 5 L20 15 L40 10 L60 20 L80 15 L100 25" fill="none" stroke="#ff4d4d" stroke-width="2"/></svg>
                </div>
            </div>
        </div>

        <div class="metric-card glass-card">
            <p class="metric-title">Lucro Total</p>
            <h3 class="metric-value">R$ 240.365,22</h3>
            <div class="metric-footer">
                <span class="metric-change positive">18,5%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder">
                     <path d="M0 10 L20 20 L40 15 L60 25 L80 12 L100 5" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
                </div>
            </div>
        </div>

        <div class="metric-card glass-card">
            <p class="metric-title">ROI (%)</p>
            <h3 class="metric-value">160.24</h3>
            <div class="metric-footer">
                <span class="metric-change positive">10,1%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder">
                     <path d="M0 10 L20 15 L40 8 L60 20 L80 15 L100 25" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
                </div>
            </div>
        </div>
    </div>



    <div class="chart-section glass-card">
        <h3 class="section-title">Gráfico de Faturamento</h3>
        <p class="chart-subtitle">Visualização completa do faturamento mensal ao longo do ano</p>
        <div class="revenue-chart-placeholder">
            <div class="chart-bar-grid">
                <div class="chart-bar" style="height: 35%" title="Jan" data-value="R$ 150.000"></div>
                <div class="chart-bar" style="height: 45%" title="Fev" data-value="R$ 190.000"></div>
                <div class="chart-bar" style="height: 60%" title="Mar" data-value="R$ 250.000"></div>
                <div class="chart-bar" style="height: 20%" title="Abr" data-value="R$ 90.000"></div>
                <div class="chart-bar" style="height: 70%" title="Mai" data-value="R$ 290.000"></div>
                <div class="chart-bar" style="height: 85%" title="Jun" data-value="R$ 350.000"></div>
                <div class="chart-bar" style="height: 50%" title="Jul" data-value="R$ 210.000"></div>
                <div class="chart-bar chart-bar-highlight" style="height: 95%" title="Ago" data-value="R$ 390.365"></div>
                <div class="chart-bar" style="height: 40%" title="Set" data-value="R$ 170.000"></div>
                <div class="chart-bar" style="height: 75%" title="Out" data-value="R$ 310.000"></div>
                <div class="chart-bar" style="height: 30%" title="Nov" data-value="R$ 130.000"></div>
                <div class="chart-bar" style="height: 65%" title="Dez" data-value="R$ 270.000"></div>
            </div>
            <div class="chart-labels">
                <span>Jan</span><span>Fev</span><span>Mar</span><span>Abr</span><span>Mai</span><span>Jun</span><span>Jul</span><span>Ago</span><span>Set</span><span>Out</span><span>Nov</span><span>Dez</span>
            </div>
        </div>
    </div>
    



<div class="glass-card meta-ads-section" style="z-index: 20;"> 
    <div class="filters-grid" style="grid-template-columns: 1fr 1fr 1fr auto;"> 
        {{-- filtro de data --}}
        <div class="filter-group">
            <label for="date-filter">Data</label>
            <input type="date" id="date-filter" value="2025-10-27"> 
        </div>

        {{-- filtro de titulo --}}
        <div class="filter-group">
            <label for="title-filter">Título</label>
            <input type="text" id="title-filter" placeholder="Buscar por Título"> 
        </div>

        {{-- FILTRO CANAIS DE TRAFEGO --}}
        <div class="filter-group">
            <label for="traffic-channels-filter">Canais de Tráfego</label>
            <div id="traffic-channels-filter" class="custom-dropdown-filter" tabindex="0">
                <div class="dropdown-header">
                    <span class="selected-items-display">Canais (0)</span> 
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>
                <div class="dropdown-list">
                    <label class="dropdown-item"><input type="checkbox" name="channels[]" value="yt_shenlong">YT Shenlong</label>
                    <label class="dropdown-item"><input type="checkbox" name="channels[]" value="facebook_ads">Facebook Ads</label>
                    <label class="dropdown-item"><input type="checkbox" name="channels[]" value="google_ads">Google Ads</label>
                    <label class="dropdown-item"><input type="checkbox" name="channels[]" value="native_ads">Native Ads</label>
                    <label class="dropdown-item"><input type="checkbox" name="channels[]" value="tiktok_ads">TikTok Ads</label>
                </div>
            </div>
        </div>
        
        <div class="filter-submit-area" style="grid-column: 4 / 5;">
            <button type="button" class="btn-filter" style="background-color: transparent; border: 1px solid var(--input-border); margin-right: 10px;">LIMPAR</button>
            <button type="submit" class="btn-filter">APLICAR</button>
        </div>
    </div>
</div>


    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropdowns = document.querySelectorAll('.custom-dropdown-filter');

        dropdowns.forEach(dropdown => {
            const header = dropdown.querySelector('.dropdown-header');
            const display = dropdown.querySelector('.selected-items-display');
            const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
            // pega o texto base ('Canais' 'Canais de Tráfego')
            const baseText = dropdown.querySelector('label')?.textContent || display.textContent.split('(')[0].trim(); 

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
                document.querySelectorAll('.custom-dropdown-filter.open').forEach(openDropdown => {
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
        <h3 class="section-title">Métricas de Campanha</h3>

        <div class="table-responsive">
            <table class="metrics-main-table">
                <thead>
                    <tr>
                        <th class="col-main">ID/TÍTULO/FONTE</th>
                        <th>CUSTO</th>
                        <th>RECEITA TOTAL</th>
                        <th class="col-roi">ROI (%)</th>
                        <th>COMPRAS</th>
                        <th>INIC. CHECKOUT</th>
                        <th>CUSTO IC</th>
                        <th>CPA</th>
                        <th>CLIQUES</th>
                    </tr>
                </thead>
                <tbody>
                    

                    
                    <tr class="campaign-row" data-id="1" onclick="toggleDetails(1)">
                        <td class="col-main-data">
                            <p class="item-name">Campanha VSL</p>
                            <span class="item-id">ID: 45884352</span>
                            <span class="item-source">Fonte: YT Shenlong</span>
                        </td>
                        <td>R$ 1.500</td>
                        <td>R$ 5.000</td>
                        <td class="col-roi-value positive">233%</td>
                        <td>50</td>
                        <td>120</td>
                        <td>R$ 12,50</td>
                        <td>R$ 30,00</td>
                        <td>3.000</td>
                    </tr>

                    <tr class="details-row" id="details-row-1">
                        <td colspan="9" class="details-content-v2">
                            
                            <div class="details-header-visual-v2">
                                <i class="fas fa-magic"></i> CRIATIVOS EM DETALHE DA CAMPANHA: <span class="campaign-title">VSL Produto X</span>
                            </div>

                            <div class="creatives-container-v2">
                                
                                <div class="creative-card-v2 glass-card-lite">
                                    
                                    <div class="creative-info-header">
                                        <div class="creative-thumb-v2 creative-thumb-blue"></div>
                                        <div class="creative-text">
                                            <p class="item-name">Criativo VSL - Teste</p>
                                            <span class="item-id">ID: 99887766</span>
                                            <span class="item-status status-ativo">ATIVO</span>
                                        </div>
                                    </div>
                                    
                                    <div class="creative-metrics-grid">
                                        
                                        <div class="metric-item">
                                            <span class="metric-label">RECEITA TOTAL</span>
                                            <span class="metric-value-lg">R$ 2.000</span>
                                        </div>
                                        <div class="metric-item">
                                            <span class="metric-label">CUSTO</span>
                                            <span class="metric-value-lg">R$ 500</span>
                                        </div>
                                        <div class="metric-item">
                                            <span class="metric-label">PROFIT</span>
                                            <span class="metric-value-lg positive">R$ 1.500</span>
                                        </div>
                                        <div class="metric-item">
                                            <span class="metric-label">ROI (%)</span>
                                            <span class="metric-value-lg roi-v2">300%</span>
                                        </div>
                                        
                                    </div>

                                    <div class="creative-metrics-secondary">
                                        
                                        <div class="secondary-item">
                                            <span class="metric-label">COMPRAS:</span>
                                            <span class="metric-value-sm">20</span>
                                        </div>
                                        <div class="secondary-item">
                                            <span class="metric-label">INIC. CHECKOUT:</span>
                                            <span class="metric-value-sm">50</span>
                                        </div>
                                        <div class="secondary-item">
                                            <span class="metric-label">CUSTO IC:</span>
                                            <span class="metric-value-sm">R$ 10,00</span>
                                        </div>
                                        <div class="secondary-item">
                                            <span class="metric-label">CPA:</span>
                                            <span class="metric-value-sm">R$ 25,00</span>
                                        </div>
                                        <div class="secondary-item">
                                            <span class="metric-label">CLIQUES:</span>
                                            <span class="metric-value-sm">1.000</span>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                </div>
                        </td>
                    </tr>

                    <tr class="campaign-row" data-id="2" onclick="toggleDetails(2)">
                     <td class="col-main-data">
                    <p class="item-name">Campanha 2</p>
                     <span class="item-id">ID: 11223344</span>
                    <span class="item-source">Fonte: Facebook Ads</span>
                    </td>
                    <td>R$ 800</td>
                    <td>R$ 1.200</td>
                    <td class="col-roi-value negative">50%</td>
                    <td>10</td>
                    <td>30</td>
                    <td>R$ 26,67</td>
                    <td>R$ 80,00</td>
                    <td>1.500</td>
                </tr>

                  <tr class="details-row" id="details-row-2">
                <td colspan="9" class="details-content-v2">
                 <div class="details-header-visual-v2">
                 <i class="fas fa-magic"></i> CRIATIVOS EM DETALHE DA CAMPANHA: <span class="campaign-title">Campanha 2 - Facebook Ads</span>
             </div>

                <div class="creatives-container-v2">
                 <div class="creative-card-v2 glass-card-lite">
                <div class="creative-info-header">
                    <div class="creative-thumb-v2 creative-thumb-red"></div>
                    <div class="creative-text">
                        <p class="item-name">Criativo FB - Conversão</p>
                        <span class="item-id">ID: 33445566</span>
                        <span class="item-status status-pausado">PAUSADO</span>
                    </div>
                </div>

                <div class="creative-metrics-grid">
                    <div class="metric-item"><span class="metric-label">RECEITA TOTAL</span>
                    <span class="metric-value-lg">R$ 1.200</span></div>

                    <div class="metric-item"><span class="metric-label">CUSTO</span>
                    <span class="metric-value-lg">R$ 800</span></div>

                    <div class="metric-item"><span class="metric-label">PROFIT</span>
                    <span class="metric-value-lg negative">R$ 400</span></div>

                    <div class="metric-item"><span class="metric-label">ROI (%)</span>
                    <span class="metric-value-lg roi-v2">50%</span></div>
                </div>

                <div class="creative-metrics-secondary">
                    <div class="secondary-item"><span class="metric-label">COMPRAS:</span>
                    <span class="metric-value-sm">10</span></div>

                    <div class="secondary-item"><span class="metric-label">INIC. CHECKOUT:</span>
                    <span class="metric-value-sm">30</span></div>

                    <div class="secondary-item"><span class="metric-label">CUSTO IC:</span>
                    <span class="metric-value-sm">R$ 26,67</span></div>

                    <div class="secondary-item"><span class="metric-label">CPA:</span>
                    <span class="metric-value-sm">R$ 80,00</span></div>

                    <div class="secondary-item"><span class="metric-label">CLIQUES:</span>
                    <span class="metric-value-sm">1.500</span></div>
                </div>
            </div>
        </div>
    </td>
</tr>
                    
                    <tr class="campaign-row zero-metrics" data-id="3" onclick="toggleDetails(3)">
                     <td class="col-main-data">
                     <p class="item-name">Campanha 3 - (Zerada)</p>
                     <span class="item-id">ID: 55667788</span>
                    <span class="item-source">Fonte: Native Ads</span>
                    </td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td class="col-roi-value">0%</td>
                    <td>0</td>
                    <td>0</td>
                    <td>R$ 0,00</td>
                    <td>R$ 0,00</td>
                    <td>0</td>
                </tr>

                    <tr class="details-row" id="details-row-3">
                    <td colspan="9" class="details-content-v2">
                    <div class="details-header-visual-v2">
                    <i class="fas fa-magic"></i> CRIATIVOS EM DETALHE DA CAMPANHA: <span class="campaign-title">Campanha 3 - Native Ads</span>
                </div>

                    <div class="creatives-container-v2">
                     <div class="creative-card-v2 glass-card-lite">
                    <div class="creative-info-header">
                    <div class="creative-thumb-v2 creative-thumb-gray"></div>
                    <div class="creative-text">
                        <p class="item-name">Nenhum criativo ativo</p>
                        <span class="item-id">ID: —</span>
                        <span class="item-status status-inativo">INATIVO</span>
                    </div>
                </div>

                <div class="creative-metrics-grid">
                    <div class="metric-item"><span class="metric-label">RECEITA TOTAL</span>
                    <span class="metric-value-lg">R$ 0,00</span></div>

                    <div class="metric-item"><span class="metric-label">CUSTO</span>
                    <span class="metric-value-lg">R$ 0,00</span></div>

                    <div class="metric-item"><span class="metric-label">PROFIT</span>
                    <span class="metric-value-lg">R$ 0,00</span></div>

                    <div class="metric-item"><span class="metric-label">ROI (%)</span>
                    <span class="metric-value-lg roi-v2">0%</span></div>
                </div>

                <div class="creative-metrics-secondary">
                    <div class="secondary-item"><span class="metric-label">COMPRAS:</span>
                    <span class="metric-value-sm">0</span></div>

                    <div class="secondary-item"><span class="metric-label">INIC. CHECKOUT:</span>
                    <span class="metric-value-sm">0</span></div>

                    <div class="secondary-item"><span class="metric-label">CUSTO IC:</span>
                    <span class="metric-value-sm">R$ 0,00</span></div>

                    <div class="secondary-item"><span class="metric-label">CPA:</span>
                    <span class="metric-value-sm">R$ 0,00</span></div>

                    <div class="secondary-item"><span class="metric-label">CLIQUES:</span>
                    <span class="metric-value-sm">0</span></div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                             </td>
                     </tr>
              </tbody>
        </table>
     </div>
        
        <div class="pagination-area">
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
        </div>
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




</x-layout>