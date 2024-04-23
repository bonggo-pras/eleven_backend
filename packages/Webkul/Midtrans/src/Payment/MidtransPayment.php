<?php

namespace Webkul\Midtrans\Payment;

use Webkul\Payment\Payment\Payment;
use Webkul\Checkout\Facades\Cart;
use Webkul\Midtrans\SnapPayment;
use Webkul\Sales\Repositories\OrderRepository;

class MidtransPayment extends Payment
{
    protected $code = 'midtrans';
    protected $orderRepository;
    protected $snapPayment;
    protected $enabledPayments = [];

    public function __construct(SnapPayment $snapPayment, OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->snapPayment = $snapPayment;
    }

    public function isAvailable()
    {
        if (!core()->getConfigData("sales.paymentmethods.{$this->code}.active")) {
            return false;
        }

        return $this->getAPIConfig('active');
    }

    public function getAPIConfig($name = '')
    {
        return core()->getConfigData("sales.paymentmethods.midtrans.{$name}");
    }

    public function getRedirectUrl()
    {
        $cart = Cart::getCart();

        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        $paymentData = $this->snapPayment
            ->setEnabledPayments($this->enabledPayments)
            ->createPayment($order);

        $order->payment->redirect_url = $paymentData->redirect_url;

        $order->payment->save();

        Cart::deActivateCart();

        return $paymentData->redirect_url;
    }
}
