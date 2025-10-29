<x-layout>
    <h2 class="dashboard-page-title">Perfil</h2>
    <p class="dashboard-page-subtitle">Gerencie suas informações e veja seu status na equipe</p>

    {{-- 1. CONTAINER PRINCIPAL DO HEADER (APENAS BACKGROUND) --}}
    <div class="profile-header-container" style="background-image: url('{{ asset('img/img-admin/profile-header-bg.png') }}');">
        
        {{-- 2. NOVO CONTAINER GLASS CARD SOBRE O BACKGROUND --}}
        <div class="profile-header-card-wrapper glass-card">
            
            <div class="profile-meta-info">
                {{-- AVATAR: ALTERADO DE "placeholder" para "new" para aplicar estilo retangular --}}
                <div class="profile-picture-new" style="background-image: url('{{ asset('img/img-admin/Titan-Avatar.png') }}');"></div>
                <div class="profile-text-group">
                    <span class="profile-subtitle"></span> {{-- Subtítulo acima do nome --}}
                    <h3 class="profile-name">Nicolle Cassiano</h3>
                    <span class="profile-email">nicolle.cassiano@agencia-titan.com</span>
                </div>
            </div>
            
            {{-- BADGES: MOVIDOS PARA DENTRO DO NOVO GLASS-CARD E ALINHADOS À DIREITA --}}
            <div class="profile-badges-aligned">
                <div class="badge-item"><i class="fas fa-user"></i> Perfil</div>
                <div class="badge-item"><i class="fas fa-briefcase"></i> Gestor Junior</div>
                <div class="badge-item"><i class="fab fa-facebook"></i> Facebook</div>
            </div>
        </div>
    </div>
    
    <div class="profile-main-grid">
        
        {{-- Card Bem-vindo --}}
        <div class="welcome-card glass-card">
            <h3 class="section-title">Bem-vindo de volta!</h3>
            <p class="welcome-message">Prazer em ver você, Nicolle Cassiano!</p>
            <div class="welcome-visual" style="background-image: url('{{ asset('img/img-admin/content.png') }}');"></div>
            <a href="#" class="btn-visualizar-perfil"><i class="fas fa-arrow-right"></i> Visualize seu Perfil</a>
        </div>
        
        {{-- Card Informações do Perfil --}}
        <div class="info-card glass-card">
            <h3 class="section-title">Informações do Perfil</h3>
            <div class="info-text-block">
                <p>Se você não consegue decidir, a resposta é não. Se houver dois caminhos, escolha o que te apavora mais.</p>
            </div>
            
            <div class="info-details-list">
                <div class="info-item"><span class="info-label">Nome Completo:</span> Nicolle Cassiano</div>
                <div class="info-item"><span class="info-label">Telefone:</span> (11) 123 124 123</div>
                <div class="info-item"><span class="info-label">Email:</span> @email.com</div>
                <div class="info-item"><span class="info-label">Local:</span> São Paulo</div>
                <div class="info-item"><span class="info-label">Social Media:</span> <i class="fab fa-instagram"></i> niccassiano</div>
            </div>
        </div>
        
        {{-- Card Time Online (Adicionado mais membros) --}}
        <div class="time-online-card glass-card">
            <div class="time-online-header">
                <h3 class="section-title">Time Online</h3>
                <a href="/time" class="btn-ver-mais">VER MAIS</a>
            </div>
            
            <div class="online-members-list">
                <div class="member-online-item">
                    <div class="member-thumb-blue"></div>
                    <div>
                        <p class="member-name-sm">Elvis Rodrigues</p>
                        <span class="member-email-sm">elvisrodrigues@titan.com</span>
                    </div>
                </div>
                 <div class="member-online-item">
                    <div class="member-thumb-blue"></div>
                    <div>
                        <p class="member-name-sm">Fernando Souza</p>
                        <span class="member-email-sm">fernando.souza@titan.com</span>
                    </div>
                </div>
                 <div class="member-online-item">
                    <div class="member-thumb-blue"></div>
                    <div>
                        <p class="member-name-sm">Juliana Silva</p>
                        <span class="member-email-sm">juliana.silva@titan.com</span>
                    </div>
                </div>
                 <div class="member-online-item"> {{-- Membro Extra 1 --}}
                    <div class="member-thumb-blue"></div>
                    <div>
                        <p class="member-name-sm">Marcos Almeida</p>
                        <span class="member-email-sm">marcos.almeida@titan.com</span>
                    </div>
                </div>
                 <div class="member-online-item"> {{-- Membro Extra 2 --}}
                    <div class="member-thumb-blue"></div>
                    <div>
                        <p class="member-name-sm">Carla Pires</p>
                        <span class="member-email-sm">carla.pires@titan.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- LAYOUT INFERIOR: GRÁFICO E PROVÉRBIO LADO A LADO --}}
    <div class="profile-bottom-grid-fixed">
        
        {{-- Card Projetos Ativos --}}
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



        {{-- Card Proverbio --}}
        <div class="proverb-card glass-card">
            <div class="proverb-image-container" style="background-image: url('{{ asset('img/img-admin/Cover.png') }}');">
                <div class="proverb-logo-sm"></div> 
            </div>
            <div class="proverb-text-content">
                <p class="proverb-title">Provérbios 16:3</p>
                <p class="proverb-quote">“Confia ao Senhor as tuas obras, e teus pensamentos serão estabelecidos.”</p>
            </div>
        </div>
    </div>
</x-layout>