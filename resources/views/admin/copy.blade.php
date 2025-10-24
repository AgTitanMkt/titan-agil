<x-layout>
    <h2 class="dashboard-page-title">Produção Copywriters</h2>
    <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>

    <div class="production-filters-section glass-card">
        <h3 class="section-title">Produção Copywriters</h3>
        
        <form class="filters-grid filters-grid-production">
            
            <div class="filter-group">
                <label for="produto">Produto</label>
                <select id="produto" class="custom-select">
                    <option>Produto X</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="source">Source</label>
                <select id="source" class="custom-select">
                    <option>Meta Ads</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="nicho">Nicho</label>
                <select id="nicho" class="custom-select">
                    <option>Nicho A</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="tipo_ad">Tipo de Ad</label>
                <select id="tipo_ad" class="custom-select">
                    <option>VSL</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="copywriter">Copywriter</label>
                <select id="copywriter" class="custom-select">
                    <option>Rogério Berenco</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="data">Data</label>
                <select id="data" class="custom-select">
                    <option>Últimos 30 dias</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="semana">Semana</label>
                <select id="semana" class="custom-select">
                    <option>Semana 1</option>
                </select>
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
                            <p class="ad-name">Gabriel Gomes</p>
                            <span class="ad-link">gabrielgomes@titan.com</span>
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
                            <p class="ad-name">Canã Ponte Silva</p>
                            <span class="ad-link">canapontesilva@titan.com</span>
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

</x-layout>