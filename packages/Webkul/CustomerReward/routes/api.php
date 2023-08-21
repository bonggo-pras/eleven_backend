<?php

Route::get('get-customer-points', 'CustomerRewardController@getCustomerPoints');

Route::get('set-customer-point', 'CustomerRewardController@setCustomerPoint');

Route::get('get-rewards', 'RewardController@getRewards');

Route::get('get-rewards/{id}', 'RewardController@detailReward');

Route::post('claim-reward', 'RewardController@claimReward');