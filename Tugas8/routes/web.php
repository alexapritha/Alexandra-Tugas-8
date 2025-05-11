<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return redirect('/peminjaman');
});

Route::prefix('peminjaman')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index']);
    Route::get('/data', [PeminjamanController::class, 'getData']);
    Route::post('/store', [PeminjamanController::class, 'store']);
    Route::get('/show/{id}', [PeminjamanController::class, 'show']);
    Route::post('/update', [PeminjamanController::class, 'update']);
    Route::post('/destroy', [PeminjamanController::class, 'destroy']);
});
