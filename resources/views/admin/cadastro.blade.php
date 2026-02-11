<x-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <div class="cadastro-container">

        <div class="header-central">
            <h1 class="main-title">Criar Nova Demanda</h1>
            <p class="main-subtitle">Preencha os dados para gerar o ID e iniciar a produção.</p>
        </div>

        <div class="id-preview-container-fixed">
            <div class="animated-border-box">
                <div class="id-content">
                    <small>ID DA DEMANDA</small>
                    <h2 id="generatedID">DBAD458-VG-??</h2>
                </div>
            </div>
        </div>

        {{-- <div class="wizard-progress-centered">
            <div class="step-bubble active" data-step="1">1</div>
            <div class="step-line"></div>
            <div class="step-bubble" data-step="2">2</div>
            <div class="step-line"></div>
            <div class="step-bubble" data-step="3">3</div>
        </div> --}}

        <div class="production-filters-section glass-card filters-shadow">
            <h3 class="section-title">Informações da Demanda</h3>

            <form class="filters-grid-production" id="formDemanda">

                <div class="step-content active" id="step-1">
                    <div class="titan-grid">


                        <div class="filter-group">
                            <label>Título da Demanda</label>
                            <input type="text" name="titulo" class="input-elegant"
                                placeholder="Ex: VSL - Emagrecimento 01" required>
                        </div>


                        <div class="filter-group">
                            <label>Nicho</label>
                            <div class="select-wrapper">
                                <select name="nicho" id="nichoSelect" class="select-elegant">
                                    <option value="" disabled selected>Selecione o Nicho</option>
                                    @foreach ($nichos as $nicho)
                                        <option value="{{ $nicho }}">{{ $nicho }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="filter-group">
                            <label>É Variação?</label>
                            <div class="select-wrapper">
                                <select name="variacao" id="variacaoToggle" class="select-elegant">
                                    <option value="nao">Não (Original)</option>
                                    <option value="sim">Sim (Variação)</option>
                                </select>
                            </div>
                        </div>

                        <!-- CRIATIVO PAI -->
                        {{-- <div class="filter-group span-full hidden" id="parentSelection"> - removido span full --}}
                        <div class="filter-group hidden" id="parentSelection">

                            <label>Criativo Pai (Base)</label>
                            <div class="select-wrapper">
                                <select name="parent_id" class="select-elegant">
                                    <option value="">Selecione o criativo original...</option>
                                    <option value="1">DBAD457 - ED</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="step-content" id="step-2">
                    <div class="titan-grid">
                        <div class="filter-group span-full">
                            <label>Link do Documento (Script/Briefing)</label>
                            <input type="url" name="link_doc" class="input-elegant"
                                placeholder="https://docs.google.com/document/...">
                        </div>
                    </div>
                </div>

                <div class="step-content" id="step-3">
                    <div class="titan-grid">

                        {{-- <div class="filter-group">
                <label>Copywriter</label>
                <div class="select-wrapper">
                    <select name="copywriter_id" class="select-elegant">
                        <option value="0">Selecione...</option>
                        @foreach ($copies as $copy)
                            <option value="{{ $copy->id }}">{{ $copy->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}


                        {{-- <div class="filter-group">
                <label>Editor</label>
                <div class="select-wrapper">
                    <select name="editor_id" class="select-elegant">
                        <option value="0">Selecione...</option>
                        @foreach ($editors as $editor)
                            <option value="{{ $editor->id }}">{{ $editor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}


                        {{-- etapa 3 COM NOVO COMPONENTE - COPY/EDITOS --}}

                        {{-- novo filtro --}}
                        <div class="filter-group">
                            <label>Fonte de Tráfego</label>
                            <div class="select-wrapper">
                                <select name="fonte_trafego" class="select-elegant" required>
                                    <option value="" disabled selected>Selecione a fonte</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="native">Native</option>
                                </select>
                            </div>
                        </div>
                        {{-- novo filtro --}}
                        <div class="filter-group">
                            <label>Gestor</label>
                            <div class="select-wrapper">
                                <select name="gestor" class="select-elegant" required>
                                    <option value="" disabled selected>Selecione o gestor</option>
                                    <option value="ary">Ary</option>
                                    <option value="hoberlan">Hoberlan</option>
                                    <option value="renato">Renato</option>
                                    <option value="dime">Dime</option>
                                    <option value="luigi">Luigi</option>
                                    <option value="erick">Erick</option>
                                </select>
                            </div>
                        </div>

                        {{-- NOVO COMPONENTE --}}
                        <div class="filter-group">
                            <x-one-select name="copywriter_id" label="Copywriter" :options="$copies->pluck('name', 'id')->toArray()"
                                placeholder="Selecione o copywriter" />
                        </div>

                        {{-- NOVO COMPONENTE --}}
                        <div class="filter-group">
                            <x-one-select name="editor_id" label="Editor" :options="$editors->pluck('name', 'id')->toArray()"
                                placeholder="Selecione o editor" />
                        </div>

                        <div class="filter-group">
                            <label>Prazo de Entrega Copywriter</label>
                            <input type="date" name="prazo_copy" class="input-elegant">
                        </div>

                        <div class="filter-group">
                            <label>Prazo de Entrega Editor</label>
                            <input type="date" name="prazo_editor" class="input-elegant">
                        </div>

                    </div>
                </div>

                <div class="filter-submit-area mt-4">
                    <button type="button" id="prevBtn" class="btn-filter secondary"
                        style="display:none;">VOLTAR</button>
                    <button type="button" id="nextBtn" class="btn-filter">PRÓXIMO</button>
                    <button type="submit" id="submitBtn" class="btn-filter" style="display:none;">CRIAR
                        DEMANDA</button>
                </div>
            </form>
        </div>

        <div class="wizard-progress-centered">
            <div class="step-bubble active" data-step="1">1</div>
            <div class="step-line"></div>
            <div class="step-bubble" data-step="2">2</div>
            <div class="step-line"></div>
            <div class="step-bubble" data-step="3">3</div>
        </div>

        {{-- <div class="id-preview-container-fixed">
            <div class="animated-border-box">
                <div class="id-content">
                    <small>ID DA DEMANDA</small>
                    <h2 id="generatedID">DBAD458-VG-??</h2>
                </div>
            </div>
        </div> --}}
    </div>

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

            prevBtn.style.display = currentStep === 0 ? 'none' : 'inline-flex'; // Ajuste para flex

            if (currentStep === sections.length - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-flex';
            } else {
                nextBtn.style.display = 'inline-flex';
                submitBtn.style.display = 'none';
            }
        }

        nextBtn.onclick = () => {
            if (currentStep < sections.length - 1) {
                currentStep++;
                updateWizard();
            }
        }
        prevBtn.onclick = () => {
            if (currentStep > 0) {
                currentStep--;
                updateWizard();
            }
        }

        document.getElementById('variacaoToggle').onchange = function() {
            const parentSel = document.getElementById('parentSelection');
            this.value === 'sim' ? parentSel.classList.remove('hidden') : parentSel.classList.add('hidden');
        };

        document.getElementById('nichoSelect').onchange = function() {
            document.getElementById('generatedID').innerText = `${this.value}AD458-VG-??`;
        };
    </script>
</x-layout>
