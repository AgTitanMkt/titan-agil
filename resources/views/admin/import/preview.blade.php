<x-layout>

    <style>
        select {
            background-color: unset;
            border: solid 1px;
            border-radius: 7px;
        }
    </style>

    <h2 class="dashboard-page-title">Prévia da Importação</h2>
    <p class="dashboard-page-subtitle">
        Ajuste os agentes antes de confirmar o salvamento.
    </p>

    <form action="{{ route('admin.import.store') }}" method="POST" onsubmit="preparePayload()">
        @csrf

        <input type="hidden" name="payload" id="payload">

        <div class="glass-card" style="padding: 25px; margin-top: 20px;">
            <h3 class="section-title">Registros encontrados</h3>

            <div class="table-responsive">
                <table class="metrics-main-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Copy</th>
                            <th>Editor</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($preview as $i => $row)
                            <tr>
                                <td>{{ $row['code'] }}</td>

                                {{-- SELECT COPY --}}
                                <td>
                                    <select onchange="updateCopy({{ $i }}, this.value)" class="form-control">
                                        <option value="">Selecione o Copy</option>

                                        @foreach ($copywriters as $copy)
                                            <option value="{{ $copy->id }}"
                                                {{ $row['copy_id'] == $copy->id ? 'selected' : '' }}>
                                                {{ $copy->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                {{-- SELECT EDITOR --}}
                                <td>
                                    <select onchange="updateEditor({{ $i }}, this.value)"
                                        class="form-control">
                                        <option value="">Selecione o Editor</option>

                                        @foreach ($editors as $editor)
                                            <option value="{{ $editor->id }}"
                                                {{ $row['editor_id'] == $editor->id ? 'selected' : '' }}>
                                                {{ $editor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 25px; text-align: right;">
                <button class="btn-filter">Salvar Dados</button>
                <a href="{{ route('admin.import.index') }}" class="btn-clear">Cancelar</a>
            </div>

        </div>

    </form>

    {{-- SCRIPT DE SINCRONIZAÇÃO DO JSON --}}
    <script>
        // Clona o preview vindo do controller
        let rows = @json($preview);

        function updateCopy(index, value) {
            rows[index].copy_id = value === "" ? null : parseInt(value);
        }

        function updateEditor(index, value) {
            rows[index].editor_id = value === "" ? null : parseInt(value);
        }

        function preparePayload() {
            document.getElementById('payload').value = JSON.stringify(rows);
        }
    </script>

</x-layout>
