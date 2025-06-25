<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ItemController;

// Main Routes
Route::get('/', function () {
    return view('AdminDashboard');
})->name('Admin.dashboard');

// Route::get('/pos-dashboard', function () {
//     return view('items.posDashboard');
// })->name('pos.dashboard');


Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/getItems', [ItemController::class, 'getItems'])->name('items.getItems');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
Route::post('/items/bulk-upload', [ItemController::class, 'bulkUpload'])->name('items.bulk-upload');


Route::get('/pos-dashboard', [PosController::class, 'index'])->name('pos.index');
Route::get('/search-items', [PosController::class, 'searchItems'])->name('pos.searchItems');
Route::get('/get-item', [PosController::class, 'getItemByCode'])->name('pos.getItemByCode');

