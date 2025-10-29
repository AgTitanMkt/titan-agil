<x-layout>
    <h2 class="dashboard-page-title">Time</h2>
    <p class="dashboard-page-subtitle">Visão geral do desempenho da equipe, projetos e nichos.</p>

    <div class="metrics-top-grid">
        <div class="metric-card glass-card">
            <p class="metric-title">Faturamento Total</p>
            <h3 class="metric-value">R$ 390.365,22</h3>
            <div class="metric-footer">
                <span class="metric-change positive">13,4%</span>
                <span class="metric-period">Na última semana</span>
                <div class="metric-sparkline">
                    <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder"><path d="M0 25 L20 15 L40 20 L60 10 L80 18 L100 5" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
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
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder"><path d="M0 5 L20 15 L40 10 L60 20 L80 15 L100 25" fill="none" stroke="#ff4d4d" stroke-width="2"/></svg>
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
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder"><path d="M0 10 L20 20 L40 15 L60 25 L80 12 L100 5" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
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
                     <svg width="100" height="30" viewBox="0 0 100 30" class="sparkline-placeholder"><path d="M0 10 L20 15 L40 8 L60 20 L80 15 L100 25" fill="none" stroke="#4CAF50" stroke-width="2"/></svg>
                </div>
            </div>
        </div>
    </div>


    <div class="main-content-grid-time">
        
        <div class="left-cards-column">
            
            <div class="mini-card-grid">
                <div class="mini-card glass-card">
                    <div class="mini-card-content">
                        <i class="fas fa-chart-line icon-profit"></i>
                        <div class="text-group">
                            <span class="card-title-lg">Profit</span>
                            <h4 class="card-value-lg">5.423</h4>
                            <span class="card-subtitle-sm">5% Mais</span>
                        </div>
                    </div>
                </div>

                <div class="mini-card glass-card">
                    <div class="mini-card-content">
                        <i class="fas fa-users icon-time"></i>
                        <div class="text-group">
                            <span class="card-title-lg">Time</span>
                            <h4 class="card-value-lg">53</h4>
                            <span class="card-subtitle-sm">Este mês</span>
                        </div>
                    </div>
                </div>

                <div class="mini-card glass-card full-width">
                    <div class="mini-card-content">
                        <i class="fas fa-laptop-code icon-projects"></i>
                        <div class="text-group">
                            <span class="card-title-lg">Projetos Ativos</span>
                            <h4 class="card-value-lg">189</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="performance-chart-card glass-card">
                <h3 class="section-title-time">Desempenho da Equipe (RedTrack)</h3>
                <div class="chart-placeholder-time">
                    <div class="chart-time-container">
                        <div class="chart-axis-y">
                            <span>400</span><span>300</span><span>200</span><span>100</span><span>0</span>
                        </div>
                        <div class="chart-area-time">
                            <svg width="100%" height="150" viewBox="0 0 100 100" preserveAspectRatio="none">
                                <path d="M0 80 L10 70 L20 60 L30 40 L40 50 L50 30 L60 20 L70 10 L80 30 L90 20 L100 40" fill="none" stroke="#2196F3" stroke-width="2.5" class="chart-line"/>
                            </svg>
                        </div>
                        <div class="chart-axis-x">
                            <span>Jan</span><span>Fev</span><span>Mar</span><span>Abr</span><span>Mai</span><span>Jun</span><span>Jul</span><span>Ago</span><span>Set</span><span>Out</span><span>Nov</span><span>Dez</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-table-column">
            <div class="time-members-card glass-card">
                <h3 class="section-title-time">Time</h3>
                
                <table class="time-members-table">
                    <thead>
                        <tr>
                            <th class="col-main">NOME</th>
                            <th>FUNÇÃO</th>
                            <th>STATUS</th>
                            <th>EMPREGADO</th>
                            <th></th> </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-main-data">
                                <p class="member-name">Rogério Berenco</p>
                                <span class="member-email">rogerioberenco@titan.com</span>
                            </td>
                            <td>Função</td>
                            <td><span class="status-badge status-online">Online</span></td>
                            <td>14/06/25</td>
                            <td><button class="btn-edit">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="col-main-data">
                                <p class="member-name">Gabriel Gomes</p>
                                <span class="member-email">gabrielgomes@titan.com</span>
                            </td>
                            <td>Função</td>
                            <td><span class="status-badge status-offline">Offline</span></td>
                            <td>14/06/25</td>
                            <td><button class="btn-edit">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="col-main-data">
                                <p class="member-name">Júlia Tavares</p>
                                <span class="member-email">juliatavares@titan.com</span>
                            </td>
                            <td>Função</td>
                            <td><span class="status-badge status-online">Online</span></td>
                            <td>14/06/25</td>
                            <td><button class="btn-edit">Edit</button></td>
                        </tr>
                         <tr>
                            <td class="col-main-data">
                                <p class="member-name">Otávio Almeida</p>
                                <span class="member-email">otavioalmeida@titan.com</span>
                            </td>
                            <td>Full Stack</td>
                            <td><span class="status-badge status-offline">Offline</span></td>
                            <td>23/09/25</td>
                            <td><button class="btn-edit">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="col-main-data">
                                <p class="member-name">Elvis Rodrigues</p>
                                <span class="member-email">elvisrodrigues@titan.com</span>
                            </td>
                            <td>Função</td>
                            <td><span class="status-badge status-online">Online</span></td>
                            <td>14/06/25</td>
                            <td><button class="btn-edit">Edit</button></td>
                        </tr>
                         </tr>
                        <tr>
                            <td class="col-main-data">
                                <p class="member-name">Elvis Rodrigues</p>
                                <span class="member-email">elvisrodrigues@titan.com</span>
                            </td>
                            <td>Função</td>
                            <td><span class="status-badge status-online">Online</span></td>
                            <td>14/06/25</td>
                            <td><button class="btn-edit">Edit</button></td>
                         </tr>
                        <tr>
                            <td class="col-main-data">
                                <p class="member-name">Elvis Rodrigues</p>
                                <span class="member-email">elvisrodrigues@titan.com</span>
                            </td>
                            <td>Função</td>
                            <td><span class="status-badge status-offline">Online</span></td>
                            <td>14/06/25</td>
                            <td><button class="btn-edit">Edit</button></td>
                         </tr>
                        <tr>
                            <td class="col-main-data">
                                <p class="member-name">Elvis Rodrigues</p>
                                <span class="member-email">elvisrodrigues@titan.com</span>
                            </td>
                            <td>Função</td>
                            <td><span class="status-badge status-online">Online</span></td>
                            <td>14/06/25</td>
                            <td><button class="btn-edit">Edit</button></td>
                         </tr>
                        <tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="bottom-content-grid-time">

        <div class="projects-chart-card glass-card">
            <h3 class="section-title-time">Projetos Ativos</h3>

            <div class="chart-projects-container">
                <div class="chart-axis-y-projects">
                    <span>50</span><span>40</span><span>30</span><span>20</span><span>10</span><span>0</span>
                </div>
                <div class="chart-area-projects">
                    <svg width="100%" height="200" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 80 L10 65 L20 40 L30 50 L40 35 L50 20 L60 30 L70 10 L80 15 L90 5 L100 10" fill="none" stroke="#64B5F6" stroke-width="3" class="chart-line chart-line-active" data-points="10,25,40,30,45,60,50,70,65,75,70"/>
                        <path d="M0 90 L10 75 L20 60 L30 55 L40 45 L50 35 L60 40 L70 30 L80 45 L90 40 L100 50" fill="none" stroke="#2196F3" stroke-width="3" class="chart-line chart-line-paused" data-points="5,15,30,35,45,55,50,60,45,50,40"/>
                        <path d="M0 95 L10 85 L20 80 L30 75 L40 70 L50 65 L60 60 L70 55 L80 50 L90 45 L100 40" fill="none" stroke="#FF5722" stroke-width="3" class="chart-line chart-line-zeroed" data-points="2,5,10,15,20,25,30,35,40,45,50"/>
                    </svg>
                </div>
                <div class="chart-axis-x-projects">
                    <span>Jan</span><span>Fev</span><span>Mar</span><span>Abr</span><span>Mai</span><span>Jun</span><span>Jul</span><span>Ago</span><span>Set</span><span>Out</span><span>Nov</span><span>Dez</span>
                </div>
            </div>

            <div class="chart-legend">
                <span class="legend-item"><i class="legend-dot active"></i> Projetos Ativos</span>
                <span class="legend-item"><i class="legend-dot paused"></i> Projetos Pausados</span>
                <span class="legend-item"><i class="legend-dot zeroed"></i> Projetos Zerados</span>
                <span class="legend-item"><i class="legend-dot all"></i> Total de Projetos</span>
            </div>
            
            <div id="chart-tooltip" class="chart-tooltip-time"></div>
        </div>


        <div class="top-nichos-card glass-card">
            <h3 class="section-title-time">Top Produtos & Nichos</h3>
            
            <table class="top-nichos-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>PROFIT</th>
                        <th>ROI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>Nicho 1</td>
                        <td>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: 95%;" data-profit="R$ 45.000"></div>
                            </div>
                        </td>
                        <td><span class="roi-percent positive">95%</span></td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>Nicho 2</td>
                        <td>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: 80%;" data-profit="R$ 38.000"></div>
                            </div>
                        </td>
                        <td><span class="roi-percent positive">80%</span></td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>Nicho 3</td>
                        <td>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: 65%;" data-profit="R$ 30.000"></div>
                            </div>
                        </td>
                        <td><span class="roi-percent positive">65%</span></td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>Nicho 4</td>
                        <td>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: 50%;" data-profit="R$ 22.000"></div>
                            </div>
                        </td>
                        <td><span class="roi-percent positive">50%</span></td>
                    </tr>
                    <tr>
                        <td>05</td>
                        <td>Nicho 5</td>
                        <td>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: 35%;" data-profit="R$ 15.000"></div>
                            </div>
                        </td>
                        <td><span class="roi-percent negative">35%</span></td>
                    </tr>
                </tbody>
            </table>
            
            <div id="profit-tooltip-nicho" class="profit-tooltip-nicho"></div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bars = document.querySelectorAll('.bar-fill');
            const tooltip = document.getElementById('profit-tooltip-nicho');

            bars.forEach(bar => {
                bar.addEventListener('mouseenter', (e) => {
                    const profitValue = e.target.getAttribute('data-profit');
                    
                    // Atualiza o conteúdo e a posição
                    tooltip.innerHTML = profitValue;
                    tooltip.style.opacity = '1';
                    
                    // Calcula a posição (baseada no mouse)
                    const rect = e.target.getBoundingClientRect();
                    const containerRect = document.querySelector('.top-nichos-card').getBoundingClientRect();
                    
                    // Posição ajustada dentro do card
                    tooltip.style.left = `${e.clientX - containerRect.left}px`;
                    tooltip.style.top = `${e.clientY - containerRect.top - 10}px`; // -10px para flutuar acima
                });

                bar.addEventListener('mousemove', (e) => {
                    // Atualiza a posição constantemente
                    const containerRect = document.querySelector('.top-nichos-card').getBoundingClientRect();
                    tooltip.style.left = `${e.clientX - containerRect.left}px`;
                    tooltip.style.top = `${e.clientY - containerRect.top - 10}px`;
                });

                bar.addEventListener('mouseleave', () => {
                    tooltip.style.opacity = '0';
                });
            });
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chart = document.querySelector('.chart-area-projects');
            const tooltip = document.getElementById('chart-tooltip');
            const paths = chart.querySelectorAll('.chart-line');
            const labelsX = document.querySelectorAll('.chart-axis-x-projects span');
            
            // Simulação de 12 pontos de dados
            const dataPoints = [
                { Jan: [10, 5, 2], Fev: [25, 15, 5], Mar: [40, 30, 10], Abr: [50, 35, 15], Mai: [35, 45, 20], Jun: [20, 55, 25], Jul: [30, 50, 30], Ago: [10, 60, 35], Set: [15, 45, 40], Out: [5, 50, 45], Nov: [10, 40, 50], Dez: [20, 30, 60] }
            ];

            // (O restante do JS para calcular a posição e o valor do tooltip
            // é complexo em SVG puro. Faremos a simulação do mouse-over
            // na área do gráfico e mostraremos um tooltip genérico no CSS/HTML
            // para manter a estrutura e o requisito de "passar o mouse na linha",
            // já que a integração dinâmica de dados está fora do escopo.)
            
            // Apenas para simular a interação de passagem do mouse:
            chart.addEventListener('mousemove', (e) => {
                const rect = chart.getBoundingClientRect();
                const x = e.clientX - rect.left;
                
                // Simulação simples de detecção de mês
                const monthIndex = Math.floor((x / rect.width) * 12);
                
                if (monthIndex >= 0 && monthIndex < 12) {
                    const monthName = labelsX[monthIndex].textContent;
                    // Valores mockados para o tooltip
                    tooltip.innerHTML = `Mês: ${monthName}<br>Ativos: 40<br>Pausados: 30<br>Zerados: 10`;
                    tooltip.style.left = `${x}px`;
                    tooltip.style.top = `${e.clientY - rect.top}px`;
                    tooltip.style.opacity = '1';
                }
            });

            chart.addEventListener('mouseleave', () => {
                tooltip.style.opacity = '0';
            });
        });
    </script>

</x-layout>