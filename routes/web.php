<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return view('beranda', ['title' => 'Beranda']);
});

Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/get_data', [ItemController::class, 'getData']);
Route::get('/items/{id}', [ItemController::class, 'show']);
Route::post('/items/form', [ItemController::class, 'storeData']);
Route::put('/items/form/{id}', [ItemController::class, 'updateData']);
Route::delete('/items/form/{id}', [ItemController::class, 'destroyData']);