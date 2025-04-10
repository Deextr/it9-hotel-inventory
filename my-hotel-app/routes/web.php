<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\InventoryViewController;
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
});

require __DIR__.'/auth.php';
