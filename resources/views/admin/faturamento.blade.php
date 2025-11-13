<x-layout>
    <h2 class="dashboard-page-title">Dashboard</h2>
    <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>

    {{-- Z-INDEX  --}}
    <div class="meta-ads-section glass-card" style="z-index: 20;"> 
        <h3 class="section-title">Meta Ads</h3>

        <form class="filters-grid">

            {{-- FILTROS --}}
            <div class="filter-group">
                <x-multiselect
                    name="creatives" 
                    label="Criativos" 
                    :options="$allCreatives" 
                    :selected="request('creatives', [])" 
                    placeholder="Selecione um ou mais criativos">
                </x-multiselect>
            </div>

            <div class="filter-group">
                <x-multiselect
                    name="editors" 
                    label="Editores" 
                    :options="$allEditors" 
                    :selected="request('editors', [])" 
                    placeholder="Selecione um ou mais editores">
                </x-multiselect>
            </div>

            
            <div class="filter-group">
                <x-multiselect
                    name="copywriters" 
                    label="Copywriters" 
                    :options="$allCopywriters" 
                    :selected="request('copywriters', [])" 
                    placeholder="Selecione um ou mais copywriters">
                </x-multiselect>
            </div>

            
            <div class="filter-group">
                <x-multiselect
                    name="sources" 
                    label="Contas" 
                    :options="$allSources" 
                    :selected="request('sources', [])" 
                    placeholder="Selecione uma ou mais contas">
                </x-multiselect>
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
        <div class="paginate">{{$topCreatives->links()}}</div>

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