<?php

namespace App\Http\Controllers\Midtrans\Resources;

class TransactionDetail extends AbstractResource
{
    public $orderId;
    public $grossAmount;

    public function getArray()
    {
        $this->validateData();

        return [
            'order_id' => $this->orderId,
            'gross_amount' => $this->grossAmount
        ];
    }
}
