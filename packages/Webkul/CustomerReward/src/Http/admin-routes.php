<?php

Route::group([
        'prefix'        => 'admin/customerreward',
        'middleware'    => ['web', 'admin']
    ], function () {

        Route::get('', 'Webkul\CustomerReward\Http\Controllers\Admin\CustomerRewardController@index')->defaults('_config', [
            'view' => 'customerreward::admin.index',
        ])->name('admin.customerreward.index');

});