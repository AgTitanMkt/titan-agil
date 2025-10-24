<x-layout>
    <h2 class="dashboard-page-title">Dashboard</h2>
    <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>

    <div class="meta-ads-section glass-card">
        <h3 class="section-title">Meta Ads</h3>

        <form class="filters-grid">
            
            <div class="filter-group">
                <label for="produto">Produto</label>
                <select id="produto" class="custom-select">
                    <option>Produto 1</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="editor">Editor</label>
                <select id="editor" class="custom-select">
                    <option>Editor A</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="nicho">Nicho</label>
                <select id="nicho" class="custom-select">
                    <option>Nicho X</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" class="custom-select">
                    <option>Presell</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="copywriter">Copywriter</label>
                <select id="copywriter" class="custom-select">
                    <option>Copywriter R</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="vsl">VSL</label>
                <select id="vsl" class="custom-select">
                    <option>VSL 01</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="variacoes">Variações</label>
                <select id="variacoes" class="custom-select">
                    <option>Var 1-3</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="formato">Formato</label>
                <select id="formato" class="custom-select">
                    <option>Carrossel</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="angulo">Ângulo</label>
                <select id="angulo" class="custom-select">
                    <option>Ângulo A</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="minerador">Minerador</label>
                <select id="minerador" class="custom-select">
                    <option>Minerador B</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="data">Data</label>
                <select id="data" class="custom-select">
                    <option>Últimos 7 dias</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="semana">Semana</label>
                <select id="semana" class="custom-select">
                    <option>Semana 2</option>
                </select>
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
                        <th>INICIOU EM</th>
                        <th>ANUNCIOU ATÉ</th>
                        <th class="col-roas">ROAS</th>
                        <th>LUCRO BRUTO</th>
                        <th>RECEITA</th>
                        <th>INVESTIMENTO</th>
                        <th>CPC</th>
                        </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p class="ad-name">AD1</p>
                            <span class="ad-link">@titan.com</span>
                        </td>
                        <td>20/10/2025</td>
                        <td>27/10/2025</td>
                        <td class="roas-value">3.50</td>
                        <td>R$ 15.000</td>
                        <td>R$ 20.000</td>
                        <td>R$ 5.000</td>
                        <td>R$ 0.75</td>
                    </tr>
                    <tr>
                        <td>
                            <p class="ad-name">AD2</p>
                            <span class="ad-link">@titan.com</span>
                        </td>
                        <td>20/10/2025</td>
                        <td>27/10/2025</td>
                        <td class="roas-value">2.80</td>
                        <td>R$ 10.000</td>
                        <td>R$ 18.000</td>
                        <td>R$ 8.000</td>
                        <td>R$ 0.85</td>
                    </tr>
                    <tr><td><p class="ad-name">AD3</p><span class="ad-link">@titan.com</span></td><td colspan="7"></td></tr>
                    <tr><td><p class="ad-name">AD4</p><span class="ad-link">@titan.com</span></td><td colspan="7"></td></tr>
                    <tr><td><p class="ad-name">AD5</p><span class="ad-link">@titan.com</span></td><td colspan="7"></td></tr>
                    <tr><td><p class="ad-name">AD6</p><span class="ad-link">@titan.com</span></td><td colspan="7"></td></tr>
                </tbody>
            </table>
        </div>

    </div>

</x-layout>