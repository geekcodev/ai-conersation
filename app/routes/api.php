<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;


Route::prefix('chat')->group(function () {
    Route::post('/send', [ChatController::class, 'sendMessage']);
    Route::get('/history', [ChatController::class, 'getHistory']);
});
