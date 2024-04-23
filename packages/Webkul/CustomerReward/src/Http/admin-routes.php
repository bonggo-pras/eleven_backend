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

    Route::get('/create', 'Webkul\CustomerReward\Http\Controllers\Admin\RewardController@create')->defaults('_config', [
        'view' => 'customerreward::admin.rewards.create',
    ])->name('admin.rewards.create');

    Route::post('/create', 'Webkul\CustomerReward\Http\Controllers\Admin\RewardController@store')->defaults('_config', [
        'redirect' => 'admin.rewards.index',
    ])->name('admin.rewards.store');

    Route::get('/edit/{id}', 'Webkul\CustomerReward\Http\Controllers\Admin\RewardController@edit')->defaults('_config', [
        'view' => 'customerreward::admin.rewards.edit',
    ])->name('admin.rewards.edit');

    Route::post('/edit/{id}', 'Webkul\CustomerReward\Http\Controllers\Admin\RewardController@update')->defaults('_config', [
        'redirect' => 'admin.rewards.index',
    ])->name('admin.rewards.update');

    Route::post('/delete/{id}', 'Webkul\CustomerReward\Http\Controllers\Admin\RewardController@destroy')->defaults('_config', [
        'redirect' => 'admin.rewards.index',
    ])->name('admin.rewards.delete');
});

Route::group([
    'prefix'        => 'admin/claim-reward',
    'middleware'    => ['web', 'admin']
], function () {
    Route::get('', 'Webkul\CustomerReward\Http\Controllers\Admin\CustomerClaimRewardController@index')->defaults('_config', [
        'view' => 'customerreward::admin.claim-reward.index',
    ])->name('admin.claim-reward.index');

    Route::get('/show/{id}', 'Webkul\CustomerReward\Http\Controllers\Admin\CustomerClaimRewardController@show')->defaults('_config', [
        'view' => 'customerreward::admin.claim-reward.view',
    ])->name('admin.claim-reward.show');

    Route::post('/save-shipment/{id}', 'Webkul\CustomerReward\Http\Controllers\Admin\CustomerClaimRewardController@saveShipment')->defaults('_config', [
        'redirect' => 'admin.claim-reward.index',
    ])->name('admin.claim-reward.claim-reward');

    Route::post('/finish-reward/{id}', 'Webkul\CustomerReward\Http\Controllers\Admin\CustomerClaimRewardController@finishReward')->defaults('_config', [
        'redirect' => 'admin.claim-reward.index',
    ])->name('admin.claim-reward.finish-reward');
});
