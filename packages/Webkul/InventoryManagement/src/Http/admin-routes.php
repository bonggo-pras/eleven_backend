<?php

Route::group([
    'prefix'        => 'admin/inventorymanagement',
    'middleware'    => ['web', 'admin']
], function () {
    Route::get('', 'Webkul\InventoryManagement\Http\Controllers\Admin\InventoryManagementController@index')->defaults('_config', [
        'view' => 'inventorymanagement::admin.index',
    ])->name('admin.inventorymanagement.index');

    Route::get('/create', 'Webkul\InventoryManagement\Http\Controllers\Admin\InventoryManagementController@create')->defaults('_config', [
        'view' => 'inventorymanagement::admin.create',
    ])->name('admin.inventorymanagement.create');

    Route::post('/create', 'Webkul\InventoryManagement\Http\Controllers\Admin\InventoryManagementController@store')->name('admin.inventorymanagement.store');

    Route::get('/view/{id}', 'Webkul\InventoryManagement\Http\Controllers\Admin\InventoryManagementController@view')->defaults('_config', [
        'view' => 'inventorymanagement::admin.view',
    ])->name('admin.inventorymanagement.view');

    Route::get('/print/{id}', 'Webkul\InventoryManagement\Http\Controllers\Admin\InventoryManagementController@print')->defaults('_config', [
        'view' => 'inventorymanagement::admin.print',
    ])->name('admin.inventorymanagement.print');

    Route::get('/edit/{id}', 'Webkul\InventoryManagement\Http\Controllers\Admin\InventoryManagementController@edit')->defaults('_config', [
        'view' => 'inventorymanagement::admin.edit',
    ])->name('admin.inventorymanagement.edit');

    Route::post('/update/{id}', 'Webkul\InventoryManagement\Http\Controllers\Admin\InventoryManagementController@update')->defaults('_config', [
        'redirect' => 'admin.inventorymanagement.index',
    ])->name('admin.inventorymanagement.update');

    Route::post('/print/delete/{id}', 'Webkul\InventoryManagement\Http\Controllers\Admin\InventoryManagementController@destroy')->defaults('_config', [
        'redirect' => 'admin.inventorymanagement.index',
    ])->name('admin.inventorymanagement.delete');
});
