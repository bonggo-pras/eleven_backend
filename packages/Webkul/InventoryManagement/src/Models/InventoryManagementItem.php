<?php

namespace Webkul\InventoryManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\InventoryManagement\Contracts\InventoryManagementItem as ContractsInventoryManagementItem;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

class InventoryManagementItem extends Model implements ContractsInventoryManagementItem
{
    use HasFactory;

    public $timestamps = true;
    
    protected $table = 'inventory_management_items';

    protected $fillable = ['inventory_management_id', 'product_id', 'stock']; 

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the InventoryManagement record associated with the InventoryManagement item.
     */
    public function inventoryManagement(): BelongsTo
    {
        return $this->belongsTo(InventoryManagement::class);
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