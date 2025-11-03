<x-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <h2 class="dashboard-page-title">Produção Copywriters</h2>
    <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>

    <div class="production-filters-section glass-card" style="z-index: 30;">
        <h3 class="section-title">Produção Copywriters</h3>
        
        <form class="filters-grid filters-grid-production">
            
            <div class="filter-group">
                <label for="produto-filter">Produto</label>
                <div id="produto-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Produto (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="produto[]" value="prod_x">Produto X</label>
                        <label class="dropdown-item"><input type="checkbox" name="produto[]" value="prod_y">Produto Y</label>
                        <label class="dropdown-item"><input type="checkbox" name="produto[]" value="prod_z">Produto Z</label>
                        <label class="dropdown-item"><input type="checkbox" name="produto[]" value="prod_x">Produto X</label>
                        <label class="dropdown-item"><input type="checkbox" name="produto[]" value="prod_y">Produto Y</label>
                        <label class="dropdown-item"><input type="checkbox" name="produto[]" value="prod_z">Produto Z</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-group">
                <label for="source-filter">Source</label>
                <div id="source-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Source (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="source[]" value="meta">Meta Ads</label>
                        <label class="dropdown-item"><input type="checkbox" name="source[]" value="google">Google Ads</label>
                        <label class="dropdown-item"><input type="checkbox" name="source[]" value="tiktok">TikTok Ads</label>
                        <label class="dropdown-item"><input type="checkbox" name="source[]" value="meta">Meta Ads</label>
                        <label class="dropdown-item"><input type="checkbox" name="source[]" value="google">Google Ads</label>
                        <label class="dropdown-item"><input type="checkbox" name="source[]" value="tiktok">TikTok Ads</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-group">
                <label for="nicho-filter">Nicho</label>
                <div id="nicho-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Nicho (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="nicho[]" value="nicho_a">Nicho A</label>
                        <label class="dropdown-item"><input type="checkbox" name="nicho[]" value="nicho_b">Nicho B</label>
                        <label class="dropdown-item"><input type="checkbox" name="nicho[]" value="nicho_c">Nicho C</label>
                         <label class="dropdown-item"><input type="checkbox" name="nicho[]" value="nicho_a">Nicho A</label>
                        <label class="dropdown-item"><input type="checkbox" name="nicho[]" value="nicho_b">Nicho B</label>
                        <label class="dropdown-item"><input type="checkbox" name="nicho[]" value="nicho_c">Nicho C</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-group">
                <label for="tipo_ad-filter">Tipo de Ad</label>
                <div id="tipo_ad-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Tipo de Ad (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="tipo_ad[]" value="vsl">VSL</label>
                        <label class="dropdown-item"><input type="checkbox" name="tipo_ad[]" value="imagem">Imagem</label>
                        <label class="dropdown-item"><input type="checkbox" name="tipo_ad[]" value="reel">Reel</label>
                          <label class="dropdown-item"><input type="checkbox" name="tipo_ad[]" value="vsl">VSL</label>
                        <label class="dropdown-item"><input type="checkbox" name="tipo_ad[]" value="imagem">Imagem</label>
                        <label class="dropdown-item"><input type="checkbox" name="tipo_ad[]" value="reel">Reel</label>
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
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="rogerio_b">Rogério Berenco</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="gabriel_g">Gabriel Gomes</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="julia_t">Júlia Tavares</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="vinicius_g">Vinicius Gomes</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="cana_p_s">Canã Ponte Silva</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="vinicius_g">Vinicius Gomes</label>
                        <label class="dropdown-item"><input type="checkbox" name="copywriter[]" value="cana_p_s">Canã Ponte Silva</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-group">
                <label for="data-filter">Data</label>
                <div id="data-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Data (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="data[]" value="30d">Últimos 30 dias</label>
                        <label class="dropdown-item"><input type="checkbox" name="data[]" value="7d">Últimos 7 dias</label>
                        <label class="dropdown-item"><input type="checkbox" name="data[]" value="hoje">Hoje</label>
                        <label class="dropdown-item"><input type="checkbox" name="data[]" value="30d">Últimos 30 dias</label>
                        <label class="dropdown-item"><input type="checkbox" name="data[]" value="7d">Últimos 7 dias</label>
                        <label class="dropdown-item"><input type="checkbox" name="data[]" value="hoje">Hoje</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-group">
                <label for="semana-filter">Semana</label>
                <div id="semana-filter" class="custom-dropdown-filter" tabindex="0">
                    <div class="dropdown-header">
                        <span class="selected-items-display">Semana (0)</span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-list">
                        <label class="dropdown-item"><input type="checkbox" name="semana[]" value="sem1">Semana 1</label>
                        <label class="dropdown-item"><input type="checkbox" name="semana[]" value="sem2">Semana 2</label>
                        <label class="dropdown-item"><input type="checkbox" name="semana[]" value="sem3">Semana 3</label>
                        <label class="dropdown-item"><input type="checkbox" name="semana[]" value="sem1">Semana 1</label>
                        <label class="dropdown-item"><input type="checkbox" name="semana[]" value="sem2">Semana 2</label>
                        <label class="dropdown-item"><input type="checkbox" name="semana[]" value="sem3">Semana 3</label>
                    </div>
                </div>
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
                        <th>BODIES PRODUZIDOS</th>
                        <th>GANCHOS PRODUZIDOS</th>
                        <th>VARIAÇÕES PRODUZIDAS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p class="ad-name">Rogério Berenco</p>
                            <span class="ad-link">rogerioberenco@titan.com</span>
                        </td>
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <p class="ad-name">Bryan Santos</p>
                            <span class="ad-link">bryansantos@titan.com</span>
                        </td>
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <p class="ad-name">Júlia Tavares</p>
                            <span class="ad-link">juliatavares@titan.com</span>
                        </td>
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <p class="ad-name">Vinicius Gomes</p>
                            <span class="ad-link">viniciusgomes@titan.com</span>
                        </td>
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <p class="ad-name">Ary Neto</p>
                            <span class="ad-link">arynetoa@titan.com</span>
                        </td>
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                     <tr>
                        <td>
                            <p class="ad-name">Elvis Rodrigues</p>
                            <span class="ad-link">elvisrodrigues@titan.com</span>
                        </td>
                        <td></td> 
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
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