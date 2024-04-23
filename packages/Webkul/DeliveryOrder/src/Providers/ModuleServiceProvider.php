<?php

namespace Webkul\DeliveryOrder\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\DeliveryOrder\Models\DeliveryOrder::class,
        \Webkul\DeliveryOrder\Models\DeliveryOrderItem::class,
    ];
}