<?php

namespace Webkul\DeliveryOrder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\DeliveryOrder\Contracts\DeliveryOrderItem as DeliveryOrderItemContract;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

class DeliveryOrderItem extends Model implements DeliveryOrderItemContract
{
    use HasFactory;

    public $timestamps = true;
    
    protected $table = 'delivery_order_items';

    protected $fillable = ['delivery_order_id', 'product_id', 'stock']; 

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the DeliveryOrder record associated with the DeliveryOrder item.
     */
    public function deliveryOrder(): BelongsTo
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function productFlat(): HasOne
    {
        return $this->hasOne(ProductFlat::class, 'product_id', 'product_id');
    }
}