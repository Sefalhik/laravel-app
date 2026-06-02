<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function index(): View
    {
        $stats = Cache::remember('stats', 3600, fn () => [
            'users' => User::count(),
            'tasks' => Task::count(),
        ]);

        return view('stats.index', compact('stats'));
    }

    public function flush(): RedirectResponse
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);

        Cache::forget('stats');

        return redirect()->route('dashboard')->with('success', 'Cache vidé !');
    }
}
