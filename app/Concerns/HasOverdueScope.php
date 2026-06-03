<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasOverdueScope
{
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'done');
    }
}
