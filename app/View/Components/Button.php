<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public string $classes;

    private const VARIANTS = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'danger'  => 'bg-red-600 hover:bg-red-700 text-white',
    ];

    public function __construct(
        public readonly string $variant = 'primary',
        public readonly ?string $href = null,
    ) {
        $this->classes = self::VARIANTS[$variant] ?? self::VARIANTS['primary'];
    }

    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
