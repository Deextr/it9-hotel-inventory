<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\InventoryViewController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\ItemTransferController;
use App\Http\Controllers\ItemPulloutController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    
    // Inventory Reports
    Route::get('inventory/reports', [InventoryReportController::class, 'index'])->name('inventory.reports.index');
    Route::get('inventory/reports/export', [InventoryReportController::class, 'export'])->name('inventory.reports.export');
    
    // Toggle status routes
    Route::patch('inventory/items/{item}/toggle-status', [ItemController::class, 'toggleStatus'])->name('inventory.items.toggle-status');
    Route::patch('inventory/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('inventory.categories.toggle-status');
    Route::patch('inventory/suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('inventory.suppliers.toggle-status');
    
    // Custom routes for purchase order status updates
    Route::patch('inventory/purchase-orders/{purchaseOrder}/deliver', [PurchaseOrderController::class, 'markAsDelivered'])
        ->name('inventory.purchase_orders.deliver');
    Route::patch('inventory/purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'markAsCanceled'])
        ->name('inventory.purchase_orders.cancel');
        
    // Inventory view routes (read-only)
    Route::get('inventory/stock', [InventoryViewController::class, 'index'])->name('inventory.view');
    Route::get('inventory/stock/category/{category}', [InventoryViewController::class, 'byCategory'])->name('inventory.view.category');
    
    // Stock Out routes
    Route::get('inventory/stock/out', [StockOutController::class, 'index'])->name('inventory.stock.out');
    Route::post('inventory/stock/out', [StockOutController::class, 'store'])->name('inventory.stock.out.store');
    Route::get('inventory/stock/bulk-out', [StockOutController::class, 'bulkIndex'])->name('inventory.stock.bulk-out');
    Route::post('inventory/stock/bulk-out', [StockOutController::class, 'bulkStore'])->name('inventory.stock.bulk-out.store');
    
    // Item Transfer routes
    Route::get('inventory/transfers', [ItemTransferController::class, 'index'])->name('inventory.transfers.index');
    Route::get('inventory/transfers/{location}', [ItemTransferController::class, 'create'])->name('inventory.transfers.create');
    Route::post('inventory/transfers', [ItemTransferController::class, 'store'])->name('inventory.transfers.store');
    
    // Item Pullout routes
    Route::get('inventory/pullouts', [ItemPulloutController::class, 'index'])->name('inventory.pullouts.index');
    Route::get('inventory/pullouts/create', [ItemPulloutController::class, 'create'])->name('inventory.pullouts.create');
    Route::post('inventory/pullouts', [ItemPulloutController::class, 'store'])->name('inventory.pullouts.store');
    Route::get('/api/locations/{location}/available-items', [ItemPulloutController::class, 'getLocationItems']);
    
    // Location management routes
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/create-batch', [LocationController::class, 'createBatch'])->name('locations.create-batch');
    Route::post('/locations/batch', [LocationController::class, 'storeBatch'])->name('locations.store-batch');
    Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
    Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
    
    // Audit log routes
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
});

// API routes
Route::get('/api/locations/rooms', function (Request $request) {
    $floor = $request->query('floor');
    $area = $request->query('area');
    
    if (!$floor || !$area) {
        return response()->json(['rooms' => []]);
    }
    
    $rooms = \App\Models\Location::where('floor_number', $floor)
        ->where('area_type', $area)
        ->where('is_active', true)
        ->whereNotNull('room_number')
        ->pluck('room_number')
        ->sort()
        ->values()
        ->toArray();
    
    return response()->json(['rooms' => $rooms]);
});

Route::get('/api/locations', function (Request $request) {
    $floor = $request->query('floor');
    $area = $request->query('area');
    $roomStart = $request->query('room_start');
    $roomEnd = $request->query('room_end');
    
    if (!$floor || !$area) {
        return response()->json(['locations' => []]);
    }
    
    $query = \App\Models\Location::where('floor_number', $floor)
        ->where('area_type', $area)
        ->where('is_active', true);
    
    if ($roomStart !== null && $roomEnd !== null) {
        // Convert to integers for proper comparison
        $minRoom = intval(min($roomStart, $roomEnd));
        $maxRoom = intval(max($roomStart, $roomEnd));
        
        // Use whereRaw for proper numeric comparison
        $query->whereRaw('CAST(room_number AS UNSIGNED) >= ?', [$minRoom])
              ->whereRaw('CAST(room_number AS UNSIGNED) <= ?', [$maxRoom]);
    } else {
        $query->whereNull('room_number');
    }
    
    $locations = $query->orderByRaw('CAST(room_number AS UNSIGNED)')
        ->get(['id', 'name', 'room_number', 'floor_number', 'area_type']);
    
    return response()->json(['locations' => $locations]);
});

Route::get('/api/locations/{location}/items', [ItemTransferController::class, 'getLocationItems']);

require __DIR__.'/auth.php';
