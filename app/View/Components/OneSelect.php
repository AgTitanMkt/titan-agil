<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OneSelect extends Component
{
    public string $name;
    public string $label;
    public array $options;
    public ?string $selected;
    public string $placeholder;

    public function __construct(
        string $name,
        string $label = '',
        array $options = [],
        ?string $selected = null,
        string $placeholder = 'Selecione uma opção'
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->selected = $selected;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.one-select');
    }
}
