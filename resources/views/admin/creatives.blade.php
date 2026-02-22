<x-layout>

    <div class="titan-dashboard-wrapper">

        <header class="titan-unified-header">
            <div class="header-top-row">
                <div class="header-brand">
                    <img src="/img/img-admin/logo titan.png" alt="Titan Logo">
                    <span class="brand-name">Métricas | Criativos</span>
                </div>

                <div class="view-selector-wrapper">
                    <span class="selector-label">Fevereiro 2k26</span>
                    <div class="toggle-group">
                        {{-- <button id="btn-dashboard" class="btn-nav inactive">Dashboard</button>
                        <button id="btn-creatives" class="btn-nav active">Criativos</button> --}}
                    </div>
                </div>
            </div>

            <nav class="filter-toolbar-container">
                <form class="filter-main-form">

                    <div class="filter-item item-date">
                        <label>Período</label>
                        <input type="date">
                    </div>

                    <div class="filter-item">
                        <label>Nicho</label>
                        <select class="titan-select">
                            <option>TOTAL</option>
                            <option>Memoria</option>
                            <option>Diabetes</option>
                            <option>Prostata</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label>Fonte</label>
                        <select class="titan-select">
                            <option>TOTAL</option>
                            <option>FACEBOOK</option>
                            <option>YOUTUBE</option>
                            <option>TIKTOK</option>
                            <option>NATIVE</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label>Tipo</label>
                        <select class="titan-select">
                            <option>TOTAL</option>
                            <option>Original</option>
                            <option>Variação</option>
                        </select>
                    </div>

                    <div class="filter-item item-agent">
                        <label>Copywriter</label>
                        <input type="text" placeholder="Buscar profissional..." class="titan-select">
                    </div>

                    <div class="filter-item item-agent">
                        <label>Editor</label>
                        <input type="text" placeholder="Buscar profissional..." class="titan-select">
                    </div>

                    <div class="filter-actions">
                        <button type="button" class="btn-execute-filter">
                            <i class="fas fa-filter"></i>
                            <span>FILTRAR</span>
                        </button>
                    </div>

                </form>
            </nav>
        </header>

        <section id="section-creatives" class="content-section">

            <div class="production-filters-section glass-card filters-shadow">
                <h3 class="section-title">
                    Produção Criativos
                </h3>



                <link rel="stylesheet"
                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

                <div class="copy-production-section glass-card table-shadow">

                    <div class="table-responsive">

                        <table class="metrics-main-table">
                            <thead>
                                <tr>
                                    <th class="sortable-main" data-sort="id">ID-Criativo <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="copy">Copywriter <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="editor">Editor <i class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="tested">Testado <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="potential">Potencial <i
                                            class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="validated">Validados <i
                                            class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="winrate">Win/Rate <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="clicks">Cliques <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="conversions">Conversões <i
                                            class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="cost">Custo <i class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="profit">Lucro <i class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="roi">ROI (%) <i class="fas fa-sort"></i></th>
                                </tr>
                            </thead>

                            <tbody id="creativesTable">
                                {{-- testes mockados --}}
                                <tr class="creative-row">
                                    <td>001</td>
                                    <td>Eduardo Bezerra</td>
                                    <td>Julia Tavares</td>
                                    <td><span class="badge positive">SIM</span></td>
                                    <td><span class="badge neutral">ALTO</span></td>
                                    <td>5</td>
                                    <td>60%</td>
                                    <td>1200</td>
                                    <td>150</td>
                                    <td>R$ 2.000</td>
                                    <td class="profit-value">R$ 3.500</td>
                                    <td class="roi-value">75%</td>
                                </tr>

                                <tr class="creative-row">
                                    <td>002</td>
                                    <td>Rogerio Barenco</td>
                                    <td>Thiago Gomes</td>
                                    <td><span class="badge negative">NÃO</span></td>
                                    <td><span class="badge neutral">BAIXO</span></td>
                                    <td>1</td>
                                    <td>20%</td>
                                    <td>800</td>
                                    <td>40</td>
                                    <td>R$ 1.500</td>
                                    <td class="profit-value">-R$ 900</td>
                                    <td class="roi-value">-35%</td>
                                </tr>

                                <tr class="creative-row">
                                    <td>001</td>
                                    <td>Eduardo Bezerra</td>
                                    <td>Julia Tavares</td>
                                    <td><span class="badge positive">SIM</span></td>
                                    <td><span class="badge neutral">ALTO</span></td>
                                    <td>5</td>
                                    <td>60%</td>
                                    <td>1200</td>
                                    <td>150</td>
                                    <td>R$ 2.000</td>
                                    <td class="profit-value">R$ 3.500</td>
                                    <td class="roi-value">75%</td>
                                </tr>

                                <tr class="creative-row">
                                    <td>002</td>
                                    <td>Rogerio Barenco</td>
                                    <td>Thiago Gomes</td>
                                    <td><span class="badge negative">NÃO</span></td>
                                    <td><span class="badge neutral">BAIXO</span></td>
                                    <td>1</td>
                                    <td>20%</td>
                                    <td>800</td>
                                    <td>40</td>
                                    <td>R$ 1.500</td>
                                    <td class="profit-value">-R$ 900</td>
                                    <td class="roi-value">-35%</td>
                                </tr>
                                <tr class="creative-row">
                                    <td>001</td>
                                    <td>Eduardo Bezerra</td>
                                    <td>Julia Tavares</td>
                                    <td><span class="badge positive">SIM</span></td>
                                    <td><span class="badge neutral">ALTO</span></td>
                                    <td>5</td>
                                    <td>60%</td>
                                    <td>1200</td>
                                    <td>150</td>
                                    <td>R$ 2.000</td>
                                    <td class="profit-value">R$ 3.500</td>
                                    <td class="roi-value">75%</td>
                                </tr>

                                <tr class="creative-row">
                                    <td>002</td>
                                    <td>Rogerio Barenco</td>
                                    <td>Thiago Gomes</td>
                                    <td><span class="badge negative">NÃO</span></td>
                                    <td><span class="badge neutral">BAIXO</span></td>
                                    <td>1</td>
                                    <td>20%</td>
                                    <td>800</td>
                                    <td>40</td>
                                    <td>R$ 1.500</td>
                                    <td class="profit-value">-R$ 900</td>
                                    <td class="roi-value">-35%</td>
                                </tr>
                                <tr class="creative-row">
                                    <td>001</td>
                                    <td>Eduardo Bezerra</td>
                                    <td>Julia Tavares</td>
                                    <td><span class="badge positive">SIM</span></td>
                                    <td><span class="badge neutral">ALTO</span></td>
                                    <td>5</td>
                                    <td>60%</td>
                                    <td>1200</td>
                                    <td>150</td>
                                    <td>R$ 2.000</td>
                                    <td class="profit-value">R$ 3.500</td>
                                    <td class="roi-value">75%</td>
                                </tr>

                                <tr class="creative-row">
                                    <td>002</td>
                                    <td>Rogerio Barenco</td>
                                    <td>Thiago Gomes</td>
                                    <td><span class="badge negative">NÃO</span></td>
                                    <td><span class="badge neutral">BAIXO</span></td>
                                    <td>1</td>
                                    <td>20%</td>
                                    <td>800</td>
                                    <td>40</td>
                                    <td>R$ 1.500</td>
                                    <td class="profit-value">-R$ 900</td>
                                    <td class="roi-value">-35%</td>
                                </tr>


                            </tbody>
                        </table>

                    </div>
                </div>
        </section>

    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const table = document.querySelector(".metrics-main-table");
            const headers = document.querySelectorAll(".sortable-main");
            const tbody = document.getElementById("creativesTable");

            headers.forEach(header => {
                header.addEventListener("click", function() {

                    const key = header.getAttribute("data-sort");
                    const rows = Array.from(tbody.querySelectorAll("tr"));
                    const asc = header.classList.toggle("asc");
                    header.classList.toggle("desc", !asc);

                    rows.sort((a, b) => {

                        const getValue = (row) => {
                            const index = Array.from(header.parentNode.children)
                                .indexOf(header);
                            let value = row.children[index].innerText.replace(
                                /[R$,%\s]/g, '');

                            if (!isNaN(value)) return parseFloat(value);
                            return value.toLowerCase();
                        };

                        let A = getValue(a);
                        let B = getValue(b);

                        if (typeof A === "number" && typeof B === "number") {
                            return asc ? A - B : B - A;
                        } else {
                            return asc ? A.localeCompare(B) : B.localeCompare(A);
                        }
                    });

                    rows.forEach(row => tbody.appendChild(row));
                });
            });

            // highlight lucro/ROI
            document.querySelectorAll(".creative-row").forEach(row => {
                const profit = row.querySelector(".profit-value").innerText.replace(/[R$,\s]/g, '');
                const roi = row.querySelector(".roi-value").innerText.replace(/[%\s]/g, '');

                const profitNumber = parseFloat(profit);
                const roiNumber = parseFloat(roi);

                if (profitNumber > 0 && roiNumber > 0) {
                    row.classList.add("creative-positive");
                    row.querySelector(".profit-value").classList.add("profit-positive");
                    row.querySelector(".roi-value").classList.add("roi-positive");
                } else {
                    row.classList.add("creative-negative");
                    row.querySelector(".profit-value").classList.add("profit-negative");
                    row.querySelector(".roi-value").classList.add("roi-negative");
                }
            });

        });
    </script>


</x-layout>
