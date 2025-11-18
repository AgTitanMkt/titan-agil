<x-layout>

    <h2 class="dashboard-page-title">Prévia da Importação</h2>
    <p class="dashboard-page-subtitle">
        Ajuste os agentes antes de confirmar o salvamento.
    </p>

    <form action="{{ route('admin.import.store') }}" method="POST">
        @csrf

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
                                {{-- Código --}}
                                <td>
                                    {{ $row['code'] }}
                                    <input type="hidden" name="items[{{$i}}][code]" value="{{ $row['code'] }}">
                                </td>

                                {{-- COPY --}}
                                <td>
                                    <select name="items[{{$i}}][copy_id]"
                                        class="form-control"
                                        style="background: rgba(255,255,255,0.06);
                                               color:white;
                                               border: 1px solid rgba(255,255,255,0.15);
                                               padding: 8px;
                                               border-radius: 6px;">
                                        <option value="">Selecione o Copy</option>

                                        @foreach($copywriters as $copy)
                                            <option value="{{ $copy->id }}"
                                                {{ $row['copy_id'] == $copy->id ? 'selected' : '' }}>
                                                {{ $copy->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </td>

                                {{-- EDITOR --}}
                                <td>
                                    <select name="items[{{$i}}][editor_id]"
                                        class="form-control"
                                        style="background: rgba(255,255,255,0.06);
                                               color:white;
                                               border: 1px solid rgba(255,255,255,0.15);
                                               padding: 8px;
                                               border-radius: 6px;">
                                        <option value="">Selecione o Editor</option>

                                        @foreach($editors as $editor)
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

</x-layout>
