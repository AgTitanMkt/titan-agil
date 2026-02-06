<x-layout>

        {{-- ARQUIVO --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    {{-- <div class="header-container">
        <h2 class="dashboard-page-title">Área de Cadastro</h2>
        <p class="dashboard-page-subtitle">Aqui está o fluxo e o visual da tela de Cadastro de Demanda:</p>
    </div> --}}
    
    
    <div class="cadastro-container">
    
    <div class="header-central">
        <h1 class="main-title">Criar Nova Demanda</h1>
        <p class="main-subtitle">Preencha os dados para gerar o ID e iniciar a produção.</p>
    </div>

    <div class="wizard-progress-centered">
        <div class="step-bubble active" data-step="1">1</div>
        <div class="step-line"></div>
        <div class="step-bubble" data-step="2">2</div>
        <div class="step-line"></div>
        <div class="step-bubble" data-step="3">3</div>
    </div>

    <div class="production-filters-section glass-card filters-shadow">
        <h3 class="section-title">Informações da Demanda</h3>

        <form class="filters-grid filters-grid-production" id="formDemanda">
            
            <div class="step-content active" id="step-1">
                <div class="form-row">
                    <div class="filter-group">
                        <label>Título da Demanda</label>
                        <input type="text" name="titulo" class="input-elegant" placeholder="Digite o título..." required>
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" class="select-elegant">
                            <option value="draft">📝 draft (Criada, ainda não enviada)</option>
                            <option value="pending">⏳ pending (Aguardando execução)</option>
                            <option value="in_progress">⚙️ in_progress (Em execução)</option>
                            <option value="under_review">🔍 under_review (Em revisão)</option>
                            <option value="approved">✅ approved (Aprovada)</option>
                            <option value="rejected">❌ rejected (Reprovada)</option>
                            <option value="archived">📁 archived (Encerrada manualmente)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row mt-4">
                    <div class="filter-group">
                        <label>Esta demanda é uma variação?</label>
                        <select name="variacao" id="variacaoToggle" class="select-elegant">
                            <option value="nao">Não (Original)</option>
                            <option value="sim">Sim (Variação)</option>
                        </select>
                    </div>
                    <div class="filter-group hidden" id="parentSelection">
                        <label>Criativo Pai</label>
                        <select name="parent_id" class="select-elegant">
                            <option value="">Selecionar criativo base...</option>
                            <option value="1">DBAD457 - ED</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="step-content" id="step-2">
                <div class="form-row">
                    <div class="filter-group">
                        <label>Nicho</label>
                        <select name="nicho" id="nichoSelect" class="select-elegant">
                            <option value="0">Selecione o Nicho</option>
                            @foreach($nichos as $nicho)
                                <option value="{{ $nicho }}">{{ $nicho }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Link do Documento</label>
                        <input type="url" name="link_doc" class="input-elegant" placeholder="https://docs.google.com/...">
                    </div>
                </div>
            </div>

            <div class="step-content" id="step-3">
                <div class="form-row">
                    <div class="filter-group">
                        <label>Copywriter</label>
                        <select name="copywriter_id">
                            <option value="0">Selecione o Copywriter</option>
                            @foreach($copies as $copy)
                                <option value="{{ $copy->id }}">{{ $copy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Editor</label>
                        <select name="editor_id">
                            <option value="0">Selecione o Editor</option>
                            @foreach($editors as $editor)
                                <option value="{{ $editor->id }}">{{ $editor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row mt-4">
                    <div class="filter-group">
                        <label>Prazo</label>
                        <input type="date" name="prazo" class="input-elegant">
                    </div>
                </div>
            </div>

            <div class="filter-submit-area filter-submit-area-production mt-4">
                <button type="button" id="prevBtn" class="btn-filter secondary" style="display:none;">VOLTAR</button>
                <button type="button" id="nextBtn" class="btn-filter">PRÓXIMO</button>
                <button type="submit" id="submitBtn" class="btn-filter" style="display:none;">CRIAR DEMANDA</button>
            </div>
        </form>
    </div>

    <div class="id-preview-container-fixed">
        <div class="animated-border-box">
            <div class="id-content">
                <small>ID DA DEMANDA</small>
                <h2 id="generatedID">DBAD458-VG-??</h2>
            </div>
        </div>
    </div>
</div>

<script>
    // wizard
    const sections = document.querySelectorAll('.step-content');
    const bubbles = document.querySelectorAll('.step-bubble');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    let currentStep = 0;

    function updateWizard() {
        sections.forEach((s, i) => s.classList.toggle('active', i === currentStep));
        bubbles.forEach((b, i) => b.classList.toggle('active', i <= currentStep));
        
        prevBtn.style.display = currentStep === 0 ? 'none' : 'inline-block';
        if (currentStep === sections.length - 1) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-block';
        } else {
            nextBtn.style.display = 'inline-block';
            submitBtn.style.display = 'none';
        }
    }

    nextBtn.onclick = () => { currentStep++; updateWizard(); }
    prevBtn.onclick = () => { currentStep--; updateWizard(); }

    // variacao
    document.getElementById('variacaoToggle').onchange = function() {
        document.getElementById('parentSelection').classList.toggle('hidden', this.value === 'nao');
    };

    // update ID
    document.getElementById('nichoSelect').onchange = function() {
        document.getElementById('generatedID').innerText = `${this.value}AD458-VG-??`;
    };
</script>
    

</x-layout>