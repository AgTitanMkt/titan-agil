<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Multiselect extends Component
{
    public string $name;
    public string $label;
    public array $options;
    public array $selected;

    public function __construct(string $name, string $label = '', array $options = [], array $selected = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->selected = $selected;
    }

    public function render()
    {
        return view('components.multiselect');
    }
}
