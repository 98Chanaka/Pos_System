<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ItemController;

// Main Routes
Route::get('/', function () {
    return view('AdminDashboard');
})->name('Admin.dashboard');

Route::get('/pos-dashboard', function () {
    return view('posDashboard');
})->name('pos.dashboard');


Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/getItems', [ItemController::class, 'getItems'])->name('items.getItems');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');


