<x-layout>

    <h2 class="dashboard-page-title">Importar Criativos por Planilha</h2>
    <p class="dashboard-page-subtitle">
        Envie uma planilha contendo <b>ID do Criativo</b>, <b>Copy responsável</b> e <b>Editor</b>.
    </p>

    <div class="glass-card" style="padding: 25px; margin-top: 20px;">
        <h3 class="filters-title">Selecione o arquivo</h3>

        <form action="{{ route('admin.import.preview') }}" method="POST" enctype="multipart/form-data" class="filters-grid-dataset">
            @csrf

            <div class="filter-field" style="grid-column: span 4;">
                <label>Arquivo da Planilha</label>
                <input type="file" name="file" required>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Carregar Prévia</button>
                <a href="{{ route('admin.import.index') }}" class="btn-clear">Limpar</a>
            </div>
        </form>
    </div>

</x-layout>
