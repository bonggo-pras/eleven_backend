<?php

namespace Webkul\CustomerReward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'rewards';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
