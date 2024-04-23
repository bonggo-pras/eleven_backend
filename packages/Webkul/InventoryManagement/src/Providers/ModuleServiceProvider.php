<?php

namespace Webkul\InventoryManagement\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\InventoryManagement\Models\InventoryManagement::class,
        \Webkul\InventoryManagement\Models\InventoryManagementItem::class
    ];
}
