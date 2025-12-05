<x-rh-component>
    <link rel="stylesheet" href="{{ asset('css/rh-colaboradores.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    

    {{-- números em todos, ativos e desligados --}}
    <div class="rh-metrics-container glass-card">
    <div class="metric-box">
        <span class="metric-label">Total de Colaboradores</span>
        <span class="metric-number" id="metric-total">0</span>
    </div>

    <div class="metric-box">
        <span class="metric-label">Ativos</span>
        <span class="metric-number metric-green" id="metric-ativos">0</span>
    </div>

    <div class="metric-box">
        <span class="metric-label">Desligados</span>
        <span class="metric-number metric-red" id="metric-desligados">0</span>
    </div>
    </div>
    {{-- SCRIPT PARA PREENCHER AUTOMATICAMENTE ESSE CAMPO DE NUMEROS --}}
    <script>
    function atualizarMetricas() {
    const rows = document.querySelectorAll(".rh-row");
    let total = rows.length;
    let ativos = 0;
    let desligados = 0;

    rows.forEach(r => {
        if (r.querySelector(".status-badge.active")) ativos++;
        if (r.querySelector(".status-badge.inactive")) desligados++;
    });

    document.getElementById("metric-total").textContent = total;
    document.getElementById("metric-ativos").textContent = ativos;
    document.getElementById("metric-desligados").textContent = desligados;
}
    
    atualizarMetricas();

    </script>
    {{-- FIM DOS números em todos, ativos e desligados --}}



    <div class="rh-colaboradores-wrapper">
        {{-- FILTROS --}}
        <div class="rh-header-controls glass-card">
            <div class="rh-filters-left">
                <div class="filter-item">
                    <label><i class="fas fa-filter"></i> Status</label>
                    <div class="status-toggle-group">
                        <button class="btn-status-filter active" data-filter="all">Todos</button>
                        <button class="btn-status-filter" data-filter="active">Ativos</button>
                        <button class="btn-status-filter" data-filter="inactive">Desligados</button>
                    </div>
                </div>
                
                <div class="filter-item">
                    <label><i class="fas fa-columns"></i> Categorias Visíveis</label>
                    <div class="view-category-group">
                        {{-- BOTOES PARA ALTERAR AS COLUNAS POR CATEGORIAS --}}
                        <button class="btn-category-view active" data-category-view="all">Todos</button>
                        <button class="btn-category-view" data-category-view="identification">Identificação (Extra)</button>
                        <button class="btn-category-view" data-category-view="personal">Pessoal</button>
                        <button class="btn-category-view" data-category-view="corporate">Corporativo</button>
                        <button class="btn-category-view" data-category-view="contract">Contratual</button>
                        <button class="btn-category-view" data-category-view="finance">Financeiro</button>
                    </div>
                </div>

                <div class="filter-item search-item">
                    <label><i class="fas fa-search"></i> Busca</label>
                    <input type="text" id="tableSearch" placeholder="Buscar colaborador...">
                </div>
            </div>

            <div class="rh-actions-right">
                <button class="btn-action btn-export" onclick="exportTableData()"><i class="fas fa-file-export"></i> Exportar Excel</button>
                <button class="btn-action btn-add" onclick="openProfileModal(null, true)"><i class="fas fa-user-plus"></i> Novo Colaborador</button>
            </div>
        </div>

        {{-- TABELA PRINCIPAL --}}
        <div class="rh-table-container glass-card custom-scrollbar">
            <table class="rh-table" id="collaboratorsTable">
                <thead>
                    <tr>
                        {{-- MATRICULA NOME E STATUS - COLUNAS COM CLASS FIXAS --}}
                        <th class="th-fixed-left col-matricula shadow-layer" data-category="fixed">MATRÍCULA</th>
                        <th class="th-fixed-left col-nome shadow-layer" data-category="fixed">NOME COMPLETO</th>
                        <th class="th-fixed-left col-status shadow-layer" data-category="fixed">STATUS</th>
                        
                        {{-- COLUNAS ROLAVEIS E QUE PODEM SER CONTROLADAS PELO FILTRO DE CATEGORIA --}}
                        <th data-category="identification" class="col-foto">FOTO</th>
                        
                        {{-- CORPORATIVO --}}
                        <th data-category="corporate">SETOR</th>
                        <th data-category="corporate">HEAD</th>
                        <th data-category="corporate">NÍVEL</th>
                        <th data-category="corporate">CARGO</th>
                        <th data-category="corporate">EMAIL CORPORATIVO</th>

                        {{-- PESSOAL --}}
                        <th data-category="personal">DATA NASC.</th>
                        <th data-category="personal">IDADE</th>
                        <th data-category="personal">TELEFONE</th>
                        <th data-category="personal">EMAIL PESSOAL</th>
                        <th data-category="personal">ENDEREÇO</th>
                        <th data-category="personal">ESTADO</th>

                        {{-- CONTRATUAL --}}
                        <th data-category="contract">ADMISSÃO</th>
                        <th data-category="contract">FIM EXP.</th>
                        <th data-category="contract">DEMISSÃO</th>
                        <th data-category="contract">CNPJ/CPF</th>

                        {{-- FINANCEIRO --}}
                        <th data-category="finance">PIX</th>
                        <th data-category="finance">PAYONEER ID</th>
                        <th data-category="finance">EMAIL PAYONEER</th>
                        <th data-category="finance">CHAVE LOOTRUSH</th>
                        <th data-category="finance">SALÁRIO</th>
                        <th data-category="finance">MOEDA</th>
                        <th data-category="finance">% COMISSÃO 1</th>
                        <th data-category="finance">DESC. 1</th>
                        <th data-category="finance">BONIFICAÇÃO I</th>
                        <th data-category="finance">BONIFICAÇÃO II</th>

                        <th class="th-actions text-center shadow-layer-right">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- LINHA DA ANYE --}}
                    <tr class="rh-row" data-status="active" data-id="TITAN202500007" onclick="openProfileModal('TITAN202500007')">
                        {{-- COLUNA FIXA --}}
                        <td class="td-fixed-left col-matricula font-mono shadow-layer" data-category="fixed">TITAN202500007</td>
                        <td class="td-fixed-left col-nome fw-bold shadow-layer" data-category="fixed">ANYE STEPHANIE SOUZA</td>
                        <td class="td-fixed-left col-status shadow-layer" data-category="fixed"><span class="status-badge active">ATIVO</span></td>
                        
                        {{-- COLUNAS NAO FIXAS - ROLAVEIS --}}
                        <td data-category="identification"><div class="avatar-sm" style="background-image: url('https://image2url.com/images/1764788554835-3ea785bf-21d7-4cab-92fd-a188049386da.jpg');"></div></td>
                        <td data-category="corporate">GESTÃO</td><td data-category="corporate">-</td><td data-category="corporate">SÊNIOR</td><td data-category="corporate">GERENTE DE PROJETOS</td><td data-category="corporate">ANYE@TITAN.COM</td>
                        <td data-category="personal">09/06/2001</td><td data-category="personal">23</td><td data-category="personal">19 98224-9095</td><td data-category="personal">ANYESOUZAA@ICLOUD.COM</td><td data-category="personal">AV. SALVADOR DI BERNARDI...</td><td data-category="personal">SC</td>
                        <td data-category="contract">04/02/2025</td><td data-category="contract">-</td><td data-category="contract">-</td><td data-category="contract">54.398.503/0001-96</td>
                        <td data-category="finance">54.398.503/0001-96</td><td data-category="finance">-</td><td data-category="finance">anye@payoneer.com</td><td data-category="finance">LT-ANYE003</td><td class="text-money" data-category="finance">R$ 10.000,00</td><td data-category="finance">BRL</td><td data-category="finance">0.5%</td><td data-category="finance">LUCRO GERAL</td><td data-category="finance">Meta 1M</td><td data-category="finance">1 Salário</td>
                        
                        <td class="td-actions text-center shadow-layer-right">
                            <button class="btn-icon-sm" onclick="event.stopPropagation(); openProfileModal('TITAN202500007')"><i class="far fa-eye"></i></button>
                        </td>
                    </tr>
                     {{-- loop das linhas - open profile modal --}}
                     @for ($i = 0; $i < 12; $i++)
                     <tr class="rh-row" data-status="{{ $i % 2 == 0 ? 'active' : 'inactive' }}" data-id="TITANGEN{{$i}}" onclick="openProfileModal('TITANGEN{{$i}}')">
                        <td class="td-fixed-left col-matricula font-mono shadow-layer" data-category="fixed">TITAN2025{{ 100 + $i }}</td>
                        <td class="td-fixed-left col-nome fw-bold shadow-layer" data-category="fixed">COLABORADOR GENÉRICO {{ $i }}</td>
                        <td class="td-fixed-left col-status shadow-layer" data-category="fixed">
                            @if($i % 2 == 0) <span class="status-badge active">ATIVO</span> @else <span class="status-badge inactive">DESLIGADO</span> @endif
                        </td>
                        <td data-category="identification"><div class="avatar-sm bg-secondary"></div></td>
                        <td data-category="corporate">DEV</td><td data-category="corporate">ANYE</td><td data-category="corporate">PLENO</td><td data-category="corporate">DEV FULL STACK</td><td data-category="corporate">DEV{{$i}}@TITAN.COM</td>
                        <td data-category="personal">01/01/2000</td><td data-category="personal">24</td><td data-category="personal">11 99999-9999</td><td data-category="personal">DEV{{$i}}@GMAIL.COM</td><td data-category="personal">RUA TESTE, {{$i}}00</td><td data-category="personal">SP</td>
                        <td data-category="contract">01/01/2025</td><td data-category="contract">-</td><td data-category="contract">-</td><td data-category="contract">00.000.000/000{{$i}}-00</td>
                        <td data-category="finance">CPF</td><td data-category="finance">-</td><td data-category="finance">dev{{$i}}@payoneer.com</td><td data-category="finance">LT-DEV{{$i}}</td><td class="text-money" data-category="finance">R$ 5.000,00</td><td data-category="finance">BRL</td><td data-category="finance">-</td><td data-category="finance">-</td><td data-category="finance">-</td><td data-category="finance">-</td>
                        <td class="td-actions text-center shadow-layer-right">
                            <button class="btn-icon-sm" onclick="event.stopPropagation(); openProfileModal('TITANGEN{{$i}}')"><i class="far fa-eye"></i></button>
                        </td>
                    </tr>
                     @endfor
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL LATERAL (UTLIZEI LAYOUT DA APPPLE) --}}
    <div class="rh-profile-modal-overlay" id="rhProfileModal" style="display: none;">
        <div class="rh-profile-modal-panel apple-modal">
            
            {{-- HEADER PADRAO --}}
            <div class="modal-header-sticky">
                <div class="modal-top-bar">
                    <h4 class="modal-kicker">Perfil do Colaborador</h4>
                    <button class="btn-close-modal" onclick="closeProfileModal()"><i class="fas fa-times"></i></button>
                </div>
                <div class="profile-hero">
                    <div class="profile-avatar-lg" id="modalAvatar">
                         <div class="avatar-overlay" onclick="document.getElementById('photoUploadInput').click()">
                             <i class="fas fa-camera"></i>
                         </div>
                        <input type="file" id="photoUploadInput" style="display:none;" onchange="updateProfilePhoto(this)">
                    </div>
                    <div class="profile-main-inputs">
                        <input type="text" class="apple-input-title" id="modalName" placeholder="Nome Completo" value="Nome do Colaborador">
                        <div class="profile-sub-inputs">
                            <input type="text" class="apple-input-sub" id="modalRole" placeholder="Cargo" value="Cargo">
                            <span class="divider">•</span>
                            <input type="text" class="apple-input-sub" id="modalSector" placeholder="Setor" value="Setor">
                        </div>
                        <select class="apple-status-select" id="modalStatusSelect">
                            <option value="active">🟢 Ativo</option>
                            <option value="inactive">🔴 Inativo</option>
                        </select>
                    </div>
                </div>
                {{-- tabs--}}
                <div class="modal-tabs-scroll custom-scrollbar">
                    <button class="tab-link active" onclick="openTab(event, 'tab-resumo')">Resumo</button>
                    <button class="tab-link" onclick="openTab(event, 'tab-pessoal')">Pessoal</button>
                    <button class="tab-link" onclick="openTab(event, 'tab-corp')">Corporativo</button>
                    <button class="tab-link" onclick="openTab(event, 'tab-finance')">Financeiro</button>
                    <button class="tab-link" onclick="openTab(event, 'tab-notes')">Anotações</button>
                    <button class="tab-link" onclick="openTab(event, 'tab-paint')">Desenho</button>
                    <button class="tab-link" onclick="openTab(event, 'tab-files')">Anexos</button>
                </div>
            </div>

            
            <div class="modal-body-scroll custom-scrollbar">
                {{-- TABS RESUMO --}}
                <div id="tab-resumo" class="tab-content active">
                    <div class="info-grid-apple">
                        <div class="apple-box">
                            <label>Matrícula</label>
                            <span id="resumoMatricula">TITAN007</span>
                        </div>
                        <div class="apple-box">
                            <label>Admissão</label>
                            <span id="resumoAdmissao">04/02/2025</span>
                        </div>
                        <div class="apple-box highlight">
                            <label>Salário Base</label>
                            <span id="resumoSalario">R$ 10.000,00</span>
                        </div>
                    </div>
                     <h4 class="section-subtitle apple-subtitle">Histórico de Itens</h4>
                     <div class="history-apple-wrapper">
                         <div class="history-input-row">
                             <input type="text" id="historyItemInput" placeholder="Item (Ex: Notebook)" class="apple-input">
                             <input type="date" id="historyDateInput" class="apple-input">
                             <button class="btn-apple-action" onclick="addItemHistory()"><i class="fas fa-plus"></i></button>
                         </div>
                         <div class="history-list-apple" id="historyList">
                             {{-- via JS --}}
                         </div>
                     </div>
                </div>
                
                {{-- TAB: DADOS PESSOAIS --}}
                <div id="tab-pessoal" class="tab-content">
                     <form class="rh-form-grid">
                        <div class="form-group">
                            <label>Data de Nascimento</label>
                            <input type="date" class="apple-input" id="pessoalNasc">
                        </div>
                        <div class="form-group">
                            <label>Telefone / WhatsApp</label>
                            <input type="text" class="apple-input phone-mask" id="pessoalTelefone" placeholder="(00) 00000-0000">
                        </div>
                        <div class="form-group full">
                            <label>Email Pessoal</label>
                            <input type="email" class="apple-input" id="pessoalEmail" placeholder="exemplo@email.com">
                        </div>
                        <div class="section-divider full"><span>Endereço</span></div>
                         <div class="form-group full">
                            <label>Logradouro</label>
                            <input type="text" class="apple-input" id="pessoalLogradouro" placeholder="Rua, Av...">
                        </div>
                         <div class="form-group">
                            <label>Número</label>
                            <input type="text" class="apple-input" id="pessoalNumero">
                        </div>
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" class="apple-input" id="pessoalComplemento">
                        </div>
                         <div class="form-group">
                            <label>Bairro</label>
                            <input type="text" class="apple-input" id="pessoalBairro">
                        </div>
                         <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" class="apple-input" id="pessoalCidade">
                        </div>
                         <div class="form-group">
                            <label>UF</label>
                            <input type="text" class="apple-input" id="pessoalUF">
                        </div>
                        <div class="form-group">
                            <label>CEP</label>
                            <input type="text" class="apple-input cep-mask" id="pessoalCEP">
                        </div>
                     </form>
                </div>
                
                {{-- TAB: CORPORATIVO --}}
                <div id="tab-corp" class="tab-content">
                     <form class="rh-form-grid">
                        <div class="form-group">
                            <label>Matrícula</label>
                            <input type="text" class="apple-input locked" id="corpMatricula" readonly>
                        </div>
                        <div class="form-group">
                            <label>Setor</label>
                            <input type="text" class="apple-input" id="corpSetor">
                        </div>
                        <div class="form-group">
                            <label>Head</label>
                            <input type="text" class="apple-input" id="corpHead">
                        </div>
                        <div class="form-group">
                            <label>Cargo</label>
                            <input type="text" class="apple-input" id="corpCargo">
                        </div>
                        <div class="form-group full">
                            <label>Email Corporativo</label>
                            <input type="email" class="apple-input" id="corpEmail">
                        </div>
                        <div class="section-divider full"><span>Contratual</span></div>
                        <div class="form-group">
                            <label>Data Admissão</label>
                            <input type="date" class="apple-input" id="contratoAdmissao">
                        </div>
                         <div class="form-group">
                            <label>Fim Exp. (CT)</label>
                            <input type="date" class="apple-input" id="contratoFimExp">
                        </div>
                        <div class="form-group">
                            <label>Data Demissão</label>
                            <input type="date" class="apple-input" id="contratoDemissao">
                        </div>
                        <div class="form-group">
                            <label>CNPJ / CPF</label>
                            <input type="text" class="apple-input cnpj-cpf-mask" id="contratoDoc">
                        </div>
                     </form>
                </div>

                {{-- TAB: FINANCEIRO --}}
                <div id="tab-finance" class="tab-content">
                    <div class="finance-hero-apple">
                        <span>Salário Atual</span>
                        <input type="number" class="apple-input-huge" id="financeSalario" value="10000.00" step="0.01">
                    </div>
                    <form class="rh-form-grid mt-4">
                        <div class="form-group full">
                            <label>Chave PIX</label>
                            <input type="text" class="apple-input" id="financePix">
                        </div>
                        <div class="form-group full">
                            <label>Payoneer ID</label>
                            <input type="text" class="apple-input" id="financePayoneerId">
                        </div>
                        <div class="form-group full">
                            <label>Email Payoneer</label>
                            <input type="email" class="apple-input" id="financePayoneerEmail">
                        </div>
                        <div class="form-group full">
                            <label>Chave Lootrush</label>
                            <input type="text" class="apple-input" id="financeLootrush">
                        </div>
                        <div class="form-group full">
                            <label>Comissão</label>
                            <input type="text" class="apple-input" id="financeComissao">
                        </div>
                        <div class="form-group">
                            <label>Moeda</label>
                            <select class="apple-input" id="financeMoeda"><option>BRL</option><option>USD</option></select>
                        </div>
                    </form>
                </div>

                {{-- TAB: ANEXOS --}}
                <div id="tab-files" class="tab-content">
                    <div class="apple-files-header">
                         <button class="btn-apple-outline" onclick="alert('Baixar Tudo')"><i class="fas fa-download"></i> Baixar Tudo</button>
                         <label for="fileInput" class="btn-apple-filled"><i class="fas fa-plus"></i> Upload</label>
                         <input type="file" id="fileInput" multiple style="display:none" onchange="handleFileUpload(this.files)">
                    </div>
                    <div class="apple-file-list" id="fileList">
                        {{-- via JS --}}
                    </div>
                </div>
                
                {{-- TAB: PAINT E ANOTACOES --}}
                 <div id="tab-notes" class="tab-content">
                    <div class="chat-container custom-scrollbar" id="profileChat"></div>
                    <div class="chat-input-area">
                        <input type="text" id="chatInput" placeholder="Adicionar comentário..." class="apple-input">
                        <button onclick="sendProfileNote()" class="btn-apple-action"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
                <div id="tab-paint" class="tab-content">
                     <div class="paint-toolbar apple-toolbar">
                        <button class="p-tool active" onclick="setPaintTool('pen', this)"><i class="fas fa-pencil-alt"></i></button>
                        <button class="p-tool" onclick="setPaintTool('eraser', this)"><i class="fas fa-eraser"></i></button>
                        <input type="color" id="paintColor" value="#0f53ff">
                        <button class="p-tool btn-apple-outline" onclick="clearProfileCanvas()">Limpar</button>
                        <button class="p-tool btn-apple-filled" onclick="saveProfileCanvas()">Salvar</button>
                    </div>
                    <div class="profile-canvas-wrapper">
                        <canvas id="profileCanvas"></canvas>
                    </div>
                </div>

            </div>

            <div class="modal-footer-sticky">
                <button class="btn-apple-text" onclick="closeProfileModal()">Cancelar</button>
                <button class="btn-apple-primary" onclick="saveProfileChanges()" id="btnSaveProfile">Salvar Alterações</button>
            </div>
        </div>
    </div>

    {{-- SCRIPTS  --}}
    <script>
        // variaveis adicionadas
        let currentProfileId = null;
        let profileData = {}; 
        const modal = document.getElementById('rhProfileModal');
        const NEW_COLLAB_ID = 'NEW_COLLABORATOR';

        // dados iniciais - apenas simulacao com ANYE
        function initializeData() {
            profileData = {
                'TITAN202500007': {
                    photo: 'https://image2url.com/images/1764788554835-3ea785bf-21d7-4cab-92fd-a188049386da.jpg', name: 'ANYE STEPHANIE SOUZA', role: 'Gerente de Projetos', sector: 'GESTÃO', status: 'active',
                    personal: { nasc: '2001-06-09', tel: '(19) 98224-9095', email: 'anyesouzaa@icloud.com', logradouro: 'Av. Salvador di Bernardi', numero: '840', bairro: 'Campinas', cidade: 'São José', uf: 'SC', cep: '88101-260' },
                    corporate: { matricula: 'TITAN202500007', setor: 'GESTÃO', head: '-', cargo: 'GERENTE DE PROJETOS', email: 'ANYE@TITAN.COM' },
                    contract: { admissao: '2025-02-04', doc: '54398503000196' },
                    finance: { salario: 10000.00, pix: '54398503000196', payoneerId: '', payoneerEmail: 'anye@payoneer.com', lootrush: 'LT-ANYE003', moeda: 'BRL' },
                    history: [{ item: 'Notebook Dell Latitude', date: '2025-02-04', author: 'RH' }],
                    files: [{ name: 'Contrato Admissão.pdf', type: 'pdf', size: '250kb' }]
                }
            };
        }
        initializeData();

        // ABRE MODAL
        function openProfileModal(id, isNew = false) {
            currentProfileId = id || NEW_COLLAB_ID;
            if (isNew) {
                resetModalForm();
                document.getElementById('modalName').placeholder = "Nome do Novo Colaborador";
                document.getElementById('btnSaveProfile').innerText = 'Criar Colaborador';
            } else {
                loadProfileData(id);
            }
            modal.style.display = 'flex';
            document.querySelector('.tab-link').click(); 
            initProfileCanvas();
        }

        function resetModalForm() {
            document.querySelectorAll('.rh-profile-modal-panel input').forEach(i => i.value = '');
            document.getElementById('modalAvatar').querySelector('div').style.backgroundImage = '';
            document.getElementById('modalStatusSelect').value = 'active';
            document.getElementById('historyList').innerHTML = '';
            document.getElementById('fileList').innerHTML = '';
        }

        function loadProfileData(id) {
            const data = profileData[id];
            if (!data) return;
            document.getElementById('modalName').value = data.name;
            document.getElementById('modalRole').value = data.role;
            document.getElementById('modalSector').value = data.sector;
            document.getElementById('modalStatusSelect').value = data.status;
            document.getElementById('pessoalEmail').value = data.personal.email;
            document.getElementById('pessoalTelefone').value = data.personal.tel;
            document.getElementById('financeSalario').value = data.finance.salario;
            document.getElementById('financePix').value = data.finance.pix;
            document.getElementById('financePayoneerEmail').value = data.finance.payoneerEmail;
            document.getElementById('financeLootrush').value = data.finance.lootrush;
            renderItemHistory(data.history || []);
            renderFileList(data.files || []);
        }

        function closeProfileModal() { modal.style.display = 'none'; }
        
        function openTab(evt, tabName) {
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
            document.getElementById(tabName).style.display = 'block';
            evt.currentTarget.classList.add('active');
            if(tabName === 'tab-paint') resizeProfileCanvas();
        }

        // FILTROS POR CATEGORIAS
        
        // status
        document.querySelectorAll('.btn-status-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.btn-status-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const status = this.dataset.filter;
                document.querySelectorAll('.rh-row').forEach(row => {
                    row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
                });
            });
        });

        // categoria
        document.querySelectorAll('.btn-category-view').forEach(btn => {
            btn.addEventListener('click', function() {
                // atualiza visual dos botoes
                document.querySelectorAll('.btn-category-view').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const categoryFilter = this.dataset.categoryView;
                
                // intera sobre TODAS as categorias que tem TH e TD
                document.querySelectorAll('th[data-category], td[data-category]').forEach(cell => {
                    
                    
                    const isFixedColumn = cell.classList.contains('th-fixed-left') || cell.classList.contains('td-fixed-left');

                    
                    
                    
                    
                   
                    if (categoryFilter === 'all' || isFixedColumn || cell.dataset.category === categoryFilter) {
                         cell.style.display = ''; 
                    } else {
                         cell.style.display = 'none'; 
                    }
                });
            });
        });

        // MODAL FUNCIONALIDADES
        function addItemHistory() {
            const item = document.getElementById('historyItemInput').value;
            const date = document.getElementById('historyDateInput').value;
            if(item && date) {
                const list = document.getElementById('historyList');
                const html = `<div class="apple-history-item"><div class="icon-box received"><i class="fas fa-arrow-down"></i></div><div class="info"><strong>${item}</strong><span>${new Date(date).toLocaleDateString('pt-BR')}</span></div></div>`;
                list.insertAdjacentHTML('afterbegin', html);
                document.getElementById('historyItemInput').value = '';
            }
        }
        function renderItemHistory(data) {
            const list = document.getElementById('historyList'); list.innerHTML = '';
            data.forEach(h => { list.insertAdjacentHTML('beforeend', `<div class="apple-history-item"><div class="icon-box received"><i class="fas fa-arrow-down"></i></div><div class="info"><strong>${h.item}</strong><span>${h.date} • ${h.author}</span></div></div>`); });
        }
        function handleFileUpload(files) {
            const list = document.getElementById('fileList');
            for(let f of files) { list.insertAdjacentHTML('beforeend', `<div class="apple-file-card"><div class="file-icon"><i class="fas fa-file-alt"></i></div><div class="file-meta"><strong>${f.name}</strong><span>${(f.size/1024).toFixed(1)} KB</span></div><button class="btn-icon-download"><i class="fas fa-download"></i></button></div>`); }
        }
        function renderFileList(files) {
             const list = document.getElementById('fileList'); list.innerHTML = '';
             files.forEach(f => { list.insertAdjacentHTML('beforeend', `<div class="apple-file-card"><div class="file-icon"><i class="fas fa-file-alt"></i></div><div class="file-meta"><strong>${f.name}</strong><span>${f.size}</span></div><button class="btn-icon-download"><i class="fas fa-download"></i></button></div>`); });
        }
        
        let pCanvas, pCtx, pPainting = false;
        function initProfileCanvas() {
            pCanvas = document.getElementById('profileCanvas'); pCtx = pCanvas.getContext('2d');
            pCanvas.addEventListener('mousedown', () => pPainting = true);
            pCanvas.addEventListener('mouseup', () => { pPainting = false; pCtx.beginPath(); });
            pCanvas.addEventListener('mousemove', (e) => {
                if(!pPainting) return; const rect = pCanvas.getBoundingClientRect(); pCtx.lineWidth = 3; pCtx.lineCap = 'round'; pCtx.strokeStyle = document.getElementById('paintColor').value;
                pCtx.lineTo(e.clientX - rect.left, e.clientY - rect.top); pCtx.stroke(); pCtx.beginPath(); pCtx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
            });
        }
        function resizeProfileCanvas() { if(pCanvas) { pCanvas.width = pCanvas.parentElement.clientWidth; pCanvas.height = pCanvas.parentElement.clientHeight; } }
        function clearProfileCanvas() { pCtx.clearRect(0,0,pCanvas.width, pCanvas.height); }
        function saveProfileCanvas() { alert('Desenho salvo!'); }
        function saveProfileChanges() { alert('Perfil salvo com sucesso!'); closeProfileModal(); }
    </script>
</x-rh-component>




