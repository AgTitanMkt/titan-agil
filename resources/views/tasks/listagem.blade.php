<x-layout>

    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Titan Ágil - Backlog Tarefas</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        
    </head>

    <body>

        <header class="top-bar">
            <div class="page-title">
                <i class="fas fa-layer-group"></i> Backlog de Tarefas
            </div>

            <div class="view-switcher">
                <button class="view-btn active" onclick="app.switchView('kanban')" id="btn-kanban">
                    <i class="fas fa-columns"></i> Board
                </button>
                <button class="view-btn" onclick="app.switchView('table')" id="btn-table">
                    <i class="fas fa-table"></i> Lista
                </button>
                <button class="view-btn" onclick="app.switchView('calendar')" id="btn-calendar">
                    <i class="fas fa-calendar"></i> Calendário
                </button>
            </div>

            <button class="btn-primary" onclick="window.location.href='/tarefas/cadastro'">
                <i class="fas fa-plus"></i> Nova Task
            </button>
        </header>

        <div class="filters-bar">
            <i class="fas fa-filter" style="color: var(--text-muted); font-size: 12px;"></i>

            <div class="filter-wrapper">
                <select class="filter-select">
                    <option>Status: Todos</option>
                    <option>Draft</option>
                    <option>Pendente</option>
                    <option>Em revisao</option>
                    <option>Aprovada</option>
                    <option>Reprovada</option>
                    <option>Encerrada</option>
                </select>
            </div>

            <div class="filter-wrapper">
                <select class="filter-select">
                    <option>Squad: Todos</option>
                    <option>YT SHENLONG</option>
                    <option>YT FÊNIX</option>
                    <option>FB CHRONOS</option>
                    <option>NT SPARTA</option>
                    <option>NT MIDAS</option>
                </select>
            </div>

            <div class="filter-wrapper">
                <select name="nicho" id="nichoSelect" class="filter-select">
                    <option value="" disabled selected>Selecione o Nicho</option>
                    @foreach ($nichos as $nicho)
                        <option value="{{ $nicho }}">{{ $nicho }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-wrapper">
                <select class="filter-select">
                    <option>Responsável: Todos</option>
                    <option>Rotta</option>
                    <option>Dime</option>
                    <option>Ary</option>
                    <option>Hoberlan</option>
                    <option>Otavio</option>
                    <option>Iury</option>
                    <option>Luigi</option>
                    <option>Rodrigo</option>
                    <option>Renato</option>
                    <option>Elvis</option>
                    <option>Erick</option>
                    <option>Ferraz</option>
                </select>
            </div>

            <div style="width: 1px; height: 20px; background: var(--border-subtle); margin: 0 10px;"></div>

            <label class="checkbox-elegant">
                <input type="checkbox">
                <span class="checkmark"></span>
                <span class="checkbox-label">Minhas tasks</span>
            </label>

            <label class="checkbox-elegant">
                <input type="checkbox">
                <span class="checkmark"></span>
                <span class="checkbox-label">Atribuídas a mim</span>
            </label>
        </div>

        <main class="content-area" id="main-canvas">
        </main>

        <div class="modal-overlay" onclick="app.closeDrawer()"></div>
        <div class="modal-drawer" id="task-drawer">
            <div class="drawer-header">
                <div style="font-size:12px; color:var(--text-muted)">TASK-ID: <span id="modal-id"></span></div>
                <div class="drawer-actions">
                    <button onclick="app.closeDrawer()"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="drawer-content">
                <input type="text" class="drawer-title" id="modal-title" value="Título da Task">

                <div class="prop-row">
                    <div class="prop-label"><i class="fas fa-spinner"></i> Status</div>
                    <div class="prop-value" id="modal-status">Em andamento</div>
                </div>
                <div class="prop-row">
                    <div class="prop-label"><i class="fas fa-user"></i> Responsável</div>
                    <div class="prop-value flex items-center gap-2">
                        <div class="avatar-sm" id="modal-avatar">NC</div>
                        <span id="modal-assignee">Nicolle</span>
                    </div>
                </div>
                <div class="prop-row">
                    <div class="prop-label"><i class="fas fa-exclamation-circle"></i> Prioridade</div>
                    <div class="prop-value" id="modal-priority">Alta</div>
                </div>
                <div class="prop-row">
                    <div class="prop-label"><i class="fas fa-bullhorn"></i> Fonte</div>
                    <div class="prop-value" id="modal-source">Facebook</div>
                </div>
                <div class="prop-row">
                    <div class="prop-label"><i class="fas fa-calendar"></i> Prazo</div>
                    <div class="prop-value" id="modal-date">15 Fev, 2026</div>
                </div>

                <div class="task-meta-block">
                    <h4 style="font-size:14px; margin-bottom:10px; color:var(--text-muted)">Checklist</h4>
                    <div id="modal-checklist">
                    </div>
                </div>

                <div class="task-meta-block">
                    <h4 style="font-size:14px; margin-bottom:10px; color:var(--text-muted)">Descrição & Contexto</h4>
                    <p style="font-size:14px; line-height:1.6; color:var(--text-muted);">
                        Esta demanda foi criada a partir da solicitação do squad FACEBOOK CHRONOS - RENATO. Necessário
                        validar VSL e Copy para novos ganchos. Total de 100 ADS. Segue Anexo:
                    </p>
                    <div
                        style="margin-top:20px; padding:15px; border:1px dashed var(--border-subtle); border-radius:6px; text-align:center; font-size:12px; color:var(--text-muted);">
                        <i class="fas fa-paperclip"></i> Anexos do RedTrack / Script/Briefing (Alta prioridade)
                    </div>
                </div>
            </div>
        </div>

        <script>
            // TESTES MOKADOS COM STATUS, PRIORIDADES E ORIGEM DAS TASKS - APENAS PARA DEMONSTRACAO, DEPOIS VAI VIRAR BACKEND 
            const CONFIG = {
                statuses: {
                    'draft': {
                        label: 'Criada',
                        color: 'gray'
                    },
                    'pending': {
                        label: 'Aguardando',
                        color: 'purple'
                    },
                    'in_progress': {
                        label: 'Em execução',
                        color: 'blue'
                    },
                    'under_review': {
                        label: 'Em revisão',
                        color: 'yellow'
                    },
                    'approved': {
                        label: 'Aprovada',
                        color: 'green'
                    },
                    'rejected': {
                        label: 'Reprovada',
                        color: 'red'
                    },
                    'archived': {
                        label: 'Encerrada',
                        color: 'gray'
                    }
                },
                priorities: {
                    'Alta': 'high',
                    'Média': 'med',
                    'Baixa': 'low'
                },
                sources: {
                    'Facebook': 'fb',
                    'YouTube': 'yt',
                    'Native': 'nt'
                }
            };

            // dados mokados para tasks que seram puxadas do banco
            const db_tasks = [{
                    id: "DBAD458-VR1",
                    title: "Copywriter Emagrecimento - Gancho Novo Weight Loss",
                    status: "in_progress",
                    squad: "Copywriters",
                    niche: "Emagrecimento",
                    assignee: "Julia",
                    assigneeInitials: "JL",
                    priority: "Alta",
                    source: "Facebook",
                    due: "2026-02-15"
                },
                {
                    id: "DBAD458-VR2",
                    title: "Criativos - ED",
                    status: "pending",
                    squad: "Video",
                    niche: "Ed",
                    assignee: "Jorge",
                    assigneeInitials: "JG",
                    priority: "Média",
                    source: "Native",
                    due: "2026-02-18"
                },
                {
                    id: "DBAD458-VR3",
                    title: "Revisão de LP - Tintinuss",
                    status: "under_review",
                    squad: "Dev",
                    niche: "Tintinuss",
                    assignee: "Nicolle",
                    assigneeInitials: "NC",
                    priority: "Alta",
                    source: "YouTube",
                    due: "2026-02-12"
                },
                {
                    id: "DBAD458-VR4",
                    title: "Configuração RedTrack - Remocao de Bug",
                    status: "draft",
                    squad: "Dev",
                    niche: "Bug",
                    assignee: "Otávio",
                    assigneeInitials: "OA",
                    priority: "Baixa",
                    source: "Facebook",
                    due: "2026-02-25"
                },
                {
                    id: "DBAD458-VR5",
                    title: "Teste A/B 100 ADS",
                    status: "approved",
                    squad: "Copywriters",
                    niche: "Ed",
                    assignee: "Thiago",
                    assigneeInitials: "TH",
                    priority: "Média",
                    source: "Native",
                    due: "2026-02-10"
                },
                {
                    id: "DBAD458-VR6",
                    title: "Edição VSL - Gancho Novo Diabetes",
                    status: "in_progress",
                    squad: "Video",
                    niche: "Diabetes",
                    assignee: "Thamiris",
                    assigneeInitials: "TH",
                    priority: "Alta",
                    source: "YouTube",
                    due: "2026-02-16"
                },
            ];

            // TROCAR PARA KANBAN, TABLE OU CALENDAR
            const app = {
                currentView: 'kanban', // PADRAO, VAI INICIAR ASSIM

                init: () => {
                    app.switchView('kanban');
                },

                // VIEW RENDERERS

                renderKanban: () => {
                    const container = document.getElementById('main-canvas');
                    container.innerHTML = `<div class="view-kanban-container fade-in" id="kanban-board"></div>`;
                    const board = document.getElementById('kanban-board');

                    // CONFIGURANDO AS COLUNAS
                    for (const [key, value] of Object.entries(CONFIG.statuses)) {
                        const colTasks = db_tasks.filter(t => t.status === key);

                        const colHTML = `
                        <div class="kanban-column">
                            <div class="kanban-header">
                                <span style="display:flex; align-items:center; gap:6px;">
                                    <div style="width:8px; height:8px; border-radius:50%; background-color: var(--status-${value.color === 'purple' ? 'pending' : value.color === 'blue' ? 'doing' : value.color === 'green' ? 'approved' : value.color === 'red' ? 'rejected' : value.color === 'yellow' ? 'review' : 'draft'})"></div>
                                    ${value.label}
                                </span>
                                <span class="count-badge">${colTasks.length}</span>
                            </div>
                            <div class="kanban-cards-area">
                                ${colTasks.map(task => app.createCardHTML(task)).join('')}
                            </div>
                        </div>
                    `;
                        board.innerHTML += colHTML;
                    }
                },

                renderTable: () => {
                    const container = document.getElementById('main-canvas');

                    let rowsHTML = db_tasks.map(task => `
                    <tr onclick="app.openDrawer('${task.id}')">
                        <td style="color:var(--text-muted); font-size:11px;">${task.id}</td>
                        <td style="font-weight:500;">${task.title}</td>
                        <td>${task.squad}</td>
                        <td>${task.niche}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="avatar-sm">${task.assigneeInitials}</div>
                                ${task.assignee}
                            </div>
                        </td>
                        <td><span class="badge badge-source ${CONFIG.sources[task.source]}">${task.source}</span></td>
                        <td><span class="badge badge-prio ${CONFIG.priorities[task.priority]}">${task.priority}</span></td>
                        <td><span class="badge badge-status">${CONFIG.statuses[task.status].label}</span></td>
                        <td style="color:var(--text-muted)">${task.due}</td>
                    </tr>
                `).join('');

                    container.innerHTML = `
                    <div class="view-table-container fade-in">
                        <table class="titan-table">
                            <thead>
                                <tr>
                                    <th width="80">ID</th>
                                    <th>Título</th>
                                    <th>Squad</th>
                                    <th>Nicho</th>
                                    <th>Responsável</th>
                                    <th>Fonte</th>
                                    <th>Prioridade</th>
                                    <th>Status</th>
                                    <th>Prazo</th>
                                </tr>
                            </thead>
                            <tbody>${rowsHTML}</tbody>
                        </table>
                    </div>
                `;
                },

                renderCalendar: () => {
                    const container = document.getElementById('main-canvas');
                    // DATA
                    let daysHTML = '';
                    for (let i = 1; i <= 28; i++) {
                        // TAKS COM PRAZO PARA O DIA
                        let dayStr = `2026-02-${i.toString().padStart(2, '0')}`;
                        let dayTasks = db_tasks.filter(t => t.due === dayStr);

                        let tasksHTML = dayTasks.map(t =>
                            `<div class="cal-task-pill" onclick="event.stopPropagation(); app.openDrawer('${t.id}')">${t.title}</div>`
                        ).join('');

                        daysHTML += `
                        <div class="cal-day">
                            <span class="cal-date-num">${i}</span>
                            ${tasksHTML}
                        </div>
                    `;
                    }

                    container.innerHTML = `
                    <div class="view-calendar-container fade-in">
                        <div style="margin-bottom:10px; font-weight:600;">Fevereiro 2026</div>
                        <div class="calendar-grid">
                            <div class="cal-header">Dom</div><div class="cal-header">Seg</div><div class="cal-header">Ter</div>
                            <div class="cal-header">Qua</div><div class="cal-header">Qui</div><div class="cal-header">Sex</div>
                            <div class="cal-header">Sab</div>
                            ${daysHTML}
                        </div>
                    </div>
                `;
                },

                // PRIORIDADES E ORIGEM das taks e bags com cores e numeros dos checklists
                createCardHTML: (task) => {
                    const prioClass = CONFIG.priorities[task.priority];
                    const sourceClass = CONFIG.sources[task.source];

                    return `
                    <div class="kanban-card" onclick="app.openDrawer('${task.id}')">
                        <div class="k-card-tags">
                            <span class="badge badge-source ${sourceClass}" style="font-size:10px;">${task.source}</span>
                            <span class="badge badge-prio ${prioClass}" style="font-size:10px;">${task.priority}</span>
                        </div>
                        <div class="k-card-title">${task.title}</div>
                        <div class="k-card-footer">
                            <div class="k-info"><i class="far fa-check-square"></i> 0/5</div>
                            <div class="avatar-sm" title="${task.assignee}">${task.assigneeInitials}</div>
                        </div>
                    </div>
                `;
                },

                switchView: (viewName) => {
                    // BUTTONS DE ACIONAMENTO
                    document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                    document.getElementById(`btn-${viewName}`).classList.add('active');

                    // RENDERIZA A VIEW SELECIONADA
                    if (viewName === 'kanban') app.renderKanban();
                    if (viewName === 'table') app.renderTable();
                    if (viewName === 'calendar') app.renderCalendar();
                },

                // MODAL AQUI

                // MODAL, VAI ABRIR COM OS DETALHES DA TASK - POR ENQUANTO É MOKADO, DEPOIS VAI SER PUXADO DO BACKEND PELO ID
                openDrawer: (taskId) => {
                    const task = db_tasks.find(t => t.id === taskId);
                    if (!task) return;

                    document.getElementById('modal-id').innerText = task.id;
                    document.getElementById('modal-title').value = task.title;
                    document.getElementById('modal-status').innerText = CONFIG.statuses[task.status].label;
                    document.getElementById('modal-assignee').innerText = task.assignee;
                    document.getElementById('modal-avatar').innerText = task.assigneeInitials;
                    document.getElementById('modal-priority').innerText = task.priority;
                    document.getElementById('modal-source').innerText = task.source;
                    document.getElementById('modal-date').innerText = task.due;

                    // CHECKLIST MOKADO
                    document.getElementById('modal-checklist').innerHTML = `
                    <div class="checklist-item"><div class="check-box checked"></div> Validar Copy</div>
                    <div class="checklist-item"><div class="check-box"></div> Aprovar Design</div>
                    <div class="checklist-item"><div class="check-box"></div> Subir Campanha</div>
                    <div class="checklist-item"><div class="check-box"></div> Pedir Alteração</div>
                    <div class="checklist-item"><div class="check-box"></div> Reprovado</div>
                `;

                    document.querySelector('.modal-overlay').classList.add('open');
                    document.getElementById('task-drawer').classList.add('open');
                },

                closeDrawer: () => {
                    document.querySelector('.modal-overlay').classList.remove('open');
                    document.getElementById('task-drawer').classList.remove('open');
                }
            };

            // START PARA TUDO
            document.addEventListener('DOMContentLoaded', app.init);
        </script>
    </body>

    </html>

</x-layout>