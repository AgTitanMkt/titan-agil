@php
    use Illuminate\Support\Str;
@endphp

@props([
    'name',
    'label' => '',
    'options' => [],
    'selected' => [],
    'placeholder' => 'Selecione uma ou mais opções',
    'id' => Str::slug($name),
])

<div class="filter-group">
    @if ($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif

    <select id="{{ $id }}" name="{{ $name }}[]" class="custom-select select2" multiple>
        @foreach ($options as $value)
            <option value="{{ $value }}" @if (in_array($value, $selected)) selected @endif>
                {{ $value }}
            </option>
        @endforeach
    </select>
</div>

@once
    {{-- Select2 (somente uma vez) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* === Ajustes visuais do Select2 para o dashboard Titan === */

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .select2-container {
            width: 100% !important;
            max-width: 100%;
        }

        /* remove margens e sombras exageradas */
        .select2-container--default .select2-selection--multiple {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            min-height: 42px;
            padding: 4px 8px;
            box-shadow: none;
            color: #fff;
        }

        /* texto branco para temas escuros */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgba(255, 255, 255, 0.25);
            color: #fff;
            border: none;
            border-radius: 4px;
            margin-top: 5px;
        }

        /* Corrige sobreposição do dropdown (z-index) */
        .select2-container .select2-dropdown {
            z-index: 9999;
        }

        /* Remove o fundo branco no dropdown se o tema for escuro */
        .select2-container--default .select2-results>.select2-results__options {
            background-color: rgba(20, 20, 20, 0.95);
            color: #fff;
        }
    </style>
@endonce

@push('scripts')
    <script>
        $(function() {
            const el = $('#{{ $id }}');
            el.select2({
                placeholder: '{{ $placeholder }}',
                allowClear: true,
                width: '100%'
            });

            // contador dinâmico de selecionados (exemplo opcional)
            el.on('change', function() {
                const count = $(this).val()?.length || 0;
                if (count > 0) {
                    $(this).next('.select2').find('.select2-selection__rendered')
                        .attr('title', `${count} selecionado${count > 1 ? 's' : ''}`);
                }
            });
        });
    </script>
@endpush