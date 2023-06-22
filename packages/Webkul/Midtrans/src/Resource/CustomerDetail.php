<?php

namespace App\Http\Controllers\Midtrans\Resources;

class CustomerDetail extends AbstractResource
{
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    protected $billingAddress;
    protected  $shippingAddress;

    public function getArray()
    {
        $this->validateData();

        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'billing_address' => $this->billingAddress->getArray(),
            'shipping_address' => $this->shippingAddress->getArray(),
        ];
    }

    public function setBillingAddress(BillingAddress $billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    public function setShippingAddress(ShippingAddress $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }
}
