<x-colaboradores-component>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/colaboradores-metas.css') }}">
    {{-- biblioteca confetes --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <div class="metas-page-container">

        {{-- conteiner de notificacoes --}}
        <div id="motivation-toast-container" class="motivation-toast-container"></div>

        {{-- CABECALHO E METAS GERAIS --}}
        <div class="hero-header">
            <div class="header-content">
                <span class="welcome-text">O palco onde nascem os novos campeões.</span>
                <h1 class="main-title">
                    AQUI, CADA META BATIDA<br>
                    ESCREVE O NOME DOS<br>
                    <span class="highlight-text">VENCEDORES</span>
                </h1>
                <p class="quote-text">"Somente quem escolhe ser visto alcança o topo — como diz o provérbio: ‘Quem mira
                    alto, chega mais longe.’"</p>
            </div>

    {{-- card e animacao --}}
    <div class="goals-overview-grid">
    @php
        // VALORES MOCKADOS 
        $progressDiariaFb = 115; // 115%
        $progressDiariaYt = 75;  // 75%
        $progressDiariaNt = 99;  // 99%
        $progressSemanalFb = 100; // 100%
        $progressSemanalYt = 60; // 60%
        $progressSemanalNt = 85; // 85%
        $progressQuinzenal = 55; // 55%
    @endphp

    {{-- META DIARIA --}}
    <div class="goal-card animated-border blue-border" id="card-diaria">
        <div class="card-glow"></div>
        <h3 class="goal-title" style="color: #fff;">META DIÁRIA</h3>
        <div class="goal-values">
            {{-- FB --}}
            <div class="val-row">
                <span class="plat fb">FB</span>
                <div class="progress-track">
                    <div class="progress-fill fb @if($progressDiariaFb >= 100) success @endif" 
                         style="width: {{ min(100, $progressDiariaFb) }}%;"
                         data-progress="{{ $progressDiariaFb }}">
                        <span class="progress-percentage">{{ $progressDiariaFb }}%</span>
                    </div>
                </div>
                <span class="money" id="val-diaria-fb">@dollarK($metasDiaria['facebook'])</span>
            </div>
            
            {{-- YT --}}
            <div class="val-row">
                <span class="plat yt">YT</span>
                <div class="progress-track">
                    <div class="progress-fill yt @if($progressDiariaYt >= 100) success @endif" 
                         style="width: {{ min(100, $progressDiariaYt) }}%;"
                         data-progress="{{ $progressDiariaYt }}">
                        <span class="progress-percentage">{{ $progressDiariaYt }}%</span>
                    </div>
                </div>
                <span class="money" id="val-diaria-yt">@dollarK($metasDiaria['google'])</span>
            </div>
            
            {{-- NT --}}
            <div class="val-row">
                <span class="plat nt">NT</span>
                <div class="progress-track">
                    <div class="progress-fill nt @if($progressDiariaNt >= 100) success @endif" 
                         style="width: {{ min(100, $progressDiariaNt) }}%;"
                         data-progress="{{ $progressDiariaNt }}">
                        <span class="progress-percentage">{{ $progressDiariaNt }}%</span>
                    </div>
                </div>
                <span class="money" id="val-diaria-nt">@dollarK($metasDiaria['native'])</span>
            </div>
        </div>
    </div>

    {{-- META SEMANAL --}}
    <div class="goal-card animated-border gold-border" id="card-semanal">
        <div class="card-glow"></div>
        <h3 class="goal-title" style="color: #fff;">META SEMANAL</h3>
        <div class="goal-values">
            {{-- FB --}}
            <div class="val-row">
                <span class="plat fb">FB</span>
                <div class="progress-track">
                    <div class="progress-fill fb @if($progressSemanalFb >= 100) success @endif" 
                         style="width: {{ min(100, $progressSemanalFb) }}%;"
                         data-progress="{{ $progressSemanalFb }}">
                        <span class="progress-percentage">{{ $progressSemanalFb }}%</span>
                    </div>
                </div>
                <span class="money" id="val-semanal-fb">@dollarK($metasSemanal['facebook'])</span>
            </div>
            
            {{-- YT --}}
            <div class="val-row">
                <span class="plat yt">YT</span>
                <div class="progress-track">
                    <div class="progress-fill yt @if($progressSemanalYt >= 100) success @endif" 
                         style="width: {{ min(100, $progressSemanalYt) }}%;"
                         data-progress="{{ $progressSemanalYt }}">
                        <span class="progress-percentage">{{ $progressSemanalYt }}%</span>
                    </div>
                </div>
                <span class="money" id="val-semanal-yt">@dollarK($metasSemanal['google'])</span>
            </div>
            
            {{-- NT --}}
            <div class="val-row">
                <span class="plat nt">NT</span>
                <div class="progress-track">
                    <div class="progress-fill nt @if($progressSemanalNt >= 100) success @endif" 
                         style="width: {{ min(100, $progressSemanalNt) }}%;"
                         data-progress="{{ $progressSemanalNt }}">
                        <span class="progress-percentage">{{ $progressSemanalNt }}%</span>
                    </div>
                </div>
                <span class="money" id="val-semanal-nt">@dollarK($metasSemanal['native'])</span>
            </div>
        </div>
    </div>

    {{-- META QUINZENAL --}}
    <div class="goal-card animated-border purple-border" id="card-quinzenal">
        <div class="card-glow"></div>
        <h3 class="goal-title" style="color: #fff;">META QUINZENAL</h3>
        
        <div class="val-row consolidated-row">
            <span class="goal-sub" style="color: #fff; margin-right: 15px;">Consolidado Geral</span>
            <div class="progress-track">
                <div class="progress-fill consolidated @if($progressQuinzenal >= 100) success @endif" 
                     style="width: {{ min(100, $progressQuinzenal) }}%;"
                     data-progress="{{ $progressQuinzenal }}">
                    <span class="progress-percentage">{{ $progressQuinzenal }}%</span>
                </div>
            </div>
            <div class="goal-total" id="val-quinzenal-total">@dollarM($metaQuinzenal)</div>
        </div>
        
    </div>
</div>

    {{-- A ANIMACAO JA estA no CSS (transition: width 0.6s ease) --}}


        {{-- RANK CONTOLS COM ABA E ANIMACAO --}}
        <div class="rank-controls-bar animated-border-controls">
            <div class="season-tabs">
                <button class="tab-btn active" onclick="switchTab('diaria')" id="btn-diaria">Meta Diária</button>
                <button class="tab-btn" onclick="switchTab('semanal')" id="btn-semanal">Meta Semanal</button>
                <button class="tab-btn" onclick="switchTab('quinzenal')" id="btn-quinzenal">Meta Quinzenal</button>
                <div class="tab-glider"></div>
            </div>

            {{-- <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Buscar vencedores ou squads..." class="search-input">
            </div> --}}
        </div>


            {{-- RANK TITAN --}}
            <div class="podium-section podium-5">

                {{-- 4 lugar --}}
                <div class="podium-pillar place-4">
                    <div class="avatar-floating">
                        <div class="avatar-circle" id="podio-4-avatar"></div>
                        <div class="rank-badge">4</div>
                    </div>
                    <div class="squad-name" id="podio-4-name"></div>
                    <div class="squad-gap">Faltam 48% para o topo</div>
                    <div class="pillar-block" id="podio-4-block">
                        <p class="pillar-profit" id="podio-4-profit"></p>
                    </div>
                </div>

                {{-- 2 lugar --}}
                <div class="podium-pillar place-2">
                    <div class="avatar-floating">
                        <div class="avatar-circle" id="podio-2-avatar"></div>
                        <div class="rank-badge">2</div>
                    </div>
                    <div class="squad-name" id="podio-2-name"></div>
                    <div class="squad-gap">Faltam 15% para o topo</div>
                    <div class="pillar-block" id="podio-2-block">
                        <p class="pillar-profit" id="podio-2-profit"></p>
                    </div>
                </div>

                {{-- 1 lugar --}}
                <div class="podium-pillar place-1">
                    <div class="crown-floating"><i class="fas fa-crown"></i></div>

                    <div class="avatar-floating champion">
                        <div class="avatar-circle" id="podio-1-avatar"></div>
                        <div class="rank-badge gold">1</div>
                    </div>

                    <div class="squad-name highlight" id="podio-1-name"></div>
                    <div class="squad-gap squad-gap-1">
                        <i class="fas fa-fire"></i> META BATIDA!
                    </div>

                    <div class="celebrate-btn-container">
                        <button class="btn-confetti" onclick="fireConfetti()">🎉 CELEBRAR!</button>
                    </div>

                    <div class="pillar-block champion-block" id="podio-1-block">
                        <p class="pillar-profit" id="podio-1-profit"></p>
                    </div>
                </div>

                {{-- 3 lugar --}}
                <div class="podium-pillar place-3">
                    <div class="avatar-floating">
                        <div class="avatar-circle" id="podio-3-avatar"></div>
                        <div class="rank-badge">3</div>
                    </div>
                    <div class="squad-name" id="podio-3-name"></div>
                    <div class="squad-gap">Faltam 32% para o topo</div>
                    <div class="pillar-block" id="podio-3-block">
                        <p class="pillar-profit" id="podio-3-profit"></p>
                    </div>
                </div>

                {{-- 5 lugar --}}
                <div class="podium-pillar place-5">
                    <div class="avatar-floating">
                        <div class="avatar-circle" id="podio-5-avatar"></div>
                        <div class="rank-badge">5</div>
                    </div>
                    <div class="squad-name" id="podio-5-name"></div>
                    <div class="squad-gap">Faltam 60% para o topo</div>
                    <div class="pillar-block" id="podio-5-block">
                        <p class="pillar-profit" id="podio-5-profit"></p>
                    </div>
                </div>

            </div>




        {{-- TABELA DE RANKING DETALHADA --}}
        <div class="ranking-table-container glass-panel">
            <div class="table-header-row">
            <div class="col-pos">Progresso (%)</div>
            <div class="col-squad">Squad</div>
            <div class="col-profit text-right">Profit Atual</div>
            <div class="col-meta text-right">Meta Alvo</div>
            {{-- <div class="col-missing text-right">Profit Faltante</div>  --}}
            <div class="col-status text-center">Status</div>
        </div>


            <div class="table-body-rows" id="rankingTableBody">
                {{-- dados via JS --}}
            </div>

            {{-- BOTAO FLUANTE CONFETE --}}
            <div class="floating-celebration" onclick="fireConfetti()">
                <i class="fas fa-gift"></i>
            </div>
        </div>

    </div>

    {{-- scripts --}}

    <script>
        window.metas = {
            diaria: @json($metasDiaria),
            semanal: @json($metasSemanal),
            quinzenal: {
                facebook: {{ $metaQuinzenal }},
                google: {{ $metaQuinzenal }},
                native: {{ $metaQuinzenal }}
            }
        };
    </script>
    <script>
        window.sources = {
            diaria: @json($metricasDiariaSources),
            semanal: @json($metricasSemanaSources),
            quinzenal: {{ $metricasQuinzSources }}
        };
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            atualizarPodio("diaria");
        });
    </script>

    <script>
        window.metricas = {
            diaria: @json($metricasDiaria),
            semanal: @json($metricasSemana),
            quinzenal: @json($metricasQuinzenal),
        };
    </script>

    {{-- Atualizar podio --}}
    <script>
        function atualizarPodio(periodo) {
            const dados = window.metricas[periodo];
            const metas = window.metas[periodo];

            const format = v => "$ " + v.toLocaleString("en-US");

            function atualizarPosicao(pos, item) {
                console.log(item);
                
                const sku = item.sku;
                const plataforma = item.platform;
                const profit = item.total_profit;
                const meta = metas[plataforma] ?? 0;

                const progresso = meta > 0 ? (profit / meta) * 100 : 0;

                let textoGap = "";
                if (meta === 0) textoGap = "--";
                else if (progresso >= 100) textoGap = "META BATIDA!";
                else textoGap = `Faltam ${(100 - progresso).toFixed(0)}% para a meta`;

                // Avatar
                document.getElementById(`podio-${pos}-avatar`).textContent = sku;
                document.getElementById(`podio-${pos}-avatar`).style.background =
                    `var(--${plataforma}-collor)`;

                // Nome
                document.getElementById(`podio-${pos}-name`).textContent = plataforma;

                // Profit
                document.getElementById(`podio-${pos}-profit`).textContent = format(profit);

                // Cor da coluna
                document.getElementById(`podio-${pos}-block`).style.background =
                    `var(--${plataforma}-collor)`;

                // Gap
                document.querySelector(`.squad-gap-${pos}`).innerHTML = textoGap;

                // altura proporcional ao profit
                const minAlt = 40;
                const maxAlt = 260;

                let p = progresso / 100;
                let curva = Math.pow(p, 0.45); // SUAVE E CONSTANTE

                let altura = minAlt + (maxAlt - minAlt) * curva;

                // salva temporariamente
                item._altura = altura;

                // Garantir ordenação das alturas (1 > 2 > 3)
                let h1 = dados[0]._altura;
                let h2 = dados[1]._altura;
                let h3 = dados[2]._altura;

                // Corrige ordem caso esteja invertida
                if (h2 >= h1) h2 = h1 - 20; // 2º nunca maior que 1º
                if (h3 >= h2) h3 = h2 - 20; // 3º nunca maior que 2º

                // Sempre mantém altura mínima
                h1 = Math.max(h1, minAlt);
                h2 = Math.max(h2, minAlt);
                h3 = Math.max(h3, minAlt);

                // aplica
                document.querySelector(`#podio-1-block`).style.height = `${h1}px`;
                document.querySelector(`#podio-2-block`).style.height = `${h2}px`;
                document.querySelector(`#podio-3-block`).style.height = `${h3}px`;




                // STATUS + FOGO + BOTÃO CELEBRAR
                const statusDiv = document.querySelector(`.place-${pos} .squad-status`);
                const btn = document.querySelector(`.place-${pos} .celebrate-btn-container`);

                if (progresso >= 100) {
                    if (statusDiv) {
                        statusDiv.style.display = "block";
                        statusDiv.classList.add("squad-status");
                        statusDiv.innerHTML = `<i class="fas fa-fire"></i> META BATIDA!`;
                    }

                    if (btn) btn.style.display = "flex";

                } else {
                    if (statusDiv) {
                        statusDiv.style.display = "none";
                        statusDiv.classList.remove("squad-status");
                        statusDiv.innerHTML = "";
                    }

                    if (btn) btn.style.display = "none";
                }
            }

            // Atualiza 1º, 2º, 3º lugar
            atualizarPosicao(1, dados[0]);
            atualizarPosicao(2, dados[1]);
            atualizarPosicao(3, dados[2]);
        }
    </script>

    <script>
      
        // funcoes confetes
        function fireConfetti() {
            var duration = 3 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = {
                startVelocity: 30,
                spread: 360,
                ticks: 60,
                zIndex: 9999
            };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now();
                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }
                var particleCount = 50 * (timeLeft / duration);
                confetti(Object.assign({}, defaults, {
                    particleCount,
                    origin: {
                        x: randomInRange(0.1, 0.3),
                        y: Math.random() - 0.2
                    }
                }));
                confetti(Object.assign({}, defaults, {
                    particleCount,
                    origin: {
                        x: randomInRange(0.7, 0.9),
                        y: Math.random() - 0.2
                    }
                }));
            }, 250);

            showToast("🎉 PARABÉNS! A meta foi celebrada! Continue assim!", "success");
        }

        // sistema de notificacao
        function showToast(message, type = 'info') {
            const container = document.getElementById('motivation-toast-container');
            const toast = document.createElement('div');
            toast.className = `motivation-toast toast-${type}`;
            toast.innerHTML = `<i class="fas fa-trophy"></i> <span>${message}</span>`;

            container.appendChild(toast);

            // animacao de entrada
            setTimeout(() => toast.classList.add('show'), 100);

            // remove apos 5 segundos na tela
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        function formatCurrency(value) {
            return value.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'USD'
            }).replace('US', '').trim();
        }

        function renderTable(periodo) {

            const container = document.getElementById('rankingTableBody');
            container.innerHTML = '';

            const data = window.sources[periodo];

            // ----------------------------------------------
            //   👉 SE O PERÍODO FOR QUINZENAL → CONSOLIDADO
            // ----------------------------------------------
            if (periodo === "quinzenal") {

                // Somar todos os profits
                const totalProfit = data;

                // Meta total única
                const metaTotal = window.metas.quinzenal.facebook; // qualquer um, são iguais

                const progress = metaTotal > 0 ? (totalProfit / metaTotal) * 100 : 0;

                const rankBadge =
                    progress >= 100 ?
                    '<span class="badge-rank conqueror"><i class="fas fa-medal"></i> Conquistado!</span>' :
                    progress >= 50 ?
                    '<span class="badge-rank challenger">A caminho!</span>' :
                    '<span class="badge-rank beginner">Continuar!</span>';

                const rowHTML = `
            <div class="rank-row ${progress >= 100 ? 'highlight-row' : ''}">
                
                <div class="col-pos">
                    <span class="percentage-val ${progress >= 100 ? 'text-success' : ''}">
                        ${progress.toFixed(0)}%
                    </span>
                    <div class="mini-progress-bar">
                        <div class="fill ${progress >= 100 ? 'success' : 'blue'}"
                            style="width: ${Math.min(progress, 100)}%">
                        </div>
                    </div>
                </div>

                <div class="col-squad">
                    <div class="squad-info">
                        <div class="squad-avatar" style="background:#6A5ACD;">QZ</div>
                        <div class="squad-details">
                            <span class="name">Consolidado Quinzenal</span>
                            <span class="msg">Performance total da quinzena</span>
                        </div>
                    </div>
                </div>

                <div class="col-profit text-right ${progress >= 100 ? 'gold-text' : ''}">
                    $ ${totalProfit.toLocaleString('en-US')}
                </div>

                <div class="col-meta text-right text-muted">
                    $ ${metaTotal.toLocaleString('en-US')}
                </div>

                <div class="col-status text-center">
                    ${rankBadge}
                </div>

            </div>
        `;

                container.insertAdjacentHTML('beforeend', rowHTML);
                return; // encerra aqui, não renderiza mais nada
            }

            // -------------------------------------------------
            //   👉 DIÁRIA e SEMANAL continuam como antes
            // -------------------------------------------------
            data.forEach(src => {

                const profit = src.total_profit;
                const meta = window.metas[periodo][src.alias] ?? 0;

                const progress = meta > 0 ? (profit / meta) * 100 : 0;
                const isSuccess = progress >= 100;

                let rankBadge = '';
                if (progress >= 100)
                    rankBadge = '<span class="badge-rank conqueror"><i class="fas fa-medal"></i> Conqueror</span>';
                else if (progress >= 50)
                    rankBadge = '<span class="badge-rank challenger">Challenger</span>';
                else
                    rankBadge = '<span class="badge-rank beginner">Rising</span>';

                const rowHTML = `
            <div class="rank-row ${isSuccess ? 'highlight-row' : ''}">
                <div class="col-pos">
                    <span class="percentage-val ${isSuccess ? 'text-success' : ''}">
                        ${progress.toFixed(0)}%
                    </span>
                    <div class="mini-progress-bar">
                        <div class="fill ${isSuccess ? 'success' : 'blue'}"
                            style="width: ${Math.min(progress, 100)}%">
                        </div>
                    </div>
                </div>

                <div class="col-squad">
                    <div class="squad-info">
                        <div class="squad-avatar" style="background: var(--${src.alias}-collor);">
                            ${src.alias.substring(0, 2).toUpperCase()}
                        </div>
                        <div class="squad-details">
                            <span class="name">${src.source}</span>
                            <span class="msg">${src.alias}</span>
                        </div>
                    </div>
                </div>

                <div class="col-profit text-right ${isSuccess ? 'gold-text' : ''}">
                    $ ${profit.toLocaleString('en-US')}
                </div>

                <div class="col-meta text-right text-muted">
                    ${meta > 0 ? '$ ' + meta.toLocaleString('en-US') : '--'}
                </div>

                <div class="col-status text-center">${rankBadge}</div>
            </div>
        `;

                container.insertAdjacentHTML('beforeend', rowHTML);
            });
        }




        function switchTab(tab) {

            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + tab).classList.add('active');

            const glider = document.querySelector('.tab-glider');
            if (tab === 'diaria') glider.style.transform = 'translateX(0%)';
            if (tab === 'semanal') glider.style.transform = 'translateX(100%)';
            if (tab === 'quinzenal') glider.style.transform = 'translateX(200%)';

            renderTable(tab);
            atualizarPodio(tab);

            // animacao transicao cards
            const cards = document.querySelectorAll('.goal-card');
            cards.forEach(c => {
                c.style.opacity = '0.5';
                setTimeout(() => c.style.opacity = '1', 300);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderTable('diaria'); // inicia com o diario para ir na ordem correta, dia, semana e mes
            setTimeout(() => showToast("Bem-vindo ao Ranks Titan! A corrida começou! 🏁", "info"), 1000);
        });
    </script>

</x-colaboradores-component>
