<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Midtrans\Resources\BillingAddress;
use App\Http\Controllers\Midtrans\Resources\CustomerDetail;
use App\Http\Controllers\Midtrans\Resources\ItemDetail;
use App\Http\Controllers\Midtrans\Resources\ShippingAddress;
use App\Http\Controllers\Midtrans\Resources\TransactionDetail;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class SnapPayment
{
    protected $transactionItems;
    protected $transactionDetail;
    protected $customerDetail;
    protected $billingAddress;
    protected $shippingAddress;
    protected $enabledPayments;

    protected $order;

    public function __construct()
    {
        $this->transactionDetail = new TransactionDetail;
        $this->transactionItems = new ItemDetail;
        $this->customerDetail = new CustomerDetail;
        $this->billingAddress = new BillingAddress;
        $this->shippingAddress = new ShippingAddress;
        $this->enabledPayments = [];
    }

    public function createPayment($order)
    {
        Config::$serverKey = $this->getAPIConfig('server_key');
        Config::$isProduction = $this->getAPIConfig('environment') === 'production';
        Config::$isSanitized = $this->getAPIConfig('sanitize');
        Config::$is3ds = $this->getAPIConfig('3ds');

        $this->order = $order;
        $this->transactionDetail->orderId = $order->id;
        $this->transactionDetail->grossAmount = (int) $order->order_amount;

        $this->setItems()
            ->setAdditionalCharges()
            ->setBillingAddress()
            ->setShippingAddress()
            ->setCustomerData();

        $midtransParams = [
            'transaction_details' => $this->transactionDetail->getArray(),
            'customer_details' => $this->customerDetail->getArray(),
        ];

        try {
            $result = Snap::createTransaction($midtransParams);
        } catch (\Exception $e) {
            $jsonRequest = json_encode($midtransParams);
            logger("{$e->getMessage()} \n Request : {$jsonRequest}");
            Log::error($e->getMessage());

            throw $e;
        }

        return $result;
    }

    /**
     * Set enabled payment
     *
     * @param array $payments
     *
     * @return self
     */
    public function setEnabledPayments($payments = [])
    {
        $this->enabledPayments = $payments;

        return $this;
    }

    protected function setBillingAddress()
    {
        $jsonDecodeAddress = $this->order->delivery_address;
        $customer = $this->order->customer;

        $this->billingAddress->firstName = $customer->f_name;
        $this->billingAddress->lastName = $customer->l_name;
        $this->billingAddress->address = $jsonDecodeAddress['address'];
        $this->billingAddress->city = $jsonDecodeAddress['city'];
        $this->billingAddress->postalCode = $jsonDecodeAddress['postcode'];
        $this->billingAddress->phone = str_replace('+', '', $jsonDecodeAddress['contact_person_number']);

        $this->customerDetail->phone = str_replace('+', '', $jsonDecodeAddress['contact_person_number']);
        $this->customerDetail->setBillingAddress($this->billingAddress);

        return $this;
    }

    protected function setShippingAddress()
    {
        $jsonDecodeAddress = $this->order->delivery_address;
        $customer = $this->order->customer;

        $this->shippingAddress->firstName = $customer->f_name;
        $this->shippingAddress->lastName = $customer->l_name;
        $this->shippingAddress->address = $jsonDecodeAddress['address'];
        $this->shippingAddress->city = $jsonDecodeAddress['city'];
        $this->shippingAddress->postalCode = $jsonDecodeAddress['postcode'];
        $this->shippingAddress->phone = str_replace('+', '', $jsonDecodeAddress['contact_person_number']);

        $this->customerDetail->setShippingAddress($this->shippingAddress);

        return $this;
    }

    protected function setCustomerData()
    {
        $customer = $this->order->customer;

        $this->customerDetail->firstName = $customer->f_name;
        $this->customerDetail->lastName = $customer->l_name;
        $this->customerDetail->email =  $customer->email;

        // return $this;
    }

    protected function setItems()
    {
        $orderItems = $this->order->details;

        foreach ($orderItems as $item) {
            $item_name = json_decode($item->product_details)->name;
            $this->transactionItems->addItem($item->id, $item_name, (int) $item->price, (int) $item->quantity);
        }

        return $this;
    }

    protected function setAdditionalCharges()
    {
        $order = $this->order;

        if ($order->delivery_charge > 0) {
            $this->transactionItems->addItem("shipping", 'global', $order->delivery_charge, 1);
        }

        if ((int) $order->coupon_discount_amount > 0) {
            $this->transactionItems->addItem("disc", "Discount", (int) $order->coupon_discount_amount * -1, 1);
        }

        if ((int) $order->total_tax_amount > 0) {
            $this->transactionItems->addItem("tax", "Tax", (int) $order->total_tax_amount, 1);
        }

        return $this;
    }

    protected function getAPIConfig($name)
    {
        return core()->getConfigData('sales.paymentmethods.midtrans.general.api.' . $name);
    }
}
