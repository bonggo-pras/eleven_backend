<?php

namespace Webkul\Notification\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'checkout.order.save.after' => [
            'Webkul\Notification\Listeners\Order@createOrder'
        ],
        'sales.order.update-status.after' => [
            'Webkul\Notification\Listeners\Order@updateOrder'
        ],
    ];
}
