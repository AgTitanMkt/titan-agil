<x-layout>
    <h2 class="dashboard-page-title">Dashboard</h2>
    <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>

    {{-- Z-INDEX  --}}
    <div class="meta-ads-section glass-card" style="z-index: 20;"> 
        <h3 class="section-title">Meta Ads</h3>

        <form class="filters-grid">

            {{-- FILTROS --}}
            <div class="filter-group">
                <label for="creatives-filter">Criativos</label>
                <div id="creatives-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Criativos (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="creatives[]" value="creative_01">Creative [Código 01]</label>
                        <label class="dropdown-item"><input type="checkbox" name="creatives[]" value="creative_02">Creative [Código 02]</label>
                        <label class="dropdown-item"><input type="checkbox" name="creatives[]" value="creative_03">Creative [Código 03]</label>
                        <label class="dropdown-item"><input type="checkbox" name="creatives[]" value="creative_01">Creative [Código 01]</label>
                        <label class="dropdown-item"><input type="checkbox" name="creatives[]" value="creative_02">Creative [Código 02]</label>
                        <label class="dropdown-item"><input type="checkbox" name="creatives[]" value="creative_03">Creative [Código 03]</label>
                        {{-- @foreach ($allCreatives as $creative) ... @endforeach --}}
                    </div>
                </div>
            </div>

            
            <div class="filter-group">
                <label for="editor-filter">Editor</label>
                <div id="editor-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Editor (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="editor[]" value="editor_a">Editor A</label>
                        <label class="dropdown-item"><input type="checkbox" name="editor[]" value="editor_b">Editor B</label>
                        <label class="dropdown-item"><input type="checkbox" name="editor[]" value="editor_a">Editor A</label>
                        <label class="dropdown-item"><input type="checkbox" name="editor[]" value="editor_b">Editor B</label>
                        <label class="dropdown-item"><input type="checkbox" name="editor[]" value="editor_a">Editor A</label>
                        <label class="dropdown-item"><input type="checkbox" name="editor[]" value="editor_b">Editor B</label>
                        {{-- @foreach ($allEditors as $editor) ... @endforeach --}}
                    </div>
                </div>
            </div>

            
            <div class="filter-group">
                <label for="copywriter-filter">Copywriter</label>
                <div id="copywriter-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Copywriter (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="copy_r">Copywriter R</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="copy_s">Copywriter S</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="copy_r">Copywriter R</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="copy_s">Copywriter S</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="copy_r">Copywriter R</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="copy_s">Copywriter S</label>
                        {{-- @foreach ($allCopywriters as $copywriter) ... @endforeach --}}
                    </div>
                </div>
            </div>

            
            <div class="filter-group">
                <label for="sources-filter">Conta</label>
                <div id="sources-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Conta (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        {{-- SELECAO --}}
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s1">FACEBOOK | ED(LM) | RE</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s2">FACEBOOK | EMAG | TRAFFIC PADRÃO</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s3">GAADS - DAVID - CONTA POATAN</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s4">FACEBOOK | MEMORIAL(LM) | RE</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s5">GAADS - YT-ARY - OZOB</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s6">FACEBOOK | TRAFFIC PADRÃO - ED (new)</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s7">GAADS - DAVID - CONTA 02</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s8">FACEBOOK | WL(DAM)</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s9">GAADS - DAVID - CONTA AMONG US</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s10">FACEBOOK | TRÁFEGO KV</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s11">FACEBOOK | MEMÓRIA (NI)</label>
                        <label class="dropdown-item"><input type="checkbox" name="sources[]" value="s12">FACEBOOK | WL(INT)</label>
                        {{-- @foreach ($allSources as $source ) ... @endforeach --}}
                    </div>
                </div>
            </div>

            <div class="filter-submit-area">
                <button type="submit" class="btn-filter">FILTRAR</button>
            </div>
        </form>
    </div>


    <div class="best-creatives-section glass-card">
        <h3 class="section-title">Melhores Criativos</h3>

        <div class="table-responsive">
            <table class="creatives-table">
                <thead>
                    <tr>
                        <th class="col-ad">AD</th>
                        <th class="col-roas">ROAS</th>
                        <th>LUCRO BRUTO</th>
                        <th>INVESTIMENTO</th>
                        <th>RECEITA</th>
                        <th>CPC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topCreatives as $top)
                        <tr>
                            <td>
                                <p class="ad-name">{{ $top->creative_code }}</p>
                                
                                <span class="ad-link">{{ $top->source }}</span>
                            </td>
                            <td>{{ $top->roi * 100 }}%</td>
                            <td>@real($top->profit)</td>
                            <td>@real($top->cost)</td>
                            <td>@real($top->cost + $top->profit)</td>
                            <td>?</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdowns = document.querySelectorAll('.custom-dropdown-filter');

            dropdowns.forEach(dropdown => {
                const header = dropdown.querySelector('.dropdown-header');
                const display = dropdown.querySelector('.selected-items-display');
                const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
                // pega os Criativos ', 'Editor ', etc...
                const baseText = display.textContent.split('(')[0].trim(); 

                // atualiza o texto de selecao
                const updateDisplay = () => {
                    const checked = Array.from(checkboxes).filter(c => c.checked);
                    if (checked.length === 0) {
                        display.textContent = `${baseText} (0)`;
                    } else if (checked.length === 1) {
                        display.textContent = `${baseText} (1)`; 
                    } else {
                        display.textContent = `${baseText} (${checked.length})`;
                    }
                };

                // inicia o display
                updateDisplay(); 
                
                // toggle do dropdown
                header.addEventListener('click', () => {
                    dropdown.classList.toggle('open');
                });

                // atualiza o display para marcar/desmarcar
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateDisplay);
                });

                // fecha o dropdown ao clicar fora
                document.addEventListener('click', (event) => {
                    if (!dropdown.contains(event.target)) {
                        dropdown.classList.remove('open');
                    }
                });
            });
        });
    </script>

</x-layout>