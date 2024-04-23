<?php

Route::group([
        'prefix'     => 'deliveryorder',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'Webkul\DeliveryOrder\Http\Controllers\Shop\DeliveryOrderController@index')->defaults('_config', [
            'view' => 'deliveryorder::shop.index',
        ])->name('shop.deliveryorder.index');

});