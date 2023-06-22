<?php

namespace Webkul\Midtrans\Payment;

use Webkul\Payment\Payment\Payment;

class Midtrans extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'midtrans';

    public function getRedirectUrl()
    {
        
    }
}