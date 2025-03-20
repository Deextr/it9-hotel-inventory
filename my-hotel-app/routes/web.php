<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('inventory/items', InventoryItemController::class)->names('inventory.items');
    Route::resource('inventory/categories', CategoryController::class)->names('inventory.categories');
});

require __DIR__.'/auth.php';
