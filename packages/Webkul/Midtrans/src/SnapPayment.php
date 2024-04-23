<?php

namespace Webkul\Midtrans;

use Webkul\Midtrans\Resources\BillingAddress;
use Webkul\Midtrans\Resources\CustomerDetail;
use Webkul\Midtrans\Resources\ItemDetail;
use Webkul\Midtrans\Resources\ShippingAddress;
use Webkul\Midtrans\Resources\TransactionDetail;
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
        Config::$serverKey = core()->getConfigData("sales.paymentmethods.midtrans.server_key");
        Config::$clientKey = core()->getConfigData("sales.paymentmethods.midtrans.client_key");
        Config::$isProduction = core()->getConfigData("sales.paymentmethods.midtrans.environment") === "production";
        Config::$isSanitized = core()->getConfigData("sales.paymentmethods.midtrans.sanitize");
        Config::$is3ds = core()->getConfigData("sales.paymentmethods.midtrans.3ds");

        $this->order = $order;
        $this->transactionDetail->orderId = $order->id;
        $this->transactionDetail->grossAmount = (int) $order->base_grand_total;

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
        $jsonDecodeAddress = $this->order->addresses->where('address_type', 'order_billing')->first();

        $this->billingAddress->firstName = $jsonDecodeAddress->first_name;
        $this->billingAddress->lastName = $jsonDecodeAddress->last_name;
        $this->billingAddress->address = $jsonDecodeAddress->address1;
        $this->billingAddress->city = $jsonDecodeAddress->city;
        $this->billingAddress->postalCode = $jsonDecodeAddress->postcode;
        $this->billingAddress->phone = str_replace('+', '', $jsonDecodeAddress->phone);

        $this->customerDetail->phone = str_replace('+', '', $jsonDecodeAddress->phone);
        $this->customerDetail->setBillingAddress($this->billingAddress);

        return $this;
    }

    protected function setShippingAddress()
    {
        $jsonDecodeAddress = $this->order->addresses->where('address_type', 'order_shipping')->first();

        $this->shippingAddress->firstName = $jsonDecodeAddress->first_name;
        $this->shippingAddress->lastName = $jsonDecodeAddress->last_name;
        $this->shippingAddress->address = $jsonDecodeAddress->address1;
        $this->shippingAddress->city = $jsonDecodeAddress->city;
        $this->shippingAddress->postalCode = $jsonDecodeAddress->postcode;
        $this->shippingAddress->phone = str_replace('+', '', $jsonDecodeAddress->phone);

        $this->customerDetail->setShippingAddress($this->shippingAddress);

        return $this;
    }

    protected function setCustomerData()
    {
        $customer = $this->order->customer;

        $this->customerDetail->firstName = $customer->first_name;
        $this->customerDetail->lastName = $customer->last_name;
        $this->customerDetail->email =  $customer->email;

        return $this;
    }

    protected function setItems()
    {
        $orderItems = $this->order->items;

        foreach ($orderItems as $item) {
            $item_name = $item->product->name;
            $this->transactionItems->addItem($item->id, $item_name, (int) $item->price, (int) $item->quantity);
        }

        return $this;
    }

    protected function setAdditionalCharges()
    {
        $order = $this->order;

        if ($order->shipping_amount > 0) {
            $this->transactionItems->addItem("shipping", 'global', $order->shipping_amount, 1);
        }

        if ((int) $order->discount_amount > 0) {
            $this->transactionItems->addItem("disc", "Discount", (int) $order->discount_amount * -1, 1);
        }

        if ((int) $order->tax_amount > 0) {
            $this->transactionItems->addItem("tax", "Tax", (int) $order->tax_amount, 1);
        }

        return $this;
    }

    protected function getAPIConfig($name)
    {
        return core()->getConfigData('sales.paymentmethods.midtrans.general.api.' . $name);
    }
}
