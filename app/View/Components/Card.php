<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(public readonly ?string $title = null)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.card');
    }
}
