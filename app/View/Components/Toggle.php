<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Toggle extends Component
{
    public $active;
    public $label;

    public function __construct($wireModel = null, $label = null)
    {
        $this->active = $wireModel;
        $this->label = $label;
    }

    public function render()
    {
        return view('components.toggle');
    }
}
