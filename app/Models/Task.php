<?php

namespace App\Models;

use App\Concerns\HasOverdueScope;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'status', 'due_date'])]
class Task extends Model
{
    use HasFactory, HasOverdueScope;

    protected $casts = [
        'due_date' => 'date',
    ];
}
