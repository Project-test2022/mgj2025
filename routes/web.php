<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('title');
});

Route::get('/title', [GameController::class, 'title'])->name('title');
Route::post('/title', [GameController::class, 'start'])->name('start');

Route::get('/home/{id}', [GameController::class, 'home'])->name('home');
