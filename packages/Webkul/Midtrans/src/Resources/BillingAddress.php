<?php

namespace Webkul\Midtrans\Resources;

class BillingAddress extends AbstractResource
{
    public $firstName;
    public $lastName;
    public $address;
    public $city;
    public $postalCode;
    public $phone;
    public $countryCode = "IDN";

    public function getArray()
    {
        $this->validateData();

        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'phone' => $this->phone,
            'country_code' => $this->countryCode
        ];
    }
}
