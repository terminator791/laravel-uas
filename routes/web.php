<?php

use App\Http\Controllers\IuranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web'])->group(function () {
    Route::resource('iuran', IuranController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('iuran/tunggakan', [IuranController::class, 'tunggakan'])->name('iuran.tunggakan');
});
