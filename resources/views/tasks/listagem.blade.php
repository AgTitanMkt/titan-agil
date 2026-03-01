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
                <div class="header-info">
                    <span class="task-id-badge">
                        <i class="fas fa-hashtag"></i>
                        <span id="modal-id"></span>
                    </span>

                    <span class="status-dot-label" id="modal-status"></span>
                </div>

                <div class="drawer-actions">
                    <button class="btn-icon" onclick="app.closeDrawer()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="drawer-content">

                <input type="text" class="drawer-title-input" id="modal-title" placeholder="Título da Task">

                <div class="properties-grid">

                    <div class="prop-item">
                        <div class="prop-label"><i class="far fa-user-circle"></i> Copywriter</div>
                        <div class="prop-value" id="modal-copywriter"></div>
                    </div>

                    <div class="prop-item">
                        <div class="prop-label"><i class="fas fa-magic"></i> Editor</div>
                        <div class="prop-value" id="modal-editor"></div>
                    </div>

                    <div class="prop-item">
                        <div class="prop-label"><i class="far fa-id-badge"></i> Gestor</div>
                        <div class="prop-value"><span id="modal-gestor"></span></div>
                    </div>

                    <div class="prop-item">
                        <div class="prop-label"><i class="far fa-calendar-alt"></i> Prazo</div>
                        <div class="prop-value" id="modal-date"></div>
                    </div>

                    <div class="prop-item">
                        <div class="prop-label"><i class="fas fa-bullseye"></i> Fonte</div>
                        <div class="prop-value" id="modal-source"></div>
                    </div>

                </div>

                <hr class="drawer-divider">

                <div class="section-container">
                    <h4 class="section-title">Descrição</h4>
                    <p id="modal-description" class="description-text"></p>
                </div>

                <!-- 🔥 BLOCO DINÂMICO AQUI -->
                <div id="dynamic-blocks"></div>

                <div class="section-container">
                    <h4 class="section-title">Checklist de Progresso</h4>
                    <div id="modal-checklist" class="elegant-checklist"></div>
                </div>

                <div class="section-container">
                    <h4 class="section-title">Anexos e Entregas</h4>
                    <div id="modal-attachments"></div>
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
                const AUTH_USER = {
                    id: {{ auth()->id() }},
                    roles: @json(auth()->user()->roles->pluck('title'))
                };

                /*
                |--------------------------------------------------------------------------
                | 2️⃣ CONFIG STATUS
                |--------------------------------------------------------------------------
                */

                const STATUS_MAP = {
                    CREATED: 'draft',

                    PENDING: 'pending',
                    PENDING_COPY: 'pending',
                    PENDING_EDITOR: 'pending',

                    REVIEW: 'under_review',
                    REVIEW_COPY: 'under_review',
                    REVIEW_EDITOR: 'under_review',

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

                function isCopywriter() {
                    return AUTH_USER.roles.includes('COPYWRITER');
                }

                function isEditor() {
                    return AUTH_USER.roles.includes('EDITOR');
                }



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

                        const taskFiles = sub.files ?? [];

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
                            } : null,
                            taskFiles: taskFiles.map(f => ({
                                id: f.id,
                                type: f.file_type,
                                url: f.file_url,
                                uploaded_by: f.uploader ? {
                                    id: f.uploader.id,
                                    name: f.uploader.name
                                } : null,
                                created_at: f.created_at
                            }))
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

                    toggleRejectMessage() {
                        const area = document.getElementById('reject-area');
                        area.style.display = area.style.display === 'none' ? 'block' : 'none';
                    },
                    showToast(message, type = 'success') {

                        const toast = document.createElement('div');

                        toast.innerText = message;

                        toast.style.position = 'fixed';
                        toast.style.bottom = '30px';
                        toast.style.right = '30px';
                        toast.style.padding = '14px 20px';
                        toast.style.borderRadius = '8px';
                        toast.style.color = '#fff';
                        toast.style.fontSize = '14px';
                        toast.style.zIndex = '9999';
                        toast.style.opacity = '0';
                        toast.style.transition = 'all 0.3s ease';

                        toast.style.background =
                            type === 'success' ?
                            'linear-gradient(90deg,#1f8f4a,#2ecc71)' :
                            'linear-gradient(90deg,#c0392b,#e74c3c)';

                        document.body.appendChild(toast);

                        setTimeout(() => toast.style.opacity = '1', 10);

                        setTimeout(() => {
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 300);
                        }, 3000);
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

                        // 🔥 Detecta reprovação
                        const rejectedAssignment = (card.assignments || []).find(a => a.status === 'REJECTED');

                        let rejectedBadge = '';

                        if (rejectedAssignment) {

                            const isCopyRejected = rejectedAssignment.user?.roles?.some(r => r.title ===
                                'COPYWRITER');
                            const isEditorRejected = rejectedAssignment.user?.roles?.some(r => r.title ===
                                'EDITOR');

                            if (isCopyRejected) {
                                rejectedBadge = `
                <div class="rejected-badge copy">
                    COPY Reprovada
                </div>
            `;
                            }

                            if (isEditorRejected) {
                                rejectedBadge = `
                <div class="rejected-badge editor">
                    VSL Reprovada
                </div>
            `;
                            }
                        }

                        return `
                            <div class="kanban-card" onclick="app.openDrawer(${card.id})">
                                ${rejectedBadge}

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

                        // ✅ reset básico do modal
                        document.getElementById('modal-id').innerText = card.code ?? card.id;
                        document.getElementById('modal-title').value = card.title;
                        document.getElementById('modal-status').innerText = STATUS_LABELS[card.status];
                        document.getElementById('modal-source').innerText = card.platform?.name ?? '-';

                        // descrição
                        document.getElementById('modal-description').innerText = card.description || '—';

                        // ✅ remove blocos dinâmicos antigos (evita duplicar)
                        const oldCopyDelivery = document.getElementById('copy-delivery-block');
                        if (oldCopyDelivery) oldCopyDelivery.remove();

                        const oldManagerReview = document.getElementById('manager-review-block');
                        if (oldManagerReview) oldManagerReview.remove();

                        // anexos
                        const attachmentsEl = document.getElementById('modal-attachments');

                        if (!card.taskFiles || card.taskFiles.length === 0) {
                            attachmentsEl.innerHTML = '';
                        } else {
                            attachmentsEl.innerHTML = `
      <div style="margin-top:20px; display:flex; flex-direction:column; gap:10px;">
        ${card.taskFiles.map(f => `
                                                        <div style="padding:12px; border:1px solid var(--border-subtle); border-radius:8px;">
                                                        <div style="display:flex; justify-content:space-between; gap:10px;">
                                                            <div style="font-size:12px; color:var(--text-muted);">
                                                            ${(f.type || '').toUpperCase() || 'ARQUIVO'}
                                                            </div>
                                                            <div style="font-size:12px; color:var(--text-muted);">
                                                            ${f.created_at ? new Date(f.created_at).toLocaleString() : ''}
                                                            </div>
                                                        </div>

                                                        <div style="margin-top:6px; font-size:14px;">
                                                            <a href="${f.url}" target="_blank" style="color:#5aa7ff; text-decoration:none;">
                                                            ${f.url}
                                                            </a>
                                                        </div>

                                                        <div style="margin-top:6px; font-size:12px; color:var(--text-muted);">
                                                            Enviado por: <b>${f.uploaded_by?.name ?? '—'}</b>
                                                        </div>
                                                        </div>
                                                    `).join('')}
      </div>
    `;
                        }

                        // ✅ assignments
                        const copyAssignment = card.assignments.find(a =>
                            a.user?.roles?.some(r => r.title === 'COPYWRITER')
                        );

                        const editorAssignment = card.assignments.find(a =>
                            a.user?.roles?.some(r => r.title === 'EDITOR')
                        );

                        // Copywriter field
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

                        // Editor field
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

                        // ✅ referência do bloco do checklist para inserir blocos acima
                        const dynamicContainer = document.getElementById('dynamic-blocks');
                        dynamicContainer.innerHTML = '';

                        // ------------------------------------------------------------
                        // BLOCO DE FEEDBACK (quando REJECTED)
                        // ------------------------------------------------------------

                        const rejectedAssignment = (card.assignments || [])
                            .filter(a => a.status === 'REJECTED')
                            .sort((a, b) => new Date(b.updated_at || 0) - new Date(a.updated_at || 0))[0];

                        if (rejectedAssignment && rejectedAssignment.message) {

                            const feedbackBlock = document.createElement('div');
                            feedbackBlock.className = 'review-feedback-card';

                            feedbackBlock.innerHTML = `
                                <div class="feedback-header">
                                    <span>FEEDBACK DE REVISÃO</span>
                                    <i class="fas fa-times-circle"></i>
                                </div>

                                <div class="feedback-body">
                                    <strong>REPROVADA</strong> - ${rejectedAssignment.message}
                                </div>

                                <div class="feedback-meta">
                                    Enviado por: <b>${card.revised_by?.name ?? '—'}</b>
                                </div>
                            `;

                            dynamicContainer.appendChild(feedbackBlock);
                        }

                        // ------------------------------------------------------------
                        // BLOCO EDITOR - "Sua Entrega"
                        // ------------------------------------------------------------

                        const isMyEditorTask = editorAssignment &&
                            editorAssignment.user.id === AUTH_USER.id &&
                            (
                                editorAssignment.status === 'ASSIGNED' ||
                                editorAssignment.status === 'REJECTED'
                            );

                        if (isEditor() && isMyEditorTask && card.rawStatus === 'PENDING_EDITOR') {

                            const editorDeliveryBlock = document.createElement('div');
                            editorDeliveryBlock.id = 'editor-delivery-block';

                            editorDeliveryBlock.innerHTML = `
                                <div class="task-meta-block">
                                    <h4 style="font-size:14px; margin-bottom:10px; color:var(--text-muted)">
                                        Sua Entrega (VSL)
                                    </h4>

                                    <div style="display:flex; gap:10px;">
                                        <input 
                                            type="text" 
                                            id="editor-delivery-link" 
                                            placeholder="Cole o link do vídeo (Drive, Vimeo, etc)"
                                            style="flex:1; padding:10px; border-radius:6px;"
                                        >
                                        <button 
                                            class="btn-primary"
                                            onclick="app.confirmEditorDelivery(${card.id}, ${editorAssignment.id})"
                                        >
                                            Confirmar Entrega
                                        </button>
                                    </div>

                                    <small style="display:block; margin-top:10px; color:var(--text-muted);">
                                        Ao confirmar, irá para revisão do gestor.
                                    </small>
                                </div>
                            `;

                            dynamicContainer.appendChild(editorDeliveryBlock);
                        }

                        // ------------------------------------------------------------
                        // 1) BLOCO COPY - "Sua Entrega" (quando ASSIGNED e é o dono)
                        // ------------------------------------------------------------
                        const isMyCopyTask = copyAssignment &&
                            copyAssignment.user.id === AUTH_USER.id &&
                            (
                                copyAssignment.status === 'ASSIGNED' ||
                                copyAssignment.status === 'REJECTED'
                            );

                        if (isCopywriter() && isMyCopyTask) {

                            const deliveryContainer = document.createElement('div');
                            deliveryContainer.id = 'copy-delivery-block';

                            deliveryContainer.innerHTML = `
                            <div class="task-meta-block">
                                <h4 style="font-size:14px; margin-bottom:10px; color:var(--text-muted)">
                                Sua Entrega
                                </h4>

                                <div style="display:flex; gap:10px;">
                                <input 
                                    type="text" 
                                    id="copy-delivery-link" 
                                    placeholder="Cole o link da sua copy (ex: Google Docs)"
                                    style="flex:1; padding:10px; border-radius:6px;"
                                >
                                <button 
                                    class="btn-primary"
                                    onclick="app.confirmDelivery(${card.id}, ${copyAssignment.id})"
                                >
                                    Confirmar Entrega
                                </button>
                                </div>

                                <small style="display:block; margin-top:10px; color:var(--text-muted);">
                                Ao confirmar, o item "Produção copywriter" será marcado.
                                </small>
                            </div>
                            `;

                            dynamicContainer.appendChild(deliveryContainer);
                        }

                        // ------------------------------------------------------------
                        // 2) BLOCO GESTOR - Revisão do Copy (quando REVIEW_COPY)
                        // ------------------------------------------------------------

                        // helper: pega o último link URL entregue

                        const getLatestUrlFile = (card) => {
                            const urls = (card.taskFiles || []).filter(f => (f.type || '').toLowerCase() ===
                                'url');
                            if (!urls.length) return null;
                            urls.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
                            return urls[0];
                        };


                        const isManagerOfThis = (card.revised_by?.id && (card.revised_by?.id === AUTH_USER.id ||
                            AUTH_USER.roles.includes('ADMIN')));


                        const isReviewCopy = card.rawStatus === 'REVIEW_COPY';
                        const latestCopyLink = getLatestUrlFile(card);

                        if (isManagerOfThis && isReviewCopy && latestCopyLink) {

                            const managerBlock = document.createElement('div');
                            managerBlock.id = 'manager-review-block';

                            managerBlock.innerHTML = `
                                <div class="task-meta-block review-block">

                                <div class="review-header">
                                    <div>
                                        <h4>Revisão da Entrega</h4>
                                        <span class="review-sub">Avalie a copy enviada</span>
                                    </div>
                                    <div class="review-status-badge">
                                        Em análise
                                    </div>
                                </div>

                                <div class="review-link-box">
                                    <a href="${latestCopyLink.url}" target="_blank">
                                        <i class="fas fa-link"></i>
                                        <span>${latestCopyLink.url}</span>
                                    </a>
                                </div>

                                <div class="review-meta">
                                    <span>Enviado por <b>${latestCopyLink.uploaded_by?.name ?? '—'}</b></span>
                                    <span>${latestCopyLink.created_at ? new Date(latestCopyLink.created_at).toLocaleString() : '—'}</span>
                                </div>

                                <div class="review-actions">
                                    <button class="btn-approve"
                                        onclick="app.reviewCopyDelivery(${card.id}, 'approve')">
                                        <i class="fas fa-check-circle"></i>
                                        Aprovar
                                    </button>

                                    <button class="btn-reject"
                                        onclick="app.toggleRejectMessage()">
                                        <i class="fas fa-times-circle"></i>
                                        Reprovar
                                    </button>
                                </div>

                                <div class="review-message-area" id="reject-area" style="display:none;">
                                    <textarea 
                                        id="copy-delivery-message"
                                        placeholder="Explique o que precisa ser ajustado..."
                                    ></textarea>

                                    <button class="btn-send-reject"
                                        onclick="app.reviewCopyDelivery(${card.id}, 'reject')">
                                        Enviar Reprovação
                                    </button>
                                </div>

                                </div>
                                `;

                            // coloca acima do checklist (e acima do bloco do copy também, se existir)
                            dynamicContainer.appendChild(managerBlock);
                        }


                        // ------------------------------------------------------------
                        // BLOCO GESTOR - Revisão da VSL
                        // ------------------------------------------------------------

                        const isReviewEditor = card.rawStatus === 'REVIEW_EDITOR';
                        const latestEditorLink = getLatestUrlFile(card);

                        if (isManagerOfThis && isReviewEditor && latestEditorLink) {

                            const reviewEditorBlock = document.createElement('div');
                            reviewEditorBlock.id = 'manager-review-editor-block';

                            reviewEditorBlock.innerHTML = `
                                <div class="task-meta-block review-block">

                                    <div class="review-header">
                                        <div>
                                            <h4>Revisão da VSL</h4>
                                            <span class="review-sub">Avalie o vídeo enviado</span>
                                        </div>
                                        <div class="review-status-badge">
                                            Em análise
                                        </div>
                                    </div>

                                    <div class="review-link-box">
                                        <a href="${latestEditorLink.url}" target="_blank">
                                            <i class="fas fa-video"></i>
                                            <span>${latestEditorLink.url}</span>
                                        </a>
                                    </div>

                                    <div class="review-meta">
                                        <span>Enviado por <b>${latestEditorLink.uploaded_by?.name ?? '—'}</b></span>
                                        <span>${latestEditorLink.created_at ? new Date(latestEditorLink.created_at).toLocaleString() : '—'}</span>
                                    </div>

                                    <div class="review-actions">
                                        <button class="btn-approve"
                                            onclick="app.reviewEditorDelivery(${card.id}, 'approve')">
                                            <i class="fas fa-check-circle"></i>
                                            Aprovar
                                        </button>

                                        <button class="btn-reject"
                                            onclick="app.toggleRejectMessage()">
                                            <i class="fas fa-times-circle"></i>
                                            Reprovar
                                        </button>
                                    </div>

                                    <div class="review-message-area" id="reject-area" style="display:none;">
                                        <textarea 
                                            id="editor-delivery-message"
                                            placeholder="Explique o que precisa ser ajustado..."
                                        ></textarea>
                                        <br>
                                        <button class="btn-send-reject"
                                            onclick="app.reviewEditorDelivery(${card.id}, 'reject')">
                                            Enviar Reprovação
                                        </button>
                                    </div>

                                </div>
                            `;

                            dynamicContainer.appendChild(reviewEditorBlock);
                        }

                        // checklist
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
                    },
                    confirmEditorDelivery(taskId, assignmentId) {

                        const link = document.getElementById('editor-delivery-link').value;

                        if (!link) {
                            app.showToast('Informe o link da entrega.', 'error');
                            return;
                        }

                        fetch("{{ route('ajax.confirm.editor.delivery') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    assignment_id: assignmentId,
                                    delivery_link: link,
                                })
                            })
                            .then(res => res.json())
                            .then((data) => {

                                if (!data.success) {
                                    app.showToast(data.message, 'error');
                                    return;
                                }

                                app.showToast(data.message, 'success');

                                setTimeout(() => {
                                    window.location.reload();
                                }, 1200);
                            });
                    },
                    confirmDelivery(taskId, assignmentId) {

                        const link = document.getElementById('copy-delivery-link').value;

                        if (!link) {
                            alert('Informe o link da entrega.');
                            return;
                        }

                        fetch("{{ route('ajax.confirm.copy.delivery') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    assignment_id: assignmentId,
                                    delivery_link: link,
                                })
                            })
                            .then(async (res) => {
                                const data = await res.json();

                                if (!data.success) {
                                    app.showToast(data.message, 'error');
                                    return;
                                }

                                app.showToast(data.message, 'success');

                                setTimeout(() => {
                                    window.location.reload();
                                }, 1200);
                            });
                    },
                    reviewEditorDelivery(subtaskId, decision) {

                        const message = document.getElementById('editor-delivery-message')?.value || null;

                        if (decision === 'reject' && !message) {
                            app.showToast('Explique o motivo da reprovação.', 'error');
                            return;
                        }

                        fetch("{{ route('ajax.review.editor.delivery') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    subtask_id: subtaskId,
                                    decision: decision,
                                    message: message
                                })
                            })
                            .then(res => res.json())
                            .then((data) => {

                                if (!data.success) {
                                    app.showToast(data.message || 'Erro ao processar.', 'error');
                                    return;
                                }

                                app.showToast(data.message, 'success');

                                setTimeout(() => {
                                    window.location.reload();
                                }, 1200);
                            });
                    },
                    reviewCopyDelivery(subtaskId, decision) {

                        const message = document.getElementById('copy-delivery-message')?.value || null;

                        if (decision === 'reject' && !message) {
                            app.showToast('Explique o motivo da reprovação.', 'error');
                            return;
                        }

                        fetch("{{ route('ajax.review.copy.delivery') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    subtask_id: subtaskId,
                                    decision: decision,
                                    message: message
                                })
                            })
                            .then(res => res.json())
                            .then((data) => {

                                if (!data.success) {
                                    app.showToast(data.message || 'Erro ao processar.', 'error');
                                    return;
                                }

                                app.showToast(data.message, 'success');

                                setTimeout(() => {
                                    window.location.reload();
                                }, 1200);
                            });
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
                const copyDone = copyAssignment?.status === 'DONE';

                // EDITOR
                const editorAssignment = assignments.find(a =>
                    a.user.roles.some(r => r.title === 'EDITOR')
                );

                const hasEditor = !!editorAssignment;
                const editorDone = editorAssignment?.status === 'DONE';

                // 🔥 NOVO ITEM — REVISÃO COPYWRITER
                const copyUnderReview =
                    card.rawStatus === 'PENDING_EDITOR' ||
                    card.rawStatus === 'REVIEW_EDITOR' ||
                    card.rawStatus === 'APPROVED' ||
                    card.rawStatus === 'CONCLUDED';

                // REVISÃO FINAL
                const editorUnderReview =
                    card.rawStatus === 'APPROVED' ||
                    card.rawStatus === 'CONCLUDED';

                // PUBLICAÇÃO
                const isPublished =
                    card.rawStatus === 'CONCLUDED';

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
                        label: 'Revisão copywriter',
                        done: copyUnderReview
                    },
                    {
                        label: 'Produção editor',
                        done: editorDone
                    },
                    {
                        label: 'Revisão Editor',
                        done: editorUnderReview
                    },
                    {
                        label: 'Publicação',
                        done: isPublished
                    }
                ];
            }
        </script>




    </x-layout>
