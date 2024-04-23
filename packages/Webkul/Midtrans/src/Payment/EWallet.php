<?php

namespace Webkul\Midtrans\Payment;

class EWallet extends MidtransPayment
{
    protected $code = 'midtrans_e_wallet';
    protected $enabledPayments = ['qris'];
}
