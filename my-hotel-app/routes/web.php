<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\InventoryViewController;
use App\Http\Controllers\LocationController;
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
    Route::resource('inventory/items', ItemController::class)->names('inventory.items');
    Route::resource('inventory/categories', CategoryController::class)->names('inventory.categories');
    Route::resource('inventory/suppliers', SupplierController::class)->names('inventory.suppliers');
    Route::resource('inventory/purchase-orders', PurchaseOrderController::class)->names('inventory.purchase_orders');
    
    // Custom routes for purchase order status updates
    Route::patch('inventory/purchase-orders/{purchaseOrder}/deliver', [PurchaseOrderController::class, 'markAsDelivered'])
        ->name('inventory.purchase_orders.deliver');
    Route::patch('inventory/purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'markAsCanceled'])
        ->name('inventory.purchase_orders.cancel');
        
    // Inventory view routes (read-only)
    Route::get('inventory/stock', [InventoryViewController::class, 'index'])->name('inventory.view');
    Route::get('inventory/stock/category/{category}', [InventoryViewController::class, 'byCategory'])->name('inventory.view.category');
    
    // Location management routes
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/create-batch', [LocationController::class, 'createBatch'])->name('locations.create-batch');
    Route::post('/locations/batch', [LocationController::class, 'storeBatch'])->name('locations.store-batch');
    Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
});

require __DIR__.'/auth.php';
