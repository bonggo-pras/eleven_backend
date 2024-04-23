<?php

namespace Webkul\InventoryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\InventoryManagement\Contracts\InventoryManagement as ContractsInventoryManagement;

class InventoryManagement extends Model implements ContractsInventoryManagement
{
    public $timestamps = true;
    
    protected $table = 'inventory_management';

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InventoryManagementItem::class);
    }
}