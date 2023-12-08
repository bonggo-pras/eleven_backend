<?php

Route::group([
        'prefix'     => 'inventorymanagement',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'Webkul\InventoryManagement\Http\Controllers\Shop\InventoryManagementController@index')->defaults('_config', [
            'view' => 'inventorymanagement::shop.index',
        ])->name('shop.inventorymanagement.index');

});