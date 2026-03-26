<x-layout>

    <h2 class="dashboard-page-title">Importar Variações</h2>
    <p class="dashboard-page-subtitle">
        Envie a planilha de variações. O sistema filtrará automaticamente apenas IDs que contenham <b>V2, V3, etc.</b>
    </p>

    <div class="glass-card" style="padding: 25px; margin-top: 20px;">
        <h3 class="filters-title">Selecione o arquivo de variações</h3>

        {{-- ROTA ALTERADA PARA A DE VARIAÇÕES --}}
        <form action="{{ route('admin.import.variations.preview') }}" method="POST" enctype="multipart/form-data" class="filters-grid-dataset">
            @csrf

            <div class="filter-field" style="grid-column: span 4;">
                <label>Arquivo da Planilha</label>
                <input type="file" name="file" required>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Carregar Prévia das Variações</button>
                <a href="{{ route('admin.import.variations') }}" class="btn-clear">Limpar</a>
            </div>
        </form>
    </div>

</x-layout>