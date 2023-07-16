<?php

namespace Webkul\CustomerReward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Checkout\Contracts\CartItem as CartItemContract;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;

class PointHistory extends Model implements CartItemContract
{
    use HasFactory;

    protected $table = 'point_histories';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }
}
