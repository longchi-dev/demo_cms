<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Export\ExportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/export/data', [ExportController::class, 'export']);
Route::get('/export/data/status', [ExportController::class, 'getStatus']);