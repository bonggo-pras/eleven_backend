<?php

Route::group([
    'prefix'        => 'admin/customerreward',
    'middleware'    => ['web', 'admin']
], function () {

    Route::get('', 'Webkul\CustomerReward\Http\Controllers\Admin\CustomerRewardController@index')->defaults('_config', [
        'view' => 'customerreward::admin.index',
    ])->name('admin.customerreward.index');
});

Route::group([
    'prefix'        => 'admin/reward',
    'middleware'    => ['web', 'admin']
], function () {

    Route::get('', 'Webkul\CustomerReward\Http\Controllers\Admin\RewardController@index')->defaults('_config', [
        'view' => 'customerreward::admin.rewards.index',
    ])->name('admin.rewards.index');

    Route::get('/add', 'Webkul\CustomerReward\Http\Controllers\Admin\RewardController@add')->defaults('_config', [
        'view' => 'customerreward::admin.rewards.add',
    ])->name('admin.rewards.add');
});
