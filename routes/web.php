<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'title'])->name('title');
Route::post('/start', [GameController::class, 'start'])->name('start');

Route::get('/home', [GameController::class, 'home'])->name('home');
