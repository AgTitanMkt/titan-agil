<x-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <style>
        .suggestions {
            margin-top: 8px;
            list-style: none;
            padding: 0;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: #fff;
            max-height: 240px;
            overflow-y: auto;
        }

        .suggestion-item {
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .suggestion-item:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        .hidden {
            display: none;
        }

        #parentSuggestions {
            background-color: #0f264b;
        }
    </style>

    <div class="cadastro-container">

        <div class="header-central">
            <h1 class="main-title">Criar Nova Demanda</h1>
            <p class="main-subtitle">Preencha os dados para gerar o ID e iniciar a produção.</p>
        </div>

        {{-- PREVIEW DO ID --}}
        <div class="id-preview-container-fixed">
            <div class="animated-border-box">
                <div class="id-content">
                    <small>ID DA DEMANDA</small>
                    <h2 id="generatedID">-</h2>
                </div>
            </div>
        </div>

        {{-- ERROS BACKEND --}}
        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="production-filters-section glass-card filters-shadow">
            <h3 class="section-title">Informações da Demanda</h3>

            <form action="{{ route('tarefas.store') }}" method="POST" id="formDemanda" novalidate>
                @csrf
                <input type="hidden" id="criativo-code" name="code">
                <input type="hidden" name="variation_number" id="variation_number">
                {{-- STEP 1 --}}
                <div class="step-content active">
                    <div class="titan-grid">

                        <div class="filter-group">
                            <label>Título da Demanda *</label>
                            <input type="text" name="titulo" class="input-elegant" required
                                placeholder="Ex: VSL Emagrecimento facebook...">
                        </div>

                        <div class="filter-group">
                            <label>Nicho *</label>
                            <div class="select-wrapper">
                                <select name="nicho" id="nichoSelect" class="select-elegant" required>
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach ($nichos as $nicho)
                                        <option value="{{ $nicho->id }}">{{ $nicho->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>É Variação?</label>
                            <div class="select-wrapper">
                                <select name="variacao" id="variacaoToggle" class="select-elegant">
                                    <option value="false">Não</option>
                                    <option value="true">Sim</option>
                                </select>
                            </div>
                        </div>

                        <div class="filter-group hidden" id="parentSelection">
                            <label>Criativo Pai</label>

                            <input type="hidden" name="parent_id" id="parent_id">

                            <input type="text" name="parentSearch" id="parentSearch" class="input-elegant"
                                placeholder="Digite para buscar... (ex: VGAD, DBAD, etc)">

                            <ul id="parentSuggestions" class="suggestions hidden"></ul>
                        </div>


                    </div>
                </div>

                {{-- STEP 2 --}}
                <div class="step-content">
                    <div class="titan-grid">
                        <div class="filter-group span-full">
                            <label>Link do Documento</label>
                            <input type="url" name="link_doc" class="input-elegant">
                        </div>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="step-content">
                    <div class="titan-grid">

                        <div class="filter-group">
                            <label>Fonte de Tráfego *</label>
                            <div class="select-wrapper">
                                <select name="fonte_trafego" class="select-elegant" required>
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach ($platforms as $platform)
                                        <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="filter-group">
                            <x-one-select name="copywriter_id" label="Copywriter" :options="$copies->pluck('name', 'id')->toArray()" />
                        </div>

                        <div class="filter-group">
                            <x-one-select name="editor_id" label="Editor" :options="$editors->pluck('name', 'id')->toArray()" />
                        </div>

                        <div class="filter-group">
                            <label>Prazo Copy</label>
                            <input type="date" name="prazo_copy" class="input-elegant">
                        </div>

                        <div class="filter-group">
                            <label>Prazo Editor</label>
                            <input type="date" name="prazo_editor" class="input-elegant">
                        </div>

                    </div>
                </div>

                {{-- BOTÕES --}}
                <div class="filter-submit-area mt-4">
                    <button type="button" id="prevBtn" class="btn-filter secondary"
                        style="display:none;">VOLTAR</button>
                    <button type="button" id="nextBtn" class="btn-filter">PRÓXIMO</button>
                    <button type="submit" id="submitBtn" class="btn-filter" style="display:none;">CRIAR
                        DEMANDA</button>
                </div>

            </form>
        </div>

        {{-- WIZARD INDICADOR --}}
        <div class="wizard-progress-centered">
            <div class="step-bubble active">1</div>
            <div class="step-line"></div>
            <div class="step-bubble">2</div>
            <div class="step-line"></div>
            <div class="step-bubble">3</div>
        </div>

    </div>

    {{-- ========================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================= --}}

    <script>
        const sections = document.querySelectorAll('.step-content');
        const bubbles = document.querySelectorAll('.step-bubble');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        let currentStep = 0;

        function updateWizard() {
            sections.forEach((s, i) => s.classList.toggle('active', i === currentStep));
            bubbles.forEach((b, i) => b.classList.toggle('active', i <= currentStep));

            prevBtn.style.display = currentStep === 0 ? 'none' : 'inline-flex';

            if (currentStep === sections.length - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-flex';
            } else {
                nextBtn.style.display = 'inline-flex';
                submitBtn.style.display = 'none';
            }
        }

        function validateCurrentStep() {
            const inputs = sections[currentStep].querySelectorAll('input, select');
            let isValid = true;

            inputs.forEach(input => {
                input.classList.remove('input-error');

                if (input.hasAttribute('required') && !input.value) {
                    input.classList.add('input-error');
                    isValid = false;
                }

                if (input.type === 'url' && input.value) {
                    try {
                        new URL(input.value);
                    } catch {
                        input.classList.add('input-error');
                        isValid = false;
                    }
                }
            });

            return isValid;
        }

        nextBtn.onclick = () => {
            if (!validateCurrentStep()) return;
            currentStep++;
            updateWizard();
        };

        prevBtn.onclick = () => {
            currentStep--;
            updateWizard();
        };

        document.getElementById('variacaoToggle').onchange = function() {

            const parentSel = document.getElementById('parentSelection');
            const parentId = document.getElementById('parent_id');
            const parentSearch = document.getElementById('parentSearch');
            const nichoSelect = document.getElementById('nichoSelect');

            if (this.value === 'true') {

                parentSel.classList.remove('hidden');

                // 🔥 torna obrigatório
                parentId.setAttribute('required', 'required');

            } else {

                parentSel.classList.add('hidden');

                // 🔥 remove obrigatoriedade
                parentId.removeAttribute('required');

                // 🔥 limpa campos
                parentId.value = '';
                parentSearch.value = '';

                nichoSelect.removeAttribute('disabled');

                document.getElementById('generatedID').innerText = '-';
                document.getElementById('criativo-code').value = '';
                document.getElementById('variation_number').value = '';
            }
        };



        document.getElementById('nichoSelect').addEventListener('change', async function() {
            try {
                let url = "{{ route('tarefas.code', ['nichoID' => 'ID_PLACEHOLDER']) }}";
                url = url.replace('ID_PLACEHOLDER', this.value);

                const response = await fetch(url);
                const data = await response.json();

                if (data.code) {
                    document.getElementById('generatedID').innerText = data.code;
                    document.querySelector('#criativo-code').value = data.code
                }

            } catch (error) {
                console.error(error);
            }
        });
    </script>

    <style>
        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .input-error {
            border: 2px solid #ff4d4f !important;
            background: #fff1f0;
        }

        .error-box {
            background: #ffecec;
            color: #b30000;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>

    {{-- preenchendo opções de tarefas pai --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const parentSelection = document.getElementById('parentSelection');
            const parentSearch = document.getElementById('parentSearch');
            const parentId = document.getElementById('parent_id');
            const suggestions = document.getElementById('parentSuggestions');

            if (!parentSelection || !parentSearch || !parentId || !suggestions) return;

            let timer = null;

            function hideSuggestions() {
                suggestions.classList.add('hidden');
                suggestions.innerHTML = '';
            }

            function showSuggestions(items) {
                suggestions.innerHTML = '';

                if (!items.length) {
                    hideSuggestions();
                    return;
                }

                items.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'suggestion-item';
                    li.textContent = `${item.code}`;
                    li.dataset.id = item.id;

                    li.addEventListener('click', async () => {

                        parentId.value = item.id;
                        parentSearch.value = `${item.code}`;

                        try {

                            let url =
                                "{{ route('tarefas.nextVariationNumber', ['taskId' => 'TASK_ID_PLACEHOLDER']) }}";
                            url = url.replace('TASK_ID_PLACEHOLDER', item.id);

                            const response = await fetch(url);
                            const data = await response.json();

                            const nextNumber = data.next_variation_number;

                            const variationCode = `${item.code}V${nextNumber}`;

                            document.getElementById('generatedID').innerText = variationCode;
                            document.getElementById('criativo-code').value = variationCode;
                            document.getElementById('variation_number').value = nextNumber;

                        } catch (error) {
                            console.error(error);
                        }

                        const nichoSelect = document.getElementById('nichoSelect');
                        nichoSelect.value = item.nicho;
                        nichoSelect.setAttribute('disabled', true);

                        hideSuggestions();
                    });

                    suggestions.appendChild(li);
                });

                suggestions.classList.remove('hidden');
            }

            parentSearch.addEventListener('input', function() {
                const term = this.value.trim();

                // se apagar, limpa seleção
                if (!term) {
                    parentId.value = '';
                    document.getElementById('generatedID').innerText = '-';
                    document.getElementById('criativo-code').value = '';
                    hideSuggestions();
                    return;
                }


                clearTimeout(timer);

                timer = setTimeout(async () => {
                    if (term.length < 2) return;

                    try {
                        let url =
                            "{{ route('ajax.criativos', ['code' => 'TERM_PLACEHOLDER']) }}";
                        url = url.replace('TERM_PLACEHOLDER', encodeURIComponent(term));

                        const res = await fetch(url);
                        const data = await res.json();

                        showSuggestions(Array.isArray(data) ? data : []);

                    } catch (e) {
                        console.error(e);
                        hideSuggestions();
                    }
                }, 350);
            });

            // fecha sugestões ao clicar fora
            document.addEventListener('click', (e) => {
                if (!parentSelection.contains(e.target)) {
                    hideSuggestions();
                }
            });

        });
    </script>



</x-layout>
