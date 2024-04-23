<?php

namespace Webkul\CustomerReward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;
use Webkul\CustomerReward\Contracts\PointHistory as PointHistoryContract;

class PointHistory extends Model implements PointHistoryContract
{   
    use HasFactory;

    protected $table = 'point_histories';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer record associated with the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
