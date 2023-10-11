<?php

namespace Webkul\CustomerReward\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\CustomerReward\Models\PointHistory::class,
    ];
}