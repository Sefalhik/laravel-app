<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public function __construct(public readonly string $title = '')
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.modal');
    }
}
