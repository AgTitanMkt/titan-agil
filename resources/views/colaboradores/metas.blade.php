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
                <span class="highlight-text">VENCEDORES</span></h1>
                <p class="quote-text">"Somente quem escolhe ser visto alcança o topo — como diz o provérbio: ‘Quem mira alto, chega mais longe.’"</p>
            </div>

            {{-- card e animacao --}}
            <div class="goals-overview-grid">
                {{-- meta diaria --}}
                <div class="goal-card animated-border blue-border" id="card-diaria">
                    <div class="card-glow"></div>
                   <h3 class="goal-title" style="color: #fff;">META DIÁRIA</h3>
                    <div class="goal-values">
                        <div class="val-row"><span class="plat fb">FB</span> <span class="money" id="val-diaria-fb">$30K</span></div>
                        <div class="val-row"><span class="plat yt">YT</span> <span class="money" id="val-diaria-yt">$30K</span></div>
                        <div class="val-row"><span class="plat nt">NT</span> <span class="money" id="val-diaria-nt">$15K</span></div>
                    </div>
                </div>

                {{-- meta semanal --}}
                <div class="goal-card animated-border gold-border" id="card-semanal">
                    <div class="card-glow"></div>
                    <h3 class="goal-title" style="color: #fff;">META SEMANAL</h3>
                    <div class="goal-values">
                        <div class="val-row"><span class="plat fb">FB</span> <span class="money" id="val-semanal-fb">$210K</span></div>
                        <div class="val-row"><span class="plat yt">YT</span> <span class="money" id="val-semanal-yt">$210K</span></div>
                        <div class="val-row"><span class="plat nt">NT</span> <span class="money" id="val-semanal-nt">$105K</span></div>
                    </div>
                </div>

                {{-- meta quinzenal --}}
                <div class="goal-card animated-border purple-border" id="card-quinzenal">
                    <div class="card-glow"></div>
                    <h3 class="goal-title" style="color: #fff;">META QUINZENAL</h3>
                    <div class="goal-total" id="val-quinzenal-total">$ 1.05M</div>
                    <p class="goal-sub" style="color: #fff;">Consolidado Geral</p>
                </div>
            </div>
        </div>


        {{-- RANK CONTOLS COM ABA E ANIMACAO --}}
        <div class="rank-controls-bar animated-border-controls">
            <div class="season-tabs">
                <button class="tab-btn active" onclick="switchTab('diaria')" id="btn-diaria">Meta Diária</button>
                <button class="tab-btn" onclick="switchTab('semanal')" id="btn-semanal">Meta Semanal</button>
                <button class="tab-btn" onclick="switchTab('quinzenal')" id="btn-quinzenal">Meta Quinzenal</button>
                <div class="tab-glider"></div> 
            </div>

            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Buscar vencedores ou squads..." class="search-input">
            </div>
        </div>


        {{-- RANK TITAN --}}
        <div class="podium-section">
            
            {{-- 2 lugar --}}
            <div class="podium-pillar place-2">
                <div class="avatar-floating">
                    <div class="avatar-circle" style="background: #1877F2;">FB</div>
                    <div class="rank-badge">2</div>
                </div>
                <div class="squad-name">FB Olimpus</div>
                <div class="squad-gap">Faltam 15% para o topo</div>
                <div class="pillar-block"></div>
            </div>

            {{-- 1 lugar --}}
            <div class="podium-pillar place-1">
                <div class="crown-floating"><i class="fas fa-crown"></i></div>
                <div class="avatar-floating champion">
                    <div class="avatar-circle" style="background: #FF0000;">YT</div>
                    <div class="rank-badge gold">1</div>
                </div>
                <div class="squad-name highlight">YT Shenlong</div>
                <div class="squad-status"><i class="fas fa-fire"></i> META BATIDA!</div>
                
                {{-- BOTAO CELEBRAR CENTRALIZADO AO CONTAINER BLOCO AZUL --}}
                <div class="celebrate-btn-container">
                    <button class="btn-confetti" onclick="fireConfetti()">🎉 CELEBRAR!</button>
                </div>

                <div class="pillar-block champion-block"></div>
            </div>

            {{-- 3 lugar --}}
            <div class="podium-pillar place-3">
                <div class="avatar-floating">
                    <div class="avatar-circle" style="background: #28a745;">NT</div> 
                    <div class="rank-badge">3</div>
                </div>
                <div class="squad-name">Native Eagle</div>
                <div class="squad-gap">Faltam 32% para o topo</div>
                <div class="pillar-block"></div>
            </div>

        </div>


        {{-- TABELA DE RANKING DETALHADA --}}
        <div class="ranking-table-container glass-panel">
            <div class="table-header-row">
                <div class="col-pos">Progresso (%)</div>
                <div class="col-squad">Squad (Vencedor)</div>
                <div class="col-profit text-right">Profit Atual</div>
                <div class="col-meta text-right">Meta Alvo</div>
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
        // dados da minha cabeca/nao reais
        const dadosMetas = {
            'diaria': {
                alvoFB: 30000, alvoYT: 30000, alvoNT: 15000,
                squads: [
                    { name: 'YT Shenlong', avatar: 'YT', color: '#FF0000', profit: 34500, meta: 30000, msg: "Hoje o dia é nosso! 🚀" },
                    { name: 'FB Olimpus', avatar: 'FB', color: '#1877F2', profit: 25500, meta: 30000, msg: "Acelerando o tráfego! ⚡" },
                    { name: 'Native Eagle', avatar: 'NT', color: '#28a745', profit: 10200, meta: 15000, msg: "Olhos na presa! 🦅" }, 
                    { name: 'YT Fênix', avatar: 'YT', color: '#FF4500', profit: 13500, meta: 30000, msg: "Renascer para vencer! 🔥" },
                    { name: 'TK Bomba', avatar: 'TK', color: '#000000', profit: 0, meta: 0, msg: "Aguardando start..." }
                ]
            },
            'semanal': {
                alvoFB: 210000, alvoYT: 210000, alvoNT: 105000,
                squads: [
                    { name: 'YT Shenlong', avatar: 'YT', color: '#FF0000', profit: 241500, meta: 210000, msg: "Semana de glória! 🏆" },
                    { name: 'FB Olimpus', avatar: 'FB', color: '#1877F2', profit: 178500, meta: 210000, msg: "Falta pouco para o topo! 💪" },
                    { name: 'Native Eagle', avatar: 'NT', color: '#28a745', profit: 71400, meta: 105000, msg: "Constância é a chave! 🗝️" },
                    { name: 'YT Fênix', avatar: 'YT', color: '#FF4500', profit: 94500, meta: 210000, msg: "Vamos virar o jogo! 🎲" },
                    { name: 'TK Bomba', avatar: 'TK', color: '#000000', profit: 0, meta: 0, msg: "Preparando terreno..." }
                ]
            },
            'quinzenal': {
                alvoFB: 525000, alvoYT: 525000, alvoNT: 262500, // projecao quinzenal baseada em 2.5x semana 
                squads: [
                    { name: 'YT Shenlong', avatar: 'YT', color: '#FF0000', profit: 600000, meta: 525000, msg: "Domínio total da quinzena! 👑" },
                    { name: 'FB Olimpus', avatar: 'FB', color: '#1877F2', profit: 450000, meta: 525000, msg: "Escalando sem parar! 🧗" },
                    { name: 'Native Eagle', avatar: 'NT', color: '#28a745', profit: 180000, meta: 262500, msg: "Voando baixo e rápido! ✈️" },
                    { name: 'YT Fênix', avatar: 'YT', color: '#FF4500', profit: 200000, meta: 525000, msg: "Aquecendo os motores! 🏎️" },
                    { name: 'TK Bomba', avatar: 'TK', color: '#000000', profit: 0, meta: 0, msg: "Em breve..." }
                ]
            }
        };

        // funcoes confetes
        function fireConfetti() {
            var duration = 3 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999 };

            function randomInRange(min, max) { return Math.random() * (max - min) + min; }

            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now();
                if (timeLeft <= 0) { return clearInterval(interval); }
                var particleCount = 50 * (timeLeft / duration);
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
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
            return value.toLocaleString('pt-BR', { style: 'currency', currency: 'USD' }).replace('US', '').trim(); 
        }

        function renderTable(periodo) {
            const container = document.getElementById('rankingTableBody');
            container.innerHTML = '';
            const data = dadosMetas[periodo].squads;

            data.forEach(squad => {
                const progress = squad.meta > 0 ? (squad.profit / squad.meta) * 100 : 0;
                const isSuccess = progress >= 100;
                const progressColor = isSuccess ? 'success' : 'blue';
                let rankBadge = '';
                
                if (progress >= 100) rankBadge = '<span class="badge-rank conqueror"><i class="fas fa-medal"></i> Conqueror</span>'; // Conquistador da meta
                else if (progress > 50) rankBadge = '<span class="badge-rank challenger">Challenger</span>'; // Desafiante da meta
                else if (squad.meta > 0) rankBadge = '<span class="badge-rank beginner">Rising</span>'; // Ascendente da meta
                else rankBadge = '<span class="badge-rank idle">Aguardando</span>';

                const rowHTML = `
                    <div class="rank-row ${isSuccess ? 'highlight-row' : ''} ${squad.meta === 0 ? 'dimmed-row' : ''}">
                        <div class="col-pos">
                            <span class="percentage-val ${isSuccess ? 'text-success' : ''}">${squad.meta > 0 ? progress.toFixed(0) + '%' : '--'}</span>
                            <div class="mini-progress-bar"><div class="fill ${progressColor}" style="width: ${Math.min(progress, 100)}%"></div></div>
                        </div>
                        <div class="col-squad">
                            <div class="squad-info">
                                <div class="squad-avatar" style="background: ${squad.color};">${squad.avatar}</div>
                                <div class="squad-details">
                                    <span class="name">${squad.name}</span>
                                    <span class="msg">"${squad.msg}"</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-profit text-right ${isSuccess ? 'gold-text' : ''}">$ ${squad.profit.toLocaleString('pt-BR')}</div>
                        <div class="col-meta text-right text-muted">${squad.meta > 0 ? '$ ' + squad.meta.toLocaleString('pt-BR') : '--'}</div>
                        <div class="col-status text-center">${rankBadge}</div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', rowHTML);
                
                // simulacao de notificacao para quem esta quase la (perto da meta)
                if (progress >= 90 && progress < 100) {
                    setTimeout(() => showToast(`🚀 ${squad.name} está a um passo da meta! Força equipe!`, "warning"), 2000);
                }
            });
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + tab).classList.add('active');
            
            const glider = document.querySelector('.tab-glider');
            if(tab === 'diaria') glider.style.transform = 'translateX(0%)';
            if(tab === 'semanal') glider.style.transform = 'translateX(100%)';
            if(tab === 'quinzenal') glider.style.transform = 'translateX(200%)';

            renderTable(tab);
            
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