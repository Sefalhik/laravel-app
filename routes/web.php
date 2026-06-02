<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/components-demo', fn() => view('components-demo'));

Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
Route::post('/cache/flush', [StatsController::class, 'flush'])->name('cache.flush')->middleware('auth');

Route::get('/dashboard', fn() => view('dashboard'))->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
    Route::get('/preferences', [PreferenceController::class, 'index'])->name('preferences.index');
    Route::post('/preferences', [PreferenceController::class, 'store'])->name('preferences.store');
});
