<?php

use App\Http\Controllers\Api\IuranController;
use Illuminate\Support\Facades\Route;

Route::apiResource('iuran', IuranController::class);
Route::get('iuran/tunggakan/{tahun}', [IuranController::class, 'tunggakan']);
