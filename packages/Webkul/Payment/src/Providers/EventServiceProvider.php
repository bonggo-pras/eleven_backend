<?php

namespace Webkul\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('checkout.order.save.after', 'Webkul\Payment\Listeners\GenerateInvoice@handle');

        Event::listen('payment.midtrans.notification.received', 'Webkul\Payment\Listeners\PaymentReceived@updateOrder');

        Event::listen('order.refund.after', 'Webkul\Payment\Listeners\RefundOrder@updatePointCustomer');
    }
}