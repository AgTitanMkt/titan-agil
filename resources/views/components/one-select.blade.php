@php
    use Illuminate\Support\Str;
    $uniqueId = 'oneselect-' . Str::random(8);
@endphp

<div class="filter-group {{ $uniqueId }}-container one-select">

    @if ($label)
        <label for="{{ $uniqueId }}">{{ $label }}</label>
    @endif
    {{-- SELECT --}}
    <select id="{{ $uniqueId }}" name="{{ $name }}" class="original-select hidden">
        <option value="">---</option>
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" @if ($value === $selected) selected @endif>
                {{ $value }}
            </option>
        @endforeach
    </select>

    {{-- SELECT CUSTOM --}}
    <div class="custom-multiselect" id="custom-{{ $uniqueId }}">

        <div class="multiselect-header" role="button" tabindex="0">
            <div class="selected-summary-wrapper">
                <span class="selected-summary">
                    {{ $selectedLabel ?? $placeholder }}
                </span>

            </div>

            <div class="header-actions">
                <button type="button" class="btn-clear-one-filter" title="Limpar seleção">
                    <i class="fas fa-eraser"></i>
                </button>
                <i class="fas fa-angle-down dropdown-icon"></i>
            </div>
        </div>

        <div class="multiselect-dropdown">
            <div class="search-box">
                <input type="text" placeholder="Buscar..." class="search-input">
            </div>

            <ul class="options-list"></ul>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('custom-{{ $uniqueId }}');
        if (!container) return;

        const originalSelect = document.getElementById('{{ $uniqueId }}');
        const header = container.querySelector('.multiselect-header');
        const summary = container.querySelector('.selected-summary');
        const searchInput = container.querySelector('.search-input');
        const optionsList = container.querySelector('.options-list');
        const clearBtn = container.querySelector('.btn-clear-one-filter');

        let isOpen = false;
        let currentSelected = originalSelect.value || null;

        const options = Array.from(originalSelect.options)
            .filter(opt => opt.value !== '')
            .map(opt => ({
                value: opt.value,
                label: opt.text
            }));

        function renderOptions(search = '') {
            optionsList.innerHTML = '';

            options
                .filter(o => o.label.toLowerCase().includes(search.toLowerCase()))
                .forEach(option => {
                    const li = document.createElement('li');
                    li.className = 'option-item';
                    li.dataset.value = option.value;
                    li.innerHTML = `
                    <i class="fas fa-check check-icon"></i>
                    <span>${option.label}</span>
                `;

                    if (option.value === currentSelected) {
                        li.classList.add('selected');
                    }

                    optionsList.appendChild(li);
                });
        }

        function selectOption(value) {
            currentSelected = value;
            originalSelect.value = value;

            const selectedOption = options.find(o => o.value === value);

            summary.textContent = selectedOption ? selectedOption.label : '{{ $placeholder }}';

            container.classList.remove('open');
            isOpen = false;

            originalSelect.dispatchEvent(new Event('change', {
                bubbles: true
            }));
        }


        function clearSelection() {
            currentSelected = null;
            originalSelect.value = '';
            summary.textContent = '{{ $placeholder }}';
        }

        header.addEventListener('click', () => {
            isOpen = !isOpen;
            container.classList.toggle('open', isOpen);
            if (isOpen) {
                renderOptions();
                searchInput.focus();
            }
        });

        optionsList.addEventListener('click', (e) => {
            const item = e.target.closest('.option-item');
            if (!item) return;
            selectOption(item.dataset.value);
        });

        searchInput.addEventListener('input', e => {
            renderOptions(e.target.value);
        });

        clearBtn.addEventListener('click', e => {
            e.stopPropagation();
            clearSelection();
        });

        document.addEventListener('click', e => {
            if (isOpen && !container.contains(e.target)) {
                container.classList.remove('open');
                isOpen = false;
            }
        });

        renderOptions();
    });
</script>
