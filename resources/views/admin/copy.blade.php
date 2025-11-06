<x-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <h2 class="dashboard-page-title">Produção Copywriters</h2>
    <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>

    <div class="production-filters-section glass-card" style="z-index: 30;">
        <h3 class="section-title">Produção Copywriters</h3>
        
        <form class="filters-grid filters-grid-production">
            
            <div class="filter-group">
                <x-multiselect
                    name="copywriters" 
                    label="Copywriters" 
                    :options="$allCopywriters" 
                    :selected="request('copywriters', [])" 
                    placeholder="Selecione um ou mais copywriters">
                </x-multiselect>
            </div>
            
            <div class="filter-submit-area filter-submit-area-production">
                <button type="submit" class="btn-filter">FILTRAR</button>
            </div>
        </form>
    </div>


    <div class="copy-production-section glass-card">
        <h3 class="section-title">Copies Produzidas por Copywriter</h3>

        <div class="table-responsive">
            <table class="creatives-table production-table">
                <thead>
                    <tr>
                        <th class="col-author">AUTOR</th>
                        <th>COPIES PRODUZIDAS</th>
                        <th>TOTAL PROFIT</th>
                        <th>ROI MÉDIO</th>
                        {{-- <th>VARIAÇÕES PRODUZIDAS</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($copies as $copie )
                        <tr>
                            <td>
                                <p class="ad_name">{{$copie->agent_name}}</p>
                                {{-- <span class="ad-link">{{  }}</span> --}}
                            </td>
                            <td>{{ $copie->total_creatives }}</td>
                            <td>@real($copie->total_profit)</td>
                            <td>{{ $copie->avg_roi*100 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="paginate">{{$copies->links()}}</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdowns = document.querySelectorAll('.custom-dropdown-filter');

            dropdowns.forEach(dropdown => {
                const header = dropdown.querySelector('.dropdown-header');
                const display = dropdown.querySelector('.selected-items-display');
                const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]');
                // pega o texto base: 'Produto ', 'Source ', etc...
                const baseText = display.textContent.split('(')[0].trim(); 

                // atualiza o texto de seleção
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

                // atualiza o display ao marcar/desmarcar
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