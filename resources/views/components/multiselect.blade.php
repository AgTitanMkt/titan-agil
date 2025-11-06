@php
    use Illuminate\Support\Str;
    $uniqueId = 'multiselect-' . Str::random(8); 
@endphp

@props([
    'name',
    'label' => 'Nome do Filtro (Ex: Criativo, Conta)',
    'options' => [
        'creative-1' => 'Creative WLAD546 H1',
        'creative-2' => 'Creative WLAD541',
        'creative-3' => 'Creative ED777',
        'creative-4' => 'Creative WKL909',
        'creative-5' => 'Creative ED3321',
        'creative-6' => 'Creative MMMA04',
        'creative-7' => 'Creative MMMB444',
        'creative-8' => 'Creative WL2111',
        'creative-9' => 'Creative MW1888',
        'creative-10' => 'Creative MM999',
        'creative-11' => 'Creative ED555',
        'creative-12' => 'Creative ED3D407',
    ],
    'selected' => ['creative-1', 'creative-2', 'creative-12'], 
    'placeholder' => 'Buscar ou Selecionar (digite para filtrar)',
    'id' => $uniqueId,
    'maxTags' => 2, 
])

{{-- HTML  --}}
<div class="filter-group {{ $id }}-container">
    
    @if ($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    <select id="{{ $id }}" name="{{ $name }}[]" multiple class="original-select hidden">
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" data-label="{{ $value }}" @if (in_array($key, $selected)) selected @endif>
                {{ $value }}
            </option>
        @endforeach
    </select>

    <div class="custom-multiselect" id="custom-{{ $id }}">
        
        <div class="multiselect-header" role="button" tabindex="0" aria-haspopup="listbox" aria-expanded="false">
            <div class="selected-summary-wrapper">
                <span class="selected-summary"></span> 
            </div>
            
            <div class="header-actions">
                <button type="button" class="btn-clear-single-filter" title="Limpar todos os selecionados deste filtro">
                    <i class="fas fa-eraser"></i> 
                </button>
                <i class="fas fa-angle-down dropdown-icon"></i>
            </div>
        </div>

        <div class="multiselect-dropdown">
            
            <div class="search-box">
                <input type="text" placeholder="Buscar..." class="search-input" aria-label="Buscar opções de filtro">
                <i class="fas fa-search search-icon"></i>
            </div>
            
            <ul class="options-list" role="listbox" aria-multiselectable="true">
                {{-- JS --}}
            </ul>
            
            <div class="dropdown-footer">
                <button type="button" class="btn-clear-all-filters" title="Limpar TODAS as seleções de TODOS os filtros na página">
                    Limpe os Filtros
                </button>
            </div>
        </div>
    </div>
</div>


@once
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endonce

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const multiselectContainer = document.getElementById('custom-{{ $id }}');
        if (!multiselectContainer) return;

        const originalSelect = document.getElementById('{{ $id }}');
        const header = multiselectContainer.querySelector('.multiselect-header');
        const searchInput = multiselectContainer.querySelector('.search-input');
        const optionsList = multiselectContainer.querySelector('.options-list');
        const summarySpan = multiselectContainer.querySelector('.selected-summary');
        const btnClearSingle = multiselectContainer.querySelector('.btn-clear-single-filter');
        const btnClearAllGlobal = multiselectContainer.querySelector('.btn-clear-all-filters');
        const maxTags = {{ $maxTags }};
        const allOptions = Array.from(originalSelect.options).map(opt => ({
            value: opt.value,
            label: opt.dataset.label,
            selected: opt.selected
        }));
        
        let isOpen = false;
        
        // FUNCAO LOGICA PADRAO
        
        function updateOriginalSelect(value, isSelected) {
            const option = originalSelect.querySelector(`option[value="${value}"]`);
            if (option) {
                option.selected = isSelected;
            }
            const localOption = allOptions.find(o => o.value === value);
            if (localOption) {
                localOption.selected = isSelected;
            }
        }

        function updateSelection(value, isSelected) {
            updateOriginalSelect(value, isSelected);
            updateHeaderDisplay();
            renderOptionsList(searchInput.value);
        }

        function clearSingleFilter() {
            allOptions.filter(o => o.selected).forEach(o => updateSelection(o.value, false));
            originalSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }
        
        function renderOptionsList(searchTerm = '') {
            optionsList.innerHTML = '';
            const lowerCaseTerm = searchTerm.toLowerCase();

            const selected = allOptions.filter(o => o.selected);
            const unselected = allOptions.filter(o => !o.selected);
            
            const listToRender = selected.concat(unselected);

            listToRender.forEach(option => {
                const label = option.label;
                const value = option.value;
                const isSelected = option.selected;

                if (label.toLowerCase().includes(lowerCaseTerm)) {
                    const listItem = document.createElement('li');
                    listItem.className = `option-item ${isSelected ? 'selected' : ''}`;
                    listItem.dataset.value = value;
                    listItem.dataset.label = label;
                    listItem.setAttribute('role', 'option');
                    listItem.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                    
                    listItem.innerHTML = `
                        <i class="fas fa-check check-icon"></i>
                        <span class="option-label">${label}</span>
                    `;
                    
                    optionsList.appendChild(listItem);
                }
            });
            
            if (optionsList.children.length === 0) {
                 optionsList.innerHTML = `<li class="no-results-item">Nenhum resultado encontrado para "${searchTerm}"</li>`;
            }
        }
        
        function updateHeaderDisplay() {
            const selectedOptions = allOptions.filter(o => o.selected);
            const count = selectedOptions.length;
            
            summarySpan.innerHTML = '';
            
            if (count === 0) {
                summarySpan.textContent = '{{ $placeholder }}';
                header.classList.remove('has-selection');
                btnClearSingle.style.display = 'none';
            } else {
                header.classList.add('has-selection');
                btnClearSingle.style.display = 'flex'; 
                
                selectedOptions.slice(0, maxTags).forEach(option => {
                    const tag = document.createElement('span');
                    tag.className = 'selected-tag';
                    tag.dataset.value = option.value;
                    tag.innerHTML = `
                        <span>${option.label}</span>
                        <button type="button" class="btn-remove-tag" title="Remover ${option.label}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    summarySpan.appendChild(tag);
                });
                
                if (count > maxTags) {
                    const overflow = document.createElement('span');
                    overflow.className = 'selected-overflow';
                    overflow.textContent = `+${count - maxTags} mais`;
                    summarySpan.appendChild(overflow);
                }
            }
            header.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
        
        // EVENT LISTENERS 

        // alternar o dropdown abre e fecha
        header.addEventListener('click', () => {
            isOpen = !isOpen;
            multiselectContainer.classList.toggle('open', isOpen);
            header.setAttribute('aria-expanded', isOpen);
            if (isOpen) {
                searchInput.focus();
                renderOptionsList(searchInput.value); 
            }
        });
        
        // selecao dos itens AGORA COM A PARADA DE PROPAGAÇÃO
        optionsList.addEventListener('click', (e) => {
            // 
            e.stopPropagation(); 
            
            const item = e.target.closest('.option-item');
            if (item && !item.classList.contains('no-results-item')) {
                const value = item.getAttribute('data-value');
                const isSelected = item.classList.contains('selected');
                
                updateSelection(value, !isSelected); 
                originalSelect.dispatchEvent(new Event('change', { bubbles: true }));
                
                // foco no campo de busca para selecao
                searchInput.focus(); 
            }
        });
        
        // remover a tagg individual
        summarySpan.addEventListener('click', (e) => {
            const removeButton = e.target.closest('.btn-remove-tag');
            if (removeButton) {
                e.stopPropagation(); 
                const tag = removeButton.closest('.selected-tag');
                const value = tag.dataset.value;
                updateSelection(value, false); 
                originalSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        // buscar
        searchInput.addEventListener('input', (e) => {
            renderOptionsList(e.target.value); 
        });

        // limpa filtro unico selecao individual
        btnClearSingle.addEventListener('click', (e) => {
            e.stopPropagation(); 
            clearSingleFilter();
        });
        
        // limpa TODOS os filtros da pagina e RECEBE UM ALERTA
        btnClearAllGlobal.addEventListener('click', (e) => {
             alert("🚨 Atenção! Esta ação irá limpar todos os filtros aplicados. Deseja realmente continuar?");
             e.stopPropagation();
        });
        
        // fecha ao clicar fora (MANTEM O FECHAMENTO AO CLICAR FORA, NAO E ACIONADO APOS CLICAR EM UMA UNICA SELECAO)
        document.addEventListener('click', (e) => {
            if (isOpen && !multiselectContainer.contains(e.target)) {
                multiselectContainer.classList.remove('open');
                header.setAttribute('aria-expanded', 'false');
                isOpen = false;
            }
        });

        // inicia tudo
        updateHeaderDisplay();
        renderOptionsList(); 
    });
</script>