<?php

namespace Webkul\Midtrans\Payment;

class BankTransfer extends MidtransPayment
{
    protected $code = 'midtrans_bank_transfer';
    protected $enabledPayments = ['bank_transfer'];
}
