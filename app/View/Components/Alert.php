<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $classes;

    private const COLORS = [
        'success' => 'bg-green-100 border-green-500 text-green-800',
        'error'   => 'bg-red-100 border-red-500 text-red-800',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-800',
    ];

    public function __construct(
        public string $type = 'success',
        public bool $dismissible = false,
    ) {
        $this->classes = self::COLORS[$type] ?? self::COLORS['success'];
    }

    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
