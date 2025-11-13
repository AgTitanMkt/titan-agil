<x-layout>

    <style>
        .details-row {
            background: rgba(255, 255, 255, 0.04);
        }

        .nested-table {
            width: 100%;
            border-collapse: collapse;
        }

        .nested-table th {
            background: rgba(255, 255, 255, 0.10);
            padding: 8px;
            text-align: left;
            font-size: 0.85rem;
        }

        .nested-table td {
            padding: 6px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .positive {
            color: #4CAF50;
        }

        .negative {
            color: #e74c3c;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <h2 class="dashboard-page-title">Produção Editores</h2>
    <p class="dashboard-page-subtitle">Visão geral e filtros de performance</p>

    <div class="production-filters-section glass-card" style="z-index: 30;">
        <h3 class="section-title">Produção Editores</h3>

        <form class="filters-grid filters-grid-production">

            <div class="filter-group">
                <x-date-range name="date" :from="$startDate" :to="$endDate" label="Intervalo de Datas" />
            </div>
            <div class="filter-group">
                <x-multiselect name="editors" label="Editores" :options="$allEditors" :selected="request('editors', [])"
                    placeholder="Selecione um ou mais editores">
                </x-multiselect>
            </div>

            <div class="filter-submit-area filter-submit-area-production">
                <button type="submit" class="btn-filter">FILTRAR</button>
            </div>
        </form>
    </div>


    <div class="copy-production-section glass-card">
        <h3 class="section-title">Edições Produzidas</h3>

        <div class="table-responsive">
            <table class="metrics-main-table">
                <thead>
                    <tr>
                        <th>Editor</th>
                        <th>Criativos</th>
                        <th>Cliques</th>
                        <th>Conversões</th>
                        <th>Custo</th>
                        <th>Lucro</th>
                        <th>ROI (%)</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($editors as $editor)
                        @php
                            $key = 'editor-' . $editor->user_id;
                            $creatives = $creativesByEditor[$editor->user_id] ?? collect();
                        @endphp
                        {{-- Linha principal --}}
                        <tr class="campaign-row" onclick="toggleDetails('{{ $key }}')">
                            <td class="fw-bold">{{ $editor->editor_name }}</td>
                            <td>{{ $editor->total_creatives }}</td>
                            <td>{{ $editor->total_clicks }}</td>
                            <td>{{ $editor->total_conversions }}</td>
                            <td>@dollar($editor->total_cost)</td>
                            <td>@dollar($editor->total_profit)</td>
                            <td class="{{ $editor->total_roi >= 0 ? 'positive' : 'negative' }}">
                                {{ number_format($editor->total_roi * 100, 2, ',', '.') }}%
                            </td>
                        </tr>

                        {{-- Detalhes expandíveis --}}
                        <tr id="details-{{ $key }}" class="details-row" style="display: none;">
                            <td colspan="7">
                                <table class="nested-table">
                                    <thead>
                                        <tr>
                                            <th>Criativo</th>
                                            <th>Cliques</th>
                                            <th>Conversões</th>
                                            <th>Custo</th>
                                            <th>Lucro</th>
                                            <th>Receita</th>
                                            <th>ROI</th>
                                            <th>Gráfico</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($creatives as $cr)
                                            <tr>
                                                <td>{{ $cr->creative_code }}</td>
                                                <td>{{ $cr->clicks }}</td>
                                                <td>{{ $cr->conversions }}</td>
                                                <td>@dollar($cr->cost)</td>
                                                <td>@dollar($cr->profit)</td>
                                                <td>@dollar($cr->revenue)</td>
                                                <td class="{{ $cr->roi >= 0 ? 'positive' : 'negative' }}">
                                                    {{ number_format($cr->roi * 100, 2, ',', '.') }}%
                                                </td>
                                                <td>
                                                    <button class="btn-chart"
                                                        onclick="openCreativeChart('{{ $cr->creative_code }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon-chart">
                                                            <polyline points="3 17 9 11 13 15 21 7"></polyline>
                                                            <polyline points="14 7 21 7 21 14"></polyline>
                                                        </svg>

                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div id="creativeChartModal" class="creative-modal" style="display:none;">
        <div class="creative-modal-content">
            <div class="creative-modal-header">
                <h3 id="chartCreativeTitle"></h3>
                <button class="creative-modal-close" onclick="closeCreativeChart()">×</button>
            </div>

            <div class="creative-chart-wrapper">
                <canvas id="creativeChart"></canvas>
            </div>
        </div>
    </div>


    <style>
        .creative-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.80);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            padding: 20px;
        }

        .creative-modal-content {
            width: 100%;
            max-width: 1400px;
            background: #0f0f0f;
            border-radius: 16px;
            padding: 25px 35px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.35);
        }

        .creative-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .creative-modal-header h3 {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .creative-modal-close {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 2rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .creative-chart-wrapper {
            width: 100%;
            height: 500px;
        }

        @media (max-width: 768px) {
            .creative-chart-wrapper {
                height: 350px;
            }
        }
    </style>


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
    <script>
        function toggleDetails(key) {
            const row = document.getElementById(`details-${key}`);
            if (row.style.display === "none") {
                row.style.display = "table-row";
            } else {
                row.style.display = "none";
            }
        }
    </script>
    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                // variável global segura
                window.creativeChart = null;

                function openCreativeChart(creativeCode) {

                    document.getElementById("creativeChartModal").style.display = "flex";
                    document.getElementById("chartCreativeTitle").innerText = "Carregando...";

                    fetch(`/admin/creative-history?creative=${creativeCode}`)
                        .then(res => res.json())
                        .then(data => {

                            const labels = data.map(item => item.date);
                            const profitData = data.map(item => item.profit);
                            const costData = data.map(item => item.cost);
                            const roiData = data.map(item => (item.roi * 100)); // ROI em %

                            const ctx = document
                                .getElementById("creativeChart")
                                .getContext("2d");

                            // 🚨 Destroy apenas se for realmente um objeto Chart
                            if (window.creativeChart instanceof Chart) {
                                window.creativeChart.destroy();
                            }

                            window.creativeChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: "Lucro",
                                            data: profitData,
                                            borderColor: "#4ADE80",
                                            backgroundColor: "rgba(74,222,128,0.10)",
                                            tension: 0.35,
                                            borderWidth: 2,
                                            pointRadius: 2,
                                            yAxisID: 'y'
                                        },
                                        {
                                            label: "Custo",
                                            data: costData,
                                            borderColor: "#F87171",
                                            backgroundColor: "rgba(248,113,113,0.10)",
                                            tension: 0.35,
                                            borderWidth: 2,
                                            pointRadius: 2,
                                            yAxisID: 'y'
                                        },
                                    ]
                                },

                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,

                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: "#ddd",
                                                font: {
                                                    size: 13
                                                }
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: "rgba(30,30,30,0.85)",
                                            titleColor: "#fff",
                                            bodyColor: "#fff",
                                            borderColor: "#333",
                                            borderWidth: 1,
                                            padding: 10,
                                            cornerRadius: 8,
                                        }
                                    },

                                    scales: {
                                        x: {
                                            ticks: {
                                                color: "#aaa",
                                                maxRotation: 60,
                                                minRotation: 45
                                            },
                                            grid: {
                                                color: "rgba(255,255,255,0.06)"
                                            }
                                        },
                                        y: {
                                            position: 'left',
                                            ticks: {
                                                color: "#aaa"
                                            },
                                            grid: {
                                                color: "rgba(255,255,255,0.05)"
                                            },
                                            title: {
                                                display: true,
                                                text: 'Valor ($)',
                                                color: "#ccc"
                                            }
                                        },
                                    }
                                }
                            });



                            document.getElementById("chartCreativeTitle").innerText = creativeCode;
                        });
                }

                function closeCreativeChart() {
                    document.getElementById("creativeChartModal").style.display = "none";
                }
            </script>
        @endpush
    @endonce


</x-layout>
