<?php

Route::group([
        'prefix'     => 'customerreward',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'Webkul\CustomerReward\Http\Controllers\Shop\CustomerRewardController@index')->defaults('_config', [
            'view' => 'customerreward::shop.index',
        ])->name('shop.customerreward.index');

});