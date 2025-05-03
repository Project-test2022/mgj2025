<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('title');
});

Route::get('/title', [GameController::class, 'title'])->name('title');
Route::post('/title', [GameController::class, 'start'])->name('start');

Route::get('/home/{id}', [GameController::class, 'home'])->name('home');
Route::post('/home/{id}', [GameController::class, 'select'])->name('select');

Route::get('/event/{id}', [GameController::class, 'event'])->name('event');
