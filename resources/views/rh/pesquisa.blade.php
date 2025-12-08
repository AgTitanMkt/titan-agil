<x-rh-component>
    <link rel="stylesheet" href="{{ asset('css/rh-pesquisa.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="rh-pesquisa-wrapper">

        {{-- CONTAINER AZUL PRINCIPAL --}}
        <div class="main-header-container-exato">
            <div class="decorative-stars"></div> 
            
            <span class="header-kicker">CENTRAL DE RECURSOS HUMANOS</span>
            
            <h1 class="main-title-exato">RH, quem você deseja <span class="visualizar">visualizar?</span></h1>
            
            <div class="search-info-bar">
                <div class="search-callout-box">
                    <span>Pesquise pelo campo abaixo</span>
                    <div class="arrow-circle"><i class="fas fa-arrow-right"></i></div>
                </div>
            </div>
        </div>

        {{-- AREA DE BUSCA --}}
        <div class="rh-search-filter-area">
            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input type="text" id="collaboratorSearchInput" placeholder="Pesquise sua opção aqui..." oninput="filterCollaborator()">
            </div>
            
            <button class="btn-filter-icon">
                <i class="fas fa-filter"></i>
            </button>

            <div class="filter-category-select">
                <select id="categoryFilterSelect" onchange="filterCategory()">
                    <option value="all">Qual categoria deseja?</option>
                    <option value="identification">Identificação</option>
                    <option value="personal">Pessoal</option>
                    <option value="corporate">Corporativo</option>
                    <option value="contract">Contratual</option>
                    <option value="finance">Financeira e Pagamento</option>
                </select>
                <i class="fas fa-pencil-alt select-icon"></i>
            </div>
            
            <button class="btn-filter-action">FILTRAR</button>
        </div>


        {{-- TABELA E HISTORICO --}}
        <div class="rh-content-area">
            
            <div id="noResults" class="message-card" style="display: flex;">
                <i class="fas fa-info-circle"></i>
                <span>Digite o nome ou matrícula do colaborador para iniciar a pesquisa.</span>
            </div>
            
            <div id="collaboratorResultContainer" class="result-container-flex" style="display: none;">
                
                {{-- DETALHES --}}
                <div class="result-details-col-full">
                    <h2 class="col-title">Detalhes do Colaborador Pesquisado</h2>

                {{-- NOVA CLASS DA TABELA APOS PESQUISAR O NOME - ALTERACOES AQUI --}}
                        <div class="single-collaborator-table-container custom-scrollbar">
                            <table class="rh-table" id="collaboratorDataTable">
                                <thead>
                                    <tr>
                                    {{-- coluans rolaveis --}}
                                    <th data-category="identification">MATRÍCULA</th>
                                    <th data-category="identification">NOME COMPLETO</th>
                                    <th data-category="identification">STATUS</th>
                                    <th data-category="identification">FOTO</th>
                                    <th data-category="personal">DATA NASC.</th>
                                    <th data-category="personal">IDADE</th>
                                    <th data-category="personal">TELEFONE</th>
                                    <th data-category="personal">EMAIL PESSOAL</th>
                                    <th data-category="personal">ENDEREÇO</th>
                                    <th data-category="personal">ESTADO</th>
                                    <th data-category="corporate">SETOR</th>
                                    <th data-category="corporate">HEAD</th>
                                    <th data-category="corporate">NÍVEL</th>
                                    <th data-category="corporate">CARGO</th>
                                    <th data-category="corporate">EMAIL CORPORATIVO</th>
                                    <th data-category="contract">ADMISSÃO</th>
                                    <th data-category="contract">FIM EXP.</th>
                                    <th data-category="contract">DEMISSÃO</th>
                                    <th data-category="contract">CNPJ/CPF</th>
                                    <th data-category="finance">PIX</th>
                                    <th data-category="finance">PAYONEER ID</th>
                                    <th data-category="finance">EMAIL PAYONEER</th>
                                    <th data-category="finance">SALÁRIO</th>
                                    <th data-category="finance">MOEDA</th>
                                    <th data-category="finance">% COMISSÃO 1</th>
                                    <th data-category="finance">DESC. 1</th>
                                    <th data-category="finance">BONIFICAÇÃO I</th>
                                    <th data-category="finance">BONIFICAÇÃO II</th>
                                    <th>AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody id="collaboratorTableBody">
                                {{-- linha do colaborador - ID --}}
                            </tbody>
                        </table>
                    </div>

                    <div class="action-panel">
                        <button class="btn-action-panel btn-view-profile" onclick="openProfileModal(currentProfileId)"><i class="far fa-eye"></i> Abrir Perfil Completo</button>
                        <button class="btn-action-panel btn-export-pdf"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
                        <button class="btn-action-panel btn-view-files"><i class="fas fa-paperclip"></i> Ver Anexos (12)</button>
                    </div>

                </div>

                {{-- HISTORICO DO COLABORADOR --}}
                <div class="result-history-area">
                    <h2 class="col-title">Histórico de Alterações (Critérios de Pesquisa)</h2>
                    <div class="history-list glass-card custom-scrollbar">
                        
                        {{-- ITENS DO HISTORICO  --}}
                        <div class="history-item salary-raise">
                            <div class="item-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="item-content">
                                <strong>Aumento Salarial</strong>
                                <span class="item-date">10/10/2025</span>
                                <p>R$ 8.000,00 <i class="fas fa-long-arrow-alt-right"></i> R$ 10.000,00</p>
                            </div>
                        </div>

                        <div class="history-item promotion">
                            <div class="item-icon"><i class="fas fa-trophy"></i></div>
                            <div class="item-content">
                                <strong>Promoção</strong>
                                <span class="item-date">01/08/2025</span>
                                <p>Júnior <i class="fas fa-long-arrow-alt-right"></i> Pleno</p>
                            </div>
                        </div>

                        <div class="history-item warning">
                            <div class="item-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="item-content">
                                <strong>Advertência</strong>
                                <span class="item-date">05/07/2025</span>
                                <p>Atraso Recorrente na Call Semanal</p>
                            </div>
                        </div>
                        
                         <div class="history-item training">
                            <div class="item-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="item-content">
                                <strong>Treinamento Concluído - Front End</strong>
                                <span class="item-date">20/06/2025</span>
                                <p>Front End Level 10</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL LATERAL APPLE - COPIADO E COLADO DO COLABORADORES --}}
    <div class="rh-profile-modal-overlay" id="rhProfileModal" style="display: none;">
        <div class="rh-profile-modal-panel apple-modal">
            
            {{-- HEADER --}}
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
                {{-- tabs --}}
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
                {{-- TAB: RESUMO --}}
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
                             {{--  via JS --}}
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


    {{-- SCRIPTS --}}
    <script>
        // variaveis adicionadas
        let currentProfileId = null;
        const modal = document.getElementById('rhProfileModal');
        const NEW_COLLAB_ID = 'NEW_COLLABORATOR';

        // dados iniciais - apenas simulacao com ANYE
        const collaboratorsData = {
            'TITAN202500007': {
                photo: 'https://image2url.com/images/1764788554835-3ea785bf-21d7-4cab-92fd-a188049386da.jpg', name: 'ANYE STEPHANIE SOUZA', role: 'Gerente de Projetos', sector: 'GESTÃO', status: 'active',
                // MODAL AO LADO IGUAL DOS COLABORADORES 
                matricula: 'TITAN202500007', statusText: 'ATIVO',
                identification: { foto: 'https://image2url.com/images/1764788554835-3ea785bf-21d7-4cab-92fd-a188049386da.jpg' },
                personal: { nasc: '09/06/2001', idade: '23', tel: '(19) 98224-9095', email: 'anyesouzaa@icloud.com', end: 'Av. Salvador di Bernardi, 840...', estado: 'SC' },
                corporate: { setor: 'GESTÃO', head: '-', nivel: 'SÊNIOR', cargo: 'GERENTE DE PROJETOS', emailCorp: 'ANYE@TITAN.COM' },
                contract: { admissao: '04/02/2025', fimExp: '04/05/2025', demissao: '-', doc: '54.398.503/0001-96' },
                finance: { pix: '54.398.503/0001-96', payoneerId: '-', payoneerEmail: 'anye@payoneer.com', salario: 'R$ 10.000,00', moeda: 'BRL', comissao1: '0.5%', desc1: 'LUCRO GERAL', bonif1: 'Meta 1M', bonif2: '1 Salário' },
                // DETALHADO
                personalModal: { nasc: '2001-06-09', tel: '(19) 98224-9095', email: 'anyesouzaa@icloud.com', logradouro: 'Av. Salvador di Bernardi', numero: '840', bairro: 'Campinas', cidade: 'São José', uf: 'SC', cep: '88101-260' },
                financeModal: { salario: 10000.00, pix: '54398503000196', payoneerId: 'AP888', payoneerEmail: 'anye@payoneer.com', lootrush: 'LT-ANYE003', moeda: 'BRL' },
                history: [{ item: 'Notebook Dell Latitude', date: '2025-02-04', author: 'RH' }],
                files: [{ name: 'Contrato Admissão.pdf', type: 'pdf', size: '250kb' }]
            },
             'TITAN202500008': {
                photo: '', name: 'ANDRE VERAS', role: 'Dev Full Stack', sector: 'DEV', status: 'active',
                matricula: 'TITAN202500008', statusText: 'ATIVO',
                identification: { foto: '' },
                personal: { nasc: '15/03/1998', idade: '27', tel: '(11) 99999-9999', email: 'andre.@gmail.com', end: 'Rua das Flores, 123...', estado: 'SP' },
                corporate: { setor: 'DEV', head: 'ANYE', nivel: 'SENIOR', cargo: 'DEV FULL STACK', emailCorp: 'ANDREVERAS@TITAN.COM' },
                contract: { admissao: '01/01/2025', fimExp: '01/04/2025', demissao: '-', doc: '000.000.000-00' },
                finance: { pix: 'CPF', payoneerId: 'MP888', payoneerEmail: 'andre.pay@email.com', salario: 'R$ 5.000,00', moeda: 'BRL', comissao1: '-', desc1: '-', bonif1: '-', bonif2: '-' },
                personalModal: { nasc: '1998-03-15', tel: '(11) 99999-9999', email: 'andre.@gmail.com', logradouro: 'Rua das Flores', numero: '123', bairro: 'Centro', cidade: 'São Paulo', uf: 'SP', cep: '01000-000' },
                financeModal: { salario: 5000.00, pix: '000.000.000-00', payoneerId: 'MP888', payoneerEmail: 'andre.pay@email.com', lootrush: 'LT-ANDRE', moeda: 'BRL' },
                history: [{ item: 'Monitar Dell 24"', date: '2025-01-01', author: 'IT' }],
                files: [{ name: 'CV Andre.pdf', type: 'pdf', size: '150kb' }]
            }
        };

        // TELA DE PESQUISA 

        function filterCollaborator() {
            const query = document.getElementById('collaboratorSearchInput').value.toLowerCase().trim();
            const resultContainer = document.getElementById('collaboratorResultContainer');
            const noResults = document.getElementById('noResults');
            const tableBody = document.getElementById('collaboratorTableBody');

            if (query.length < 3) {
                resultContainer.style.display = 'none';
                noResults.style.display = 'flex';
                noResults.querySelector('span').innerText = `Digite o nome ou matrícula do colaborador para iniciar a pesquisa.`;
                return;
            }

            // simula a busca no banco de dados
            const foundId = Object.keys(collaboratorsData).find(id => 
                collaboratorsData[id].name.toLowerCase().includes(query) || id.toLowerCase().includes(query)
            );

            if (foundId) {
                currentProfileId = foundId;
                const data = collaboratorsData[foundId];
                renderCollaboratorData(data);
                resultContainer.style.display = 'flex';
                noResults.style.display = 'none';
                // filtro ser carregado como novo resultado
                filterCategory(); 
            } else {
                resultContainer.style.display = 'none';
                noResults.style.display = 'flex';
                noResults.querySelector('span').innerText = `Nenhum colaborador encontrado para "${query}".`;
            }
        }

        function renderCollaboratorData(data) {
            const statusClass = data.status === 'active' ? 'active' : 'inactive';
            const statusBadge = `<span class="status-badge ${statusClass}">${data.statusText}</span>`;

            // (TDs)
            let rowHTML = `<tr class="rh-row" data-status="${data.status}" data-id="${data.matricula}" onclick="openProfileModal('${data.matricula}')">`;

            // DADOS ROLAVEIS PADRAO
            rowHTML += `<td data-category="identification">${data.matricula}</td>`;
            rowHTML += `<td data-category="identification" class="fw-bold">${data.name}</td>`;
            rowHTML += `<td data-category="identification">${statusBadge}</td>`;
            rowHTML += `<td data-category="identification"><div class="avatar-sm" style="background-image: url('${data.identification.foto}');"></div></td>`;
            
            // PESSOAL
            rowHTML += `<td data-category="personal">${data.personal.nasc}</td>`;
            rowHTML += `<td data-category="personal">${data.personal.idade}</td>`;
            rowHTML += `<td data-category="personal">${data.personal.tel}</td>`;
            rowHTML += `<td data-category="personal">${data.personal.email}</td>`;
            rowHTML += `<td data-category="personal">${data.personal.end}</td>`;
            rowHTML += `<td data-category="personal">${data.personal.estado}</td>`;

            // CORPORATIVO
            rowHTML += `<td data-category="corporate">${data.corporate.setor}</td>`;
            rowHTML += `<td data-category="corporate">${data.corporate.head}</td>`;
            rowHTML += `<td data-category="corporate">${data.corporate.nivel}</td>`;
            rowHTML += `<td data-category="corporate">${data.corporate.cargo}</td>`;
            rowHTML += `<td data-category="corporate">${data.corporate.emailCorp}</td>`;

            // CONTRATUAL
            rowHTML += `<td data-category="contract">${data.contract.admissao}</td>`;
            rowHTML += `<td data-category="contract">${data.contract.fimExp}</td>`;
            rowHTML += `<td data-category="contract">${data.contract.demissao}</td>`;
            rowHTML += `<td data-category="contract">${data.contract.doc}</td>`;

            // FINANCEIRO
            rowHTML += `<td data-category="finance">${data.finance.pix}</td>`;
            rowHTML += `<td data-category="finance">${data.finance.payoneerId}</td>`;
            rowHTML += `<td data-category="finance">${data.finance.payoneerEmail}</td>`;
            rowHTML += `<td class="text-money" data-category="finance">${data.finance.salario}</td>`;
            rowHTML += `<td data-category="finance">${data.finance.moeda}</td>`;
            rowHTML += `<td data-category="finance">${data.finance.comissao1}</td>`;
            rowHTML += `<td data-category="finance">${data.finance.desc1}</td>`;
            rowHTML += `<td data-category="finance">${data.finance.bonif1}</td>`;
            rowHTML += `<td data-category="finance">${data.finance.bonif2}</td>`;
            
            // ACOES - MATRICULA
            rowHTML += `<td class="td-actions text-center">
                            <button class="btn-icon-sm" onclick="event.stopPropagation(); openProfileModal('${data.matricula}')"><i class="far fa-eye"></i></button>
                        </td>`;
            
            rowHTML += `</tr>`;
            
            document.getElementById('collaboratorTableBody').innerHTML = rowHTML;
        }

        // filtro de categoria 

        function filterCategory() {
            const categoryFilter = document.getElementById('categoryFilterSelect').value;
            
            // TH e TD
            document.querySelectorAll('#collaboratorDataTable th[data-category], #collaboratorDataTable td[data-category]').forEach(cell => {
                
                if (categoryFilter === 'all' || cell.dataset.category === categoryFilter) {
                     cell.style.display = ''; 
                } else {
                     cell.style.display = 'none'; 
                }
            });
        }


        // MODAL 

        function loadProfileData(id) {
            const data = collaboratorsData[id];
            if (!data) return;
            
            // carrega dados principais
            document.getElementById('modalName').value = data.name;
            document.getElementById('modalRole').value = data.role;
            document.getElementById('modalSector').value = data.corporate.setor;
            document.getElementById('modalStatusSelect').value = data.status;
            document.getElementById('modalAvatar').style.backgroundImage = `url('${data.photo}')`;

            // carrega tabs
            document.getElementById('resumoMatricula').innerText = data.matricula;
            document.getElementById('resumoAdmissao').innerText = data.contract.admissao;
            document.getElementById('resumoSalario').innerText = data.finance.salario;
            
            document.getElementById('pessoalNasc').value = data.personalModal.nasc;
            document.getElementById('pessoalTelefone').value = data.personalModal.tel;
            document.getElementById('pessoalEmail').value = data.personalModal.email;
            document.getElementById('pessoalLogradouro').value = data.personalModal.logradouro;
            document.getElementById('pessoalNumero').value = data.personalModal.numero;
            document.getElementById('pessoalComplemento').value = data.personalModal.complemento || '';
            document.getElementById('pessoalBairro').value = data.personalModal.bairro;
            document.getElementById('pessoalCidade').value = data.personalModal.cidade;
            document.getElementById('pessoalUF').value = data.personalModal.uf;
            document.getElementById('pessoalCEP').value = data.personalModal.cep;

            document.getElementById('corpMatricula').value = data.matricula;
            document.getElementById('corpSetor').value = data.corporate.setor;
            document.getElementById('corpHead').value = data.corporate.head;
            document.getElementById('corpCargo').value = data.corporate.cargo;
            document.getElementById('corpEmail').value = data.corporate.emailCorp;
            document.getElementById('contratoAdmissao').value = data.contract.admissao;
            document.getElementById('contratoFimExp').value = data.contract.fimExp;
            document.getElementById('contratoDemissao').value = data.contract.demissao;
            document.getElementById('contratoDoc').value = data.contract.doc;

            document.getElementById('financeSalario').value = data.financeModal.salario;
            document.getElementById('financePix').value = data.financeModal.pix;
            document.getElementById('financePayoneerId').value = data.financeModal.payoneerId;
            document.getElementById('financePayoneerEmail').value = data.financeModal.payoneerEmail;
            document.getElementById('financeLootrush').value = data.financeModal.lootrush;
            document.getElementById('financeMoeda').value = data.financeModal.moeda;

            // renderiza o historico e anexos ao modal
            renderItemHistory(collaboratorsData[id].history || []);
            renderFileList(collaboratorsData[id].files || []);
        }
        
        // funcoes do modal
        function openProfileModal(id) {
            currentProfileId = id;
            loadProfileData(id);
            modal.style.display = 'flex';
            document.querySelector('.tab-link').click(); 
            // initProfileCanvas(); // canvas
        }
        
        function closeProfileModal() { modal.style.display = 'none'; }
        
        function openTab(evt, tabName) {
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
            document.getElementById(tabName).style.display = 'block';
            evt.currentTarget.classList.add('active');
            // if(tabName === 'tab-paint') resizeProfileCanvas(); // canvas 
        }

        // historico e arquivos
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
        function saveProfileChanges() { alert('Perfil salvo com sucesso!'); closeProfileModal(); }
        
        // mock do canvas
        let pCanvas, pCtx, pPainting = false;
        function initProfileCanvas() { /* ... */ }
        function resizeProfileCanvas() { /* ... */ }
        function clearProfileCanvas() { /* ... */ }
        function saveProfileCanvas() { /* ... */ }
    </script>



</x-rh-component>