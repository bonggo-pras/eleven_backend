<?php

namespace Webkul\CustomerReward\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Konekt\Concord\Proxies\ModelProxy;

class RewardClaimHistory extends ModelProxy
{
    use HasFactory;

    protected $table = 'reward_claim_hitories';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}