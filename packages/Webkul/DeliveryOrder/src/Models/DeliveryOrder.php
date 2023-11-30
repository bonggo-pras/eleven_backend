<?php

namespace Webkul\DeliveryOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\DeliveryOrder\Contracts\DeliveryOrder as DeliveryOrderContract;

class DeliveryOrder extends Model implements DeliveryOrderContract
{
    public $timestamps = true;
    
    protected $table = 'delivery_orders';

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }
}