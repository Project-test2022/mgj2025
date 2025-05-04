<?php

use App\Http\Controllers\DebugController;
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
Route::post('/event/{id}', [GameController::class, 'selectEvent'])->name('event.select');
Route::get('/event/{id}/result', [GameController::class, 'eventResult'])->name('event.result');

Route::get('/face/{id}', [GameController::class, 'face'])->name('face');
Route::get('/background/{id}', [GameController::class, 'background'])->name('background');

Route::get('/result/{id}', [GameController::class, 'result'])->name('result');

Route::get('/error/{id}', [GameController::class, 'error'])->name('error');

if (config('app.env') === 'local') {
    Route::get('/debug/title', [DebugController::class, 'title']);
    Route::get('/debug/home', [DebugController::class, 'home']);
    Route::get('/debug/select', [DebugController::class, 'select']);
    Route::get('/debug/event', [DebugController::class, 'event']);
    Route::get('/debug/end', [DebugController::class, 'end']);
}
