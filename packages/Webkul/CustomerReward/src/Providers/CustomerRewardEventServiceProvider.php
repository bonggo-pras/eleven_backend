<?php

namespace Webkul\CustomerReward\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class CustomerRewardEventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('payment.midtrans.notification.received', 'Webkul\Payment\Listeners\PaymentReceived@updateOrder');
    }
}