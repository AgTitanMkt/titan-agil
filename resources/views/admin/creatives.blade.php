<x-layout>

    {{-- ALTERACAO COLLABORATOR --}}
    @php
        $collaborator = $collaborator ?? 'IN';
        $isExternal = $collaborator === 'EX';
        // $isCopy = $type === 'copywriters';
    @endphp

    <div class="titan-dashboard-wrapper">

        {{-- ALTERACAO COLLABORATOR VIEW EXTERNA OU INTERNA --}}
        <header class="titan-unified-header">
            <div class="header-top-row">
                <div class="header-brand">
                    <img src="/img/img-admin/logo titan.png" alt="Titan Logo">
                    <span class="brand-name">Métricas | Criativos |
                        {{ $isExternal ? 'Externos' : 'Internos' }}
                    </span>
                </div>

                <div class="view-selector-wrapper">
                    <span class="selector-label">2k26</span>
                    <div class="toggle-group">
                        {{-- <button id="btn-dashboard" class="btn-nav inactive">Dashboard</button>
                        <button id="btn-creatives" class="btn-nav active">Criativos</button> --}}
                    </div>
                </div>
            </div>

            {{-- ALTERACAO COLLABORATOR --}}
            <nav class="filter-toolbar-container">
                <form action="{{ route('admin.creatives', ['collaborator' => $collaborator]) }}" method="GET"
                    class="filter-main-form">

                    <input type="hidden" name="type" value="{{ $type }}">


                    <div class="filter-item item-date">
                        <label>Escolha o Filtro Desejado:</label>
                        <x-date-range name="date" :from="$startDate" :to="$endDate" />
                    </div>

                    <div class="filter-item">
                        <label>Nicho</label>
                        <select name="nicho" class="titan-select">
                            <option value="TOTAL" {{ request('nicho', 'TOTAL') === 'TOTAL' ? 'selected' : '' }}>
                                TOTAL
                            </option>

                            @foreach ($allNiches as $niche)
                                <option value="{{ $niche }}" {{ request('nicho') === $niche ? 'selected' : '' }}>
                                    {{ $niche }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="filter-item">
                        <label>Fonte</label>
                        <select name="source" class="titan-select">
                            @php $source = request('source', 'TOTAL'); @endphp

                            <option value="TOTAL" {{ $source === 'TOTAL' ? 'selected' : '' }}>TOTAL</option>
                            <option value="FACEBOOK" {{ $source === 'FACEBOOK' ? 'selected' : '' }}>FACEBOOK</option>
                            <option value="YOUTUBE" {{ $source === 'YOUTUBE' ? 'selected' : '' }}>YOUTUBE</option>
                            <option value="NATIVE" {{ $source === 'NATIVE' ? 'selected' : '' }}>NATIVE</option>
                            <option value="TIKTOK" {{ $source === 'TIKTOK' ? 'selected' : '' }}>TIKTOK</option>
                        </select>

                    </div>

                    <div class="filter-item">
                        <label>Tipo</label>
                        <select name="creation_type" class="titan-select">
                            @php $typeFilter = request('creation_type', 'TOTAL'); @endphp

                            <option value="TOTAL" {{ $typeFilter === 'TOTAL' ? 'selected' : '' }}>TOTAL</option>
                            <option value="original" {{ $typeFilter === 'original' ? 'selected' : '' }}>Original
                            </option>
                            <option value="variation" {{ $typeFilter === 'variation' ? 'selected' : '' }}>Variação
                            </option>
                        </select>
                    </div>

                    {{-- NOVO FILTRO - ALTARACAO COLLABORATOR --}}
                    <div class="filter-item">
                        <label>Colaborador</label>

                        <select name="collaborator" class="titan-select">
                            <option value="IN" {{ $collaborator === 'IN' ? 'selected' : '' }}>
                                Interno
                            </option>

                            <option value="EX" {{ $collaborator === 'EX' ? 'selected' : '' }}>
                                Externo
                            </option>
                        </select>
                    </div>

                    <div class="filter-item item-agent">
                        <label>Copywriter</label>
                        <x-multiselect name="copywriters" :options="$allCopywriters" placeholder="Buscar copywriter..." />
                    </div>


                    <div class="filter-item item-agent">
                        <label>Editor</label>
                        <x-multiselect name="editors" :options="$allEditors" placeholder="Buscar editor..." />
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-execute-filter">
                            <i class="fas fa-filter"></i>
                            <span>FILTRAR</span>
                        </button>
                    </div>

                </form>
            </nav>
        </header>

        <section id="section-creatives" class="content-section">


            <div class="top-creatives-podium">
                @foreach ($topCreatives as $index => $top)
                    <div class="podium-card rank-{{ $index + 1 }}">
                        <div class="rank-badge">
                            @if ($index == 0)
                                <i class="fas fa-crown"></i>
                            @else
                                {{ $index + 1 }}º
                            @endif
                        </div>

                        <div class="podium-info">
                            <span class="creative-id">{{ $top->creative_code }}</span>
                            <div class="agents-line">
                                <small><i class="fas fa-pen-nib"></i>
                                    {{ explode(' ', $top->copywriter)[0] ?? '---' }}</small>
                                <small><i class="fas fa-video"></i>
                                    {{ explode(' ', $top->editor)[0] ?? '---' }}</small>
                            </div>
                        </div>

                        <div class="podium-stats">
                            <div class="stat-item">
                                <span class="stat-label">LUCRO</span>
                                <span class="stat-value profit">@dollar($top->total_profit)</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">ROI</span>
                                <span class="stat-value roi">{{ number_format($top->roi_decimal * 100, 1) }}%</span>
                            </div>
                        </div>

                        <div class="source-tag {{ strtolower($top->source ?? 'unknown') }}">
                            {{ $top->source ?? '---' }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="metrics-top-cards">
                <div class="metric-card">
                    <span class="label">TESTADO</span>
                    <span class="value">{{ $totalTestado }}</span>
                </div>
                <div class="metric-card">
                    <span class="label">POTENCIAL</span>
                    <span class="value">{{ $totalPotencial }}</span>
                </div>
                <div class="metric-card">
                    <span class="label">VALIDADOS</span>
                    <span class="value">{{ $totalValidados }}</span>
                </div>
                <div class="metric-card">
                    <span class="label">WIN/RATE</span>
                    <span class="value">{{ number_format($winRate, 1) }}%</span>
                </div>
                <div class="metric-card">
                    <span class="label">CLIQUES</span>
                    <span class="value">{{ number_format($totalClicks) }}</span>
                </div>
                <div class="metric-card">
                    <span class="label">CUSTO</span>
                    <span class="value">@dollar($totalCost)</span>
                </div>
                <div class="metric-card highlight-profit">
                    <span class="label">LUCRO</span>
                    <span class="value">@dollar($totalProfit)</span>
                </div>
                <div class="metric-card">
                    <span class="label">ROI TOTAL</span>
                    <span class="value">{{ number_format($totalROI, 1) }}%</span>
                </div>
            </div>


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
                                    <th class="sortable-main" data-sort="editor">Editor <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="tested">Testado <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="potential">Potencial <i
                                            class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="validated">Validados <i
                                            class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="winrate">Win/Rate <i
                                            class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="clicks">Cliques <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="conversions">Conversões <i
                                            class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="cost">Custo <i class="fas fa-sort"></i></th>
                                    <th class="sortable-main" data-sort="profit">Lucro <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable-main" data-sort="roi">ROI (%) <i class="fas fa-sort"></i>
                                    </th>
                                </tr>
                            </thead>

                            {{-- NOVO CAMPO COPY PARA CONSEGUIR PEGAR MANUALMENTE O COPYWRITER PELO SISTEMA --}}

                            <tbody id="creativesTable">
                                @foreach ($creatives as $creative)
                                    <tr class="creative-row">
                                        <td>{{ $creative->creative_code }}</td>
                                        {{-- <td>{{ $creative->copywriter ?? '---' }}</td> --}}
                                        <td>
                                            @if (!$creative->copywriter)
                                                <form class="assign-form">

                                                    <input type="hidden" name="creative_code"
                                                        value="{{ $creative->creative_code }}">

                                                    <select name="copywriter_id" class="assign-copy">

                                                        <option value="">Selecionar</option>

                                                        @foreach ($allCopywriters as $copy)
                                                            <option value="{{ $copy['value'] }}">{{ $copy['label'] }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                    <button type="button" class="btn-save-agent">
                                                        Salvar Copy
                                                    </button>

                                                </form>
                                            @else
                                                {{ $creative->copywriter }}
                                            @endif
                                        </td>

                                        {{-- NOVO CAMPO EDITOR PARA CONSEGUIR PEGAR MANUALMENTE O EDITOR PELO SISTEMA --}}

                                        {{-- <td>{{ trim(explode(',', $creative->editor ?? '---')[0]) }}</td> --}}
                                        <td>

                                            @if (!$creative->editor)
                                                <form class="assign-form">

                                                    <input type="hidden" name="creative_code"
                                                        value="{{ $creative->creative_code }}">

                                                    <select name="editor_id" class="assign-editor">

                                                        <option value="">Selecionar</option>

                                                        @foreach ($allEditors as $editor)
                                                            <option value="{{ $editor['value'] }}">
                                                                {{ $editor['label'] }}</option>
                                                        @endforeach

                                                    </select>

                                                    <button type="button" class="btn-save-agent">
                                                        Salvar Editor
                                                    </button>

                                                </form>
                                            @else
                                                {{ trim(explode(',', $creative->editor)[0]) }}
                                            @endif

                                        </td>


                                        {{-- testado/potencial --}}
                                        <td><span
                                                class="badge {{ $creative->total_clicks > 0 ? 'positive' : 'negative' }}">
                                                {{ $creative->total_clicks > 0 ? 'SIM' : 'NÃO' }}
                                            </span></td>

                                        <td><span
                                                class="badge {{ $creative->total_profit > 0 ? 'neutral' : 'negative' }}">
                                                {{ $creative->total_profit > 200 ? 'ALTO' : ($creative->total_profit > 0 ? 'MÉDIO' : 'BAIXO') }}
                                            </span></td>

                                        <td>{{ $creative->total_conversions }}</td>
                                        <td>{{ number_format($creative->roi_decimal * 100, 1) }}%</td>
                                        <td>{{ number_format($creative->total_clicks) }}</td>
                                        <td>{{ $creative->total_conversions }}</td>
                                        <td>@dollar($creative->total_cost)</td>

                                        <td
                                            class="{{ $creative->total_profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                            @dollar($creative->total_profit)
                                        </td>

                                        <td
                                            class="{{ $creative->roi_decimal >= 0 ? 'roi-positive' : 'roi-negative' }}">
                                            {{ number_format($creative->roi_decimal * 100, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
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

    {{-- SCRIPT PARA SALVAR AUTOMATICO E MANUAL OS COPY/EDITORES NO SISTEMA --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll(".btn-save-agent").forEach(button => {

                button.addEventListener("click", async function() {

                    const form = this.closest("form")
                    const td = form.closest("td")

                    const data = new FormData(form)

                    try {
                        const response = await fetch("{{ route('creative.assign') }}", {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: data
                        });

                        if (!response.ok) throw new Error('Erro na resposta do servidor');

                        const result = await response.json();

                        if (result.success) {
                            // pega o nome que voltou (seja copy ou editor)
                            let name = result.copywriter || result.editor;
                            if (name) {
                                td.innerHTML = name; // substitui o form pelo nome salvo
                                showToast();
                            }
                        }
                    } catch (error) {
                        console.error("Erro detalhado:", error);
                        // erro para caso ser bo ver no devtools
                        alert("Erro: " + error.message);
                    }

                })

            })

            function showToast() {

                const toast = document.getElementById("toast-success")

                toast.style.display = "block"

                setTimeout(() => {

                    toast.style.display = "none"

                }, 2500)

            }

        })
    </script>

    {{-- CABO. --}}









</x-layout>
