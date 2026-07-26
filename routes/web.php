<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\ColorUnitController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\TransactionHistory;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\SalesOrderController;
use Illuminate\Support\Facades\Route;


// Route::get('/logout', function () {
//     return redirect()->route('login');
// });

// Route::middleware('guest')->group(function () {
//     Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
//     Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// });

// Route::middleware('auth')->group(function () {

//     Route::get('/', [HomeController::class, 'index'])->name('home');

//     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//     // Account
//     Route::get('/account', [AccountController::class, 'index'])->name('account.index');
//     Route::get('/account/create', [AccountController::class, 'create'])->name('account.create');
//     Route::get('/account/{id}/edit', [AccountController::class, 'edit'])->name('account.edit');
//     Route::post('/account', [AccountController::class, 'store'])->name('account.store');
//     Route::post('/account/delete', [AccountController::class, 'delete'])->name('account.delete');
//     Route::put('/account/{id}', [AccountController::class, 'update'])->name('account.update');

//     // Role
//     Route::get('/role', [RoleController::class, 'index'])->name('role.index');
//     Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
//     Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
//     Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
//     Route::post('/role/delete', [RoleController::class, 'delete'])->name('role.delete');
//     Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');

//     // Units
//     Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');
//     Route::get('/unit/create', [UnitController::class, 'create'])->name('unit.create');
//     Route::post('/unit/store', [UnitController::class, 'store'])->name('unit.store');
//     Route::get('/unit/{id}/edit', [UnitController::class, 'edit'])->name('unit.edit');
//     Route::post('/unit/delete', [UnitController::class, 'delete'])->name('unit.delete');
//     Route::put('/unit/{id}', [UnitController::class, 'update'])->name('unit.update');

//     // Product
//     Route::get('/product', [ProductController::class, 'product'])->name('product.index');
//     Route::get('/product/history', [ProductController::class, 'history'])->name('product.history');
//     Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
//     Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
//     Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
//     Route::post('/product/delete', [ProductController::class, 'delete'])->name('product.delete');
//     Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');

//     // RFID
//     Route::get('/rfid-tags', [RfidController::class, 'index'])->name('rfid-tags.index');
//     Route::get('/rfid-tags/create', [RfidController::class, 'create'])->name('rfid-tags.create');
//     Route::post('/rfid-tags/store', [RfidController::class, 'store'])->name('rfid-tags.store');
//     Route::get('/rfid-tags/{id}/edit', [RfidController::class, 'edit'])->name('rfid-tags.edit');
//     Route::post('/rfid-tags/delete', [RfidController::class, 'delete'])->name('rfid-tags.delete');
//     Route::put('/rfid-tags/{id}', [RfidController::class, 'update'])->name('rfid-tags.update');

//     // Inbound
//     Route::get('/inbound', [InboundController::class, 'index'])->name('inbound.index');
//     Route::post('/inbound/scan', [InboundController::class, 'scan'])->name('inbound.scan');
//     Route::post('/inbound/commit', [InboundController::class, 'commit'])->name('inbound.commit');
//     Route::get('/inbound/history', [InboundController::class, 'history'])->name('inbound.history');

//     // Inbound draft
//     Route::post('/inbound/draft/remove', [InboundController::class, 'removeDraft'])->name('inbound.draft.remove');
//     Route::post('/inbound/draft/reset', [InboundController::class, 'resetDraft'])->name('inbound.draft.reset');

//     // Outbound
//     Route::get('/outbound', [OutboundController::class, 'index'])->name('outbound.index');

//     Route::get('/outbound/order/{orderId}', [OutboundController::class, 'process'])->name('outbound.process');
//     Route::post('/outbound/order/{orderId}/scan', [OutboundController::class, 'scan'])->name('outbound.scan');
//     Route::post('/outbound/order/{orderId}/commit', [OutboundController::class, 'commit'])->name('outbound.commit');

//     Route::get('/outbound/history', [OutboundController::class, 'history'])->name('outbound.history');
//     Route::get('/outbound/history/{id}', [OutboundController::class, 'show'])->name('outbound.show');

//     // Sales Order
//     Route::get('/sales-orders', [SalesOrderController::class, 'index'])->name('sales-orders.index');
//     Route::get('/sales-orders/create', [SalesOrderController::class, 'create'])->name('sales-orders.create');
//     Route::post('/sales-orders/store', [SalesOrderController::class, 'store'])->name('sales-orders.store');

//     Route::get('/sales-orders/{id}', [SalesOrderController::class, 'show'])->name('sales-orders.show');
//     Route::post('/sales-orders/{id}/items', [SalesOrderController::class, 'addItem'])->name('sales-orders.items.add');
//     Route::post('/sales-orders/items/{itemId}/delete', [SalesOrderController::class, 'deleteItem'])->name('sales-orders.items.delete');
//     Route::post('/sales-orders/delete', [SalesOrderController::class, 'deleteOrders'])->name('sales-orders.delete');
// });


// sudah pake middle ware
Route::get('/logout', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {

    Route::get('/', [HomeController::class, 'index'])
        ->name('home')
        ->middleware('role_name:Superadmin,Kepala Gudang,Tim Produksi,Staff Gudang,Tim Penjualan');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //CRUD'S
    Route::resource('materials', MaterialController::class);
    Route::resource('outbounds', OutboundController::class);
    Route::resource('inbounds', InboundController::class);
    Route::resource('bundlings', BundleController::class);
    Route::resource('masterdata', MasterDataController::class);


    Route::resource('products', ProductController::class)
        ->except(['show']);

    Route::post(
        '/outbounds/draft',
        [OutboundController::class, 'storeDraft']
    )->name('outbounds.storeDraft');

    Route::get('/inventories', [ProductController::class, 'indexInventory'])->name('inventory.index');
    Route::get('/transaction-history', [TransactionHistoryController::class, 'index'])
        ->name('transaction-history.index');



    Route::prefix('master-data')->name('master-data.')->group(function () {

        // Index
        Route::get('/', [MasterDataController::class, 'index'])->name('index');

        /*
        |--------------------------------------------------------------------------
        | Materials
        |--------------------------------------------------------------------------
        */
        Route::get('/materials/create', [MasterDataController::class, 'createMaterial'])->name('materials.create');
        Route::post('/materials', [MasterDataController::class, 'storeMaterial'])->name('materials.store');
        Route::get('/materials/{material}/edit', [MasterDataController::class, 'editMaterial'])->name('materials.edit');
        Route::put('/materials/{material}', [MasterDataController::class, 'updateMaterial'])->name('materials.update');
        Route::delete('/materials/{material}', [MasterDataController::class, 'destroyMaterial'])->name('materials.destroy');

        /*
        |--------------------------------------------------------------------------
        | Colors
        |--------------------------------------------------------------------------
        */
        Route::get('/colors/create', [MasterDataController::class, 'createColor'])->name('colors.create');
        Route::post('/colors', [MasterDataController::class, 'storeColor'])->name('colors.store');
        Route::get('/colors/{color}/edit', [MasterDataController::class, 'editColor'])->name('colors.edit');
        Route::put('/colors/{color}', [MasterDataController::class, 'updateColor'])->name('colors.update');
        Route::delete('/colors/{color}', [MasterDataController::class, 'destroyColor'])->name('colors.destroy');

        /*
        |--------------------------------------------------------------------------
        | Sizes
        |--------------------------------------------------------------------------
        */
        Route::get('/sizes/create', [MasterDataController::class, 'createSize'])->name('sizes.create');
        Route::post('/sizes', [MasterDataController::class, 'storeSize'])->name('sizes.store');
        Route::get('/sizes/{size}/edit', [MasterDataController::class, 'editSize'])->name('sizes.edit');
        Route::put('/sizes/{size}', [MasterDataController::class, 'updateSize'])->name('sizes.update');
        Route::delete('/sizes/{size}', [MasterDataController::class, 'destroySize'])->name('sizes.destroy');
    });

    Route::middleware('role_name:Superadmin')->group(function () {

        // Account
        Route::get('/account', [AccountController::class, 'index'])->name('account.index');
        Route::get('/account/create', [AccountController::class, 'create'])->name('account.create');
        Route::get('/account/{id}/edit', [AccountController::class, 'edit'])->name('account.edit');
        Route::post('/account', [AccountController::class, 'store'])->name('account.store');
        Route::post('/account/delete', [AccountController::class, 'delete'])->name('account.delete');
        Route::put('/account/{id}', [AccountController::class, 'update'])->name('account.update');





        // Role
        // Route::get('/role', [RoleController::class, 'index'])->name('role.index');
        // Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
        // Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
        // Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
        // Route::post('/role/delete', [RoleController::class, 'delete'])->name('role.delete');
        // Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');

        // // Units
        // Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');
        // Route::get('/unit/create', [UnitController::class, 'create'])->name('unit.create');
        // Route::post('/unit/store', [UnitController::class, 'store'])->name('unit.store');
        // Route::get('/unit/{id}/edit', [UnitController::class, 'edit'])->name('unit.edit');
        // Route::post('/unit/delete', [UnitController::class, 'delete'])->name('unit.delete');
        // Route::put('/unit/{id}', [UnitController::class, 'update'])->name('unit.update');
    });

    // Route::middleware('role_name:Superadmin,Kepala Gudang,Tim Produksi,Staff Gudang,Tim Penjualan')->group(function () {
    //     Route::get('/product', [ProductController::class, 'product'])->name('product.index');
    // });

    // Route::middleware('role_name:Superadmin,Kepala Gudang,Staff Gudang')->group(function () {
    //     Route::get('/product/history', [ProductController::class, 'history'])->name('product.history');
    // });

    // Route::middleware('role_name:Superadmin')->group(function () {
    //     Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    //     Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    //     Route::get('/product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    //     Route::post('/product/delete', [ProductController::class, 'delete'])->name('product.delete');
    //     Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    // });

    // Route::middleware('role_name:Superadmin,Tim Produksi')->group(function () {
    //     Route::get('/rfid-tags', [RfidController::class, 'index'])->name('rfid-tags.index');
    //     Route::get('/rfid-tags/create', [RfidController::class, 'create'])->name('rfid-tags.create');
    //     Route::post('/rfid-tags/store', [RfidController::class, 'store'])->name('rfid-tags.store');
    //     Route::get('/rfid-tags/{id}/edit', [RfidController::class, 'edit'])->name('rfid-tags.edit');
    //     Route::post('/rfid-tags/delete', [RfidController::class, 'delete'])->name('rfid-tags.delete');
    //     Route::put('/rfid-tags/{id}', [RfidController::class, 'update'])->name('rfid-tags.update');
    // });

    // Route::middleware('role_name:Superadmin,Tim Produksi,Staff Gudang')->group(function () {
    //     Route::get('/inbound', [InboundController::class, 'index'])->name('inbound.index');
    //     Route::post('/inbound/scan', [InboundController::class, 'scan'])->name('inbound.scan');
    //     Route::post('/inbound/commit', [InboundController::class, 'commit'])->name('inbound.commit');

    //     Route::post('/inbound/draft/remove', [InboundController::class, 'removeDraft'])->name('inbound.draft.remove');
    //     Route::post('/inbound/draft/reset', [InboundController::class, 'resetDraft'])->name('inbound.draft.reset');
    // });

    // Route::middleware('role_name:Superadmin,Kepala Gudang,Tim Produksi,Staff Gudang')->group(function () {
    //     Route::get('/inbound/history', [InboundController::class, 'history'])->name('inbound.history');
    // });

    // Route::middleware('role_name:Superadmin,Tim Penjualan')->group(function () {
    //     Route::get('/sales-orders', [SalesOrderController::class, 'index'])->name('sales-orders.index');
    //     Route::get('/sales-orders/create', [SalesOrderController::class, 'create'])->name('sales-orders.create');
    //     Route::post('/sales-orders/store', [SalesOrderController::class, 'store'])->name('sales-orders.store');

    //     Route::get('/sales-orders/{id}', [SalesOrderController::class, 'show'])->name('sales-orders.show');
    //     Route::post('/sales-orders/{id}/items', [SalesOrderController::class, 'addItem'])->name('sales-orders.items.add');
    //     Route::post('/sales-orders/items/{itemId}/delete', [SalesOrderController::class, 'deleteItem'])->name('sales-orders.items.delete');

    //     Route::post('/sales-orders/delete', [SalesOrderController::class, 'deleteOrders'])->name('sales-orders.delete');
    // });

    // Route::middleware('role_name:Superadmin,Tim Penjualan,Staff Gudang')->group(function () {
    //     Route::get('/outbound', [OutboundController::class, 'index'])->name('outbound.index');

    //     Route::get('/outbound/order/{orderId}', [OutboundController::class, 'process'])->name('outbound.process');
    //     Route::post('/outbound/order/{orderId}/scan', [OutboundController::class, 'scan'])->name('outbound.scan');
    //     Route::post('/outbound/order/{orderId}/commit', [OutboundController::class, 'commit'])->name('outbound.commit');

    //     Route::get('/outbound/history', [OutboundController::class, 'history'])->name('outbound.history');
    //     Route::get('/outbound/history/{id}', [OutboundController::class, 'show'])->name('outbound.show');
    // });
    // Route::middleware('role_name:Superadmin,Tim Produksi')->group(function () {
    //     Route::get('/rfid-tags', [RfidController::class, 'index'])->name('rfid-tags.index');
    //     Route::get('/rfid-tags/create', [RfidController::class, 'create'])->name('rfid-tags.create');
    //     Route::post('/rfid-tags/store', [RfidController::class, 'store'])->name('rfid-tags.store');
    //     Route::get('/rfid-tags/{id}/edit', [RfidController::class, 'edit'])->name('rfid-tags.edit');
    //     Route::post('/rfid-tags/delete', [RfidController::class, 'delete'])->name('rfid-tags.delete');
    //     Route::put('/rfid-tags/{id}', [RfidController::class, 'update'])->name('rfid-tags.update');
    // });

    // Route::middleware('role_name:Superadmin,Tim Produksi,Staff Gudang')->group(function () {
    //     Route::get('/inbound', [InboundController::class, 'index'])->name('inbound.index');
    //     Route::post('/inbound/scan', [InboundController::class, 'scan'])->name('inbound.scan');
    //     Route::post('/inbound/commit', [InboundController::class, 'commit'])->name('inbound.commit');

    //     Route::post('/inbound/draft/remove', [InboundController::class, 'removeDraft'])->name('inbound.draft.remove');
    //     Route::post('/inbound/draft/reset', [InboundController::class, 'resetDraft'])->name('inbound.draft.reset');
    // });

    // Route::middleware('role_name:Superadmin,Kepala Gudang,Tim Produksi,Staff Gudang')->group(function () {
    //     Route::get('/inbound/history', [InboundController::class, 'history'])->name('inbound.history');
    // });

    // Route::middleware('role_name:Superadmin,Tim Penjualan')->group(function () {
    //     Route::get('/sales-orders', [SalesOrderController::class, 'index'])->name('sales-orders.index');
    //     Route::get('/sales-orders/create', [SalesOrderController::class, 'create'])->name('sales-orders.create');
    //     Route::post('/sales-orders/store', [SalesOrderController::class, 'store'])->name('sales-orders.store');

    //     Route::get('/sales-orders/{id}', [SalesOrderController::class, 'show'])->name('sales-orders.show');
    //     Route::post('/sales-orders/{id}/items', [SalesOrderController::class, 'addItem'])->name('sales-orders.items.add');
    //     Route::post('/sales-orders/items/{itemId}/delete', [SalesOrderController::class, 'deleteItem'])->name('sales-orders.items.delete');

    //     Route::post('/sales-orders/delete', [SalesOrderController::class, 'deleteOrders'])->name('sales-orders.delete');
    // });

    // Route::middleware('role_name:Superadmin,Tim Penjualan,Staff Gudang')->group(function () {
    //     Route::get('/outbound', [OutboundController::class, 'index'])->name('outbound.index');

    //     Route::get('/outbound/order/{orderId}', [OutboundController::class, 'process'])->name('outbound.process');
    //     Route::post('/outbound/order/{orderId}/scan', [OutboundController::class, 'scan'])->name('outbound.scan');
    //     Route::post('/outbound/order/{orderId}/commit', [OutboundController::class, 'commit'])->name('outbound.commit');

    //     Route::get('/outbound/history', [OutboundController::class, 'history'])->name('outbound.history');
    //     Route::get('/outbound/history/{id}', [OutboundController::class, 'show'])->name('outbound.show');
    // });
});
