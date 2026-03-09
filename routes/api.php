<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemoController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/memo', [MemoController::class, 'apiIndex']);
    Route::post('/memo', [MemoController::class, 'apiStore']);
    Route::get('/memo/{memo}', [MemoController::class, 'apiShow']);
    Route::put('/memo/{memo}', [MemoController::class, 'apiUpdate']);
    Route::delete('/memo/{memo}', [MemoController::class, 'apiDestroy']);
});
