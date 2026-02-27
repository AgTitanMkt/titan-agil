    <x-layout>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                    <div class="prop-label"><i class="fas fa-user-edit"></i> Copywriter</div>
                    <div class="prop-value flex items-center gap-2" id="modal-copywriter"></div>
                </div>

                <div class="prop-row">
                    <div class="prop-label"><i class="fas fa-video"></i> Editor</div>
                    <div class="prop-value flex items-center gap-2" id="modal-editor"></div>
                </div>
                <div class="prop-row">
                    <div class="prop-label"><i class="fas fa-user"></i> Gestor</div>
                    <div class="prop-value flex items-center gap-2">
                        <span id="modal-gestor"></span>
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

        <div class="role-modal-overlay" id="role-overlay" onclick="event.stopPropagation(); app.closeRoleModal();">
        </div>

        <div class="role-modal" id="role-modal" onclick="event.stopPropagation()">
            <div class="role-modal-header">
                <h3 id="role-modal-title"></h3>
                <button onclick="app.closeRoleModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="role-modal-body" id="role-user-list"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /*
                |--------------------------------------------------------------------------
                | 1️⃣ DADOS DO BACKEND
                |--------------------------------------------------------------------------
                */

                const RAW_SUBTASKS = @json($subtasks ?? []);
                const COPIES = @json($copies);
                const EDITORS = @json($editors);

                /*
                |--------------------------------------------------------------------------
                | 2️⃣ CONFIG STATUS
                |--------------------------------------------------------------------------
                */

                const STATUS_MAP = {
                    CREATED: 'draft',
                    PENDING: 'pending',
                    REVIEW: 'under_review',
                    APPROVED: 'approved',
                    CONCLUDED: 'archived'
                };

                const STATUS_LABELS = {
                    draft: 'Criada',
                    pending: 'Aguardando',
                    under_review: 'Em revisão',
                    approved: 'Aprovada',
                    archived: 'Encerrada'
                };

                /*
                |--------------------------------------------------------------------------
                | 3️⃣ MAPEAMENTO SUBTASK → CARD
                |--------------------------------------------------------------------------
                */

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        app.closeRoleModal();
                    }
                });

                function mapSubtasks(subtasks) {

                    return subtasks.map(sub => {

                        const task = sub.task ?? {};
                        const agentes = sub.agentes ?? [];

                        const isVariation = !!sub.variation;
                        const variationNumber = sub.variation_number ?? null;

                        let title = task.code ?? 'Task';

                        if (isVariation && variationNumber) {
                            title = `${task.code}V${variationNumber}`;
                        }

                        const mappedAgents = agentes.map(a => ({
                            name: a.name,
                            initials: a.tags?.[0]?.tag || '',
                            roles: a.roles?.map(r => r.title) || []
                        }));

                        return {
                            id: sub.id,
                            code: task.code ?? null,
                            title: title,
                            baseTitle: task.title ?? '',
                            status: STATUS_MAP[sub.status] ?? 'draft',
                            rawStatus: sub.status,
                            niche: task.nicho ?? '-',
                            due: sub.due_date ?? null,
                            description: sub.description ?? '',
                            variation: sub.variation,
                            variation_number: sub.variation_number,
                            agents: mappedAgents,
                            platform: sub.platform ? {
                                id: sub.platform.id,
                                name: sub.platform.name
                            } : null,
                            assignments: sub.assignments ?? [],
                            revised_by: sub.revised_by ? {
                                id: sub.revised_by.id,
                                name: sub.revised_by.name
                            } : null
                        };
                    });
                }

                /*
                |--------------------------------------------------------------------------
                | 4️⃣ ESTADO
                |--------------------------------------------------------------------------
                */

                let DB = mapSubtasks(RAW_SUBTASKS);

                /*
                |--------------------------------------------------------------------------
                | 5️⃣ APP
                |--------------------------------------------------------------------------
                */

                const app = {

                    init() {
                        this.renderKanban();
                    },

                    renderKanban() {

                        const container = document.getElementById('main-canvas');
                        container.innerHTML = `<div class="view-kanban-container" id="kanban-board"></div>`;

                        const board = document.getElementById('kanban-board');

                        const columns = ['draft', 'pending', 'under_review', 'approved', 'archived'];

                        columns.forEach(statusKey => {

                            const cards = DB.filter(item => item.status === statusKey);

                            const colHTML = `
                        <div class="kanban-column">
                            <div class="kanban-header">
                                <span>${STATUS_LABELS[statusKey]}</span>
                                <span class="count-badge">${cards.length}</span>
                            </div>
                            <div class="kanban-cards-area">
                                ${cards.map(card => this.createCard(card)).join('')}
                            </div>
                        </div>
                    `;

                            board.innerHTML += colHTML;
                        });
                    },

                    createCard(card) {

                        const maxVisible = 3;
                        const visibleAgents = card.agents.slice(0, maxVisible);
                        const extraCount = card.agents.length - maxVisible;

                        const avatarsHTML = visibleAgents.map(agent => `
            <div class="avatar-sm" title="${agent.name}">
                ${agent.initials}
            </div>
        `).join('');

                        const extraHTML = extraCount > 0 ?
                            `<div class="avatar-sm more-agents">+${extraCount}</div>` :
                            '';

                        return `
            <div class="kanban-card" onclick="app.openDrawer(${card.id})">
                <div class="k-card-title">${card.title}</div>
                <div class="k-card-footer">
                    <div class="k-info">
                        <i class="fas fa-circle"></i> ${card.rawStatus}
                    </div>
                    <div class="agents-group">
                        ${avatarsHTML}
                        ${extraHTML}
                    </div>
                </div>
            </div>
        `;
                    },

                    openDrawer(id) {

                        // 🚫 Se o role modal estiver aberto, não faz nada
                        if (document.getElementById('role-modal').classList.contains('open')) {
                            return;
                        }

                        const card = DB.find(item => item.id === id);
                        if (!card) return;

                        document.getElementById('modal-id').innerText = card.code ?? card.id;
                        document.getElementById('modal-title').value = card.title;
                        document.getElementById('modal-status').innerText = STATUS_LABELS[card.status];
                        document.getElementById('modal-source').innerText = card.platform?.name ?? '-';

                        const copyAssignment = card.assignments.find(a =>
                            a.user.roles.some(r => r.title === 'COPYWRITER')
                        );

                        const copyContainer = document.getElementById('modal-copywriter');

                        if (copyAssignment) {
                            copyContainer.innerHTML = `<span>${copyAssignment.user.name}</span>`;
                        } else {
                            copyContainer.innerHTML = `
            <button class="btn-add-role" onclick="app.addRole(${card.id}, 'COPYWRITER')">
                <i class="fas fa-plus"></i> Adicionar Copywriter
            </button>
        `;
                        }

                        const editorAssignment = card.assignments.find(a =>
                            a.user.roles.some(r => r.title === 'EDITOR')
                        );

                        const editorContainer = document.getElementById('modal-editor');

                        if (editorAssignment) {
                            editorContainer.innerHTML = `<span>${editorAssignment.user.name}</span>`;
                        } else {
                            editorContainer.innerHTML = `
            <button class="btn-add-role" onclick="app.addRole(${card.id}, 'EDITOR')">
                <i class="fas fa-plus"></i> Adicionar Editor
            </button>
        `;
                        }

                        document.getElementById('modal-gestor').innerText = card.revised_by?.name ?? '—';
                        document.getElementById('modal-date').innerText = card.due ?? '-';

                        const checklist = resolveChecklist(card);

                        document.getElementById('modal-checklist').innerHTML =
                            checklist.map(item => `
            <div class="checklist-item">
                <div class="check-box ${item.done ? 'checked' : ''}"></div>
                ${item.label}
            </div>
        `).join('');

                        document.querySelector('.modal-overlay').classList.add('open');
                        document.getElementById('task-drawer').classList.add('open');
                    },

                    closeDrawer() {
                        document.querySelector('.modal-overlay').classList.remove('open');
                        document.getElementById('task-drawer').classList.remove('open');
                    },

                    addRole(taskId, role) {

                        document.body.style.pointerEvents = 'none';
                        document.getElementById('role-modal').style.pointerEvents = 'auto';
                        document.getElementById('role-overlay').style.pointerEvents = 'auto';

                        this.currentTaskId = taskId;
                        this.currentRole = role;

                        const users = role === 'COPYWRITER' ? COPIES : EDITORS;

                        document.getElementById('role-modal-title').innerText =
                            role === 'COPYWRITER' ?
                            'Selecionar Copywriter' :
                            'Selecionar Editor';

                        const container = document.getElementById('role-user-list');

                        container.innerHTML = users.map(user => `
            <div class="role-user-item"
                onclick="app.selectUser(${user.id}, '${user.name.replace(/'/g, "\\'")}')">
                ${user.name}
            </div>
        `).join('');

                        document.getElementById('role-overlay').classList.add('open');
                        document.getElementById('role-modal').classList.add('open');
                    },

                    selectUser(userId, userName) {

                        const routeName =
                            this.currentRole === 'COPYWRITER' ?
                            "{{ route('ajax.add.copywriter') }}" :
                            "{{ route('ajax.add.editor') }}";

                        fetch(routeName, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    sub_task_id: this.currentTaskId,
                                    user_id: userId
                                })
                            })
                            .then(res => res.json())
                            .then(() => {
                                window.location.reload();
                            });
                    },
                    closeRoleModal() {
                        document.getElementById('role-overlay').classList.remove('open');
                        document.getElementById('role-modal').classList.remove('open');
                        document.body.style.pointerEvents = 'auto';

                        // 🔥 garante que o clique não reabra drawer
                        setTimeout(() => {
                            this.currentTaskId = null;
                        }, 50);
                    },
                    refreshDrawer(id) {
                        const card = DB.find(item => item.id === id);
                        if (!card) return;

                        // Atualiza apenas copywriter
                        const copyAssignment = card.assignments.find(a =>
                            a.user.roles.some(r => r.title === 'COPYWRITER')
                        );

                        const copyContainer = document.getElementById('modal-copywriter');

                        if (copyAssignment) {
                            copyContainer.innerHTML = `<span>${copyAssignment.user.name}</span>`;
                        }

                        // Atualiza editor
                        const editorAssignment = card.assignments.find(a =>
                            a.user.roles.some(r => r.title === 'EDITOR')
                        );

                        const editorContainer = document.getElementById('modal-editor');

                        if (editorAssignment) {
                            editorContainer.innerHTML = `<span>${editorAssignment.user.name}</span>`;
                        }
                    }
                };

                window.app = app;

                app.init();

            });

            function resolveChecklist(card) {

                const assignments = card.assignments ?? [];

                // COPYWRITER
                const copyAssignment = assignments.find(a =>
                    a.user.roles.some(r => r.title === 'COPYWRITER')
                );

                const hasCopywriter = !!copyAssignment;
                const copyInProgress = copyAssignment?.status === 'IN_PROGRESS';
                const copyDone = copyAssignment?.status === 'DONE';

                // EDITOR
                const editorAssignment = assignments.find(a =>
                    a.user.roles.some(r => r.title === 'EDITOR')
                );

                const hasEditor = !!editorAssignment;
                const editorInProgress = editorAssignment?.status === 'IN_PROGRESS';
                const editorDone = editorAssignment?.status === 'DONE';

                // REVISÃO
                const isReviewed = card.status === 'REVIEW' ||
                    card.status === 'APPROVED' ||
                    card.status === 'archived';

                // PUBLICAÇÃO
                const isPublished = card.status === 'archived';

                return [{
                        label: 'Atribuição copywriter',
                        done: hasCopywriter
                    },
                    {
                        label: 'Atribuição editor',
                        done: hasEditor
                    },
                    {
                        label: 'Produção copywriter',
                        done: copyDone
                    },
                    {
                        label: 'Produção editor',
                        done: editorDone
                    },
                    {
                        label: 'Revisão',
                        done: isReviewed
                    },
                    {
                        label: 'Publicação',
                        done: isPublished
                    },
                ];
            }
        </script>




    </x-layout>
