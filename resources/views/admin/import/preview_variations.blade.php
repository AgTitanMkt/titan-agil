<x-layout>
    <style>
        select { background-color: unset; border: solid 1px; border-radius: 7px; }
    </style>

    <h2 class="dashboard-page-title">Prévia das Variações</h2>
    <p class="dashboard-page-subtitle">Confirme os dados das variações antes de salvar no sistema.</p>

    {{-- ROTA ALTERADA PARA STORE DE VARIAÇÕES --}}
    <form action="{{ route('admin.import.variations.store') }}" method="POST" onsubmit="preparePayload()">
        @csrf
        <input type="hidden" name="payload" id="payload">

        <div class="glass-card" style="padding: 25px; margin-top: 20px;">
            <h3 class="section-title">Variações encontradas</h3>

            <div class="table-responsive">
                <table class="metrics-main-table">
                    <thead>
                        <tr>
                            <th>Código (Variação)</th>
                            <th>Copy</th>
                            <th>Editor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preview as $i => $row)
                            <tr>
                                <td><span class="badge badge-info">{{ $row['code'] }}</span></td>
                                <td>
                                    <select onchange="updateCopy({{ $i }}, this.value)" class="form-control">
                                        <option value="">Selecione o Copy</option>
                                        @foreach ($copywriters as $copy)
                                            <option value="{{ $copy->id }}" {{ $row['copy_id'] == $copy->id ? 'selected' : '' }}>
                                                {{ $copy->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select onchange="updateEditor({{ $i }}, this.value)" class="form-control">
                                        <option value="">Selecione o Editor</option>
                                        @foreach ($editors as $editor)
                                            <option value="{{ $editor->id }}" {{ $row['editor_id'] == $editor->id ? 'selected' : '' }}>
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
                <button class="btn-filter">Salvar Variações</button>
                <a href="{{ route('admin.import.variations') }}" class="btn-clear">Cancelar</a>
            </div>
        </div>
    </form>

    <script>
        let rows = @json($preview);
        function updateCopy(index, value) { rows[index].copy_id = value === "" ? null : parseInt(value); }
        function updateEditor(index, value) { rows[index].editor_id = value === "" ? null : parseInt(value); }
        function preparePayload() { document.getElementById('payload').value = JSON.stringify(rows); }
    </script>
</x-layout>