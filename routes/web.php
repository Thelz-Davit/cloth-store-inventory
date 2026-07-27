<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\TransactionHistoryController; 
use App\Http\Controllers\InventoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Route Guest (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// 2. Route Auth (Akses Utama Aplikasi)
Route::middleware('auth')->group(function () {

    // Dashboard Home
    Route::get('/', [HomeController::class, 'index'])
        ->name('home')
        ->middleware('role_name:Superadmin,Kepala Gudang,Tim Produksi,Staff Gudang,Tim Penjualan');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- RESOURCE ROUTES UTAMA ---
    Route::resource('outbounds', OutboundController::class);
    Route::resource('inbounds', InboundController::class);
    Route::resource('bundlings', BundleController::class);
    
    // Master Data Utama (Index, Store, dll)
    Route::resource('masterdata', MasterDataController::class)
        ->names('master-data')
        ->parameters(['masterdata' => 'id']);
    
    // Rute Spesifik untuk Material, Size, & Color yang di-handle oleh MasterDataController
    // Materials
    Route::get('/materials/create', [MasterDataController::class, 'createMaterial'])->name('materials.create');
    Route::post('/materials', [MasterDataController::class, 'storeMaterial'])->name('materials.store');
    Route::get('/materials/{material}/edit', [MasterDataController::class, 'editMaterial'])->name('materials.edit');
    Route::put('/materials/{material}', [MasterDataController::class, 'updateMaterial'])->name('materials.update');
    Route::delete('/materials/{material}', [MasterDataController::class, 'destroyMaterial'])->name('materials.destroy');

    // Sizes
    Route::get('/sizes/create', [MasterDataController::class, 'createSize'])->name('sizes.create');
    Route::post('/sizes', [MasterDataController::class, 'storeSize'])->name('sizes.store');
    Route::get('/sizes/{size}/edit', [MasterDataController::class, 'editSize'])->name('sizes.edit');
    Route::put('/sizes/{size}', [MasterDataController::class, 'updateSize'])->name('sizes.update');
    Route::delete('/sizes/{size}', [MasterDataController::class, 'destroySize'])->name('sizes.destroy');

    // Colors
    Route::get('/colors/create', [MasterDataController::class, 'createColor'])->name('colors.create');
    Route::post('/colors', [MasterDataController::class, 'storeColor'])->name('colors.store');
    Route::get('/colors/{color}/edit', [MasterDataController::class, 'editColor'])->name('colors.edit');
    Route::put('/colors/{color}', [MasterDataController::class, 'updateColor'])->name('colors.update');
    Route::delete('/colors/{color}', [MasterDataController::class, 'destroyColor'])->name('colors.destroy');

    // Products Resource
    Route::resource('products', ProductController::class);

    // --- ROUTE TAMBAHAN UNTUK LINK SIDEBAR ---
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/transaction-history', [TransactionHistoryController::class, 'index'])->name('transaction-history.index');
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/rfid', [RfidController::class, 'index'])->name('rfid.index');

    // --- GROUP OUTBOUND KHUSUS ROLE ---
    Route::middleware('role_name:Superadmin,Tim Penjualan,Staff Gudang')->group(function () {
        Route::get('/outbound', [OutboundController::class, 'index'])->name('outbound.index');
        Route::get('/outbound/create', [OutboundController::class, 'create'])->name('outbound.create');
        Route::post('/outbound/store', [OutboundController::class, 'store'])->name('outbound.store');
        Route::get('/outbound/{outbound}/edit', [OutboundController::class, 'edit'])->name('outbound.edit');
        Route::put('/outbound/{outbound}', [OutboundController::class, 'update'])->name('outbound.update');
        Route::delete('/outbound/{outbound}', [OutboundController::class, 'destroy'])->name('outbound.destroy');

        Route::get('/get-bundle-details/{id}', [OutboundController::class, 'getBundleDetails'])->name('bundle.details');

        Route::get('/outbound/order/{orderId}', [OutboundController::class, 'process'])->name('outbound.process');
        Route::post('/outbound/order/{orderId}/scan', [OutboundController::class, 'scan'])->name('outbound.scan');
        Route::post('/outbound/order/{orderId}/commit', [OutboundController::class, 'commit'])->name('outbound.commit');

        Route::get('/outbound/history', [OutboundController::class, 'history'])->name('outbound.history');
        Route::get('/outbound/history/{id}', [OutboundController::class, 'show'])->name('outbound.show');
    });

});