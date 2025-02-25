<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Logo extends Component
{
    public $src;
    public $class;

    public function __construct($src = 'img/informatica1.png', $class = '')
    {
        $this->src = $src;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.logo');
    }
}
