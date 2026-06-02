<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/components-demo', fn() => view('components-demo'));

Route::get('/dashboard', fn() => view('dashboard'))->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
});
