@props([
    'name' => 'date',
    'from' => null,
    'to' => null,
    'label' => 'Período',
])

@php
    function formatBR($d)
    {
        return $d ? \Carbon\Carbon::parse($d)->format('d/m/Y') : '';
    }

    // Valor exibido no input (formato bonito)
    $displayRange = $from && $to ? formatBR($from) . ' — ' . formatBR($to) : '';
@endphp

{{-- Carregar assets somente quando o componente for usado --}}
@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            .flatpickr-calendar {
                z-index: 999999 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endpush
@endonce


<div class="date-range-container">

    <label class="date-range-label">{{ $label }}</label>

    <input id="date" type="text" class="date-range-input" placeholder="Selecione o período"
        value="{{ $displayRange }}" autocomplete="off">

    {{-- Hidden fields usados pelo backend --}}
    <input type="hidden" name="{{ $name }}_from" id="{{ $name }}_from" value="{{ $from }}">
    <input type="hidden" name="{{ $name }}_to" id="{{ $name }}_to" value="{{ $to }}">

</div>


@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const input = document.querySelector("#date");
            const hiddenFrom = document.querySelector("#{{ $name }}_from");
            const hiddenTo = document.querySelector("#{{ $name }}_to");

            flatpickr(input, {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: {!! json_encode([$from, $to]) !!},
                locale: {
                    rangeSeparator: " to ",
                    firstDayOfWeek: 1
                },
                onChange: function(selectedDates, dateStr) {
                    const [start, end] = dateStr.split(" to ");

                    // preencher hidden fields (backend)
                    hiddenFrom.value = start || '';
                    hiddenTo.value = end || '';

                    // atualizar input visual (formato BR)
                    if (start && end) {
                        const startBR = new Date(start).toLocaleDateString('pt-BR');
                        const endBR = new Date(end).toLocaleDateString('pt-BR');
                        input.value = `${startBR} — ${endBR}`;
                    }
                },
            });
        });
    </script>
@endpush


<style>

    .date-range-label {
        font-size: 0.85rem;
        opacity: .85;
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .date-range-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 10px 12px;
        border-radius: 8px;
        color: #fff;
        cursor: pointer;
    }
</style>
