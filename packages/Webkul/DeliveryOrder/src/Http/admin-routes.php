<?php

Route::group([
    'prefix'        => 'admin/deliveryorder',
    'middleware'    => ['web', 'admin']
], function () {

    Route::get('', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@indexCustom')->defaults('_config', [
        'view' => 'deliveryorder::admin.index-custom',
    ])->name('admin.deliveryorder.index');

    Route::post('/cetak-do', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@cetakDo')->defaults('_config', [
        'view' => 'deliveryorder::admin.cetak-do',
    ])->name('admin.deliveryorder.cetak-do');

    Route::post('/index-json', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@indexJson')->name('admin.deliveryorder.indexJson');

    Route::get('/create', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@create')->defaults('_config', [
        'view' => 'deliveryorder::admin.create',
    ])->name('admin.deliveryorder.create');

    Route::post('/create', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@store')->name('admin.deliveryorder.store');

    Route::get('/view/{id}', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@view')->defaults('_config', [
        'view' => 'deliveryorder::admin.view',
    ])->name('admin.deliveryorder.view');

    Route::get('/edit/{id}', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@edit')->defaults('_config', [
        'view' => 'deliveryorder::admin.edit',
    ])->name('admin.deliveryorder.edit');

    Route::post('/update/{id}', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@update')->defaults('_config', [
        'redirect' => 'deliveryorder::admin.update',
    ])->name('admin.deliveryorder.update');

    Route::get('/print/{id}', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@print')->defaults('_config', [
        'view' => 'deliveryorder::admin.print',
    ])->name('admin.deliveryorder.print');

    Route::post('/print/delete/{id}', 'Webkul\DeliveryOrder\Http\Controllers\Admin\DeliveryOrderController@destroy')->defaults('_config', [
        'redirect' => 'admin.deliveryorder.index',
    ])->name('admin.deliveryorder.delete');
});
