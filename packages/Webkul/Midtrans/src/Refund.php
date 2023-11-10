<?php

namespace Webkul\Midtrans;

use Illuminate\Support\Facades\Log;
use Midtrans\Transaction;
use Webkul\Sales\Models\Order;
use Midtrans\Config;

class Refund
{
    protected $order;

    public function __construct()
    {
    }

    public function createRefund($orderId)
    {
        $order = Order::find($orderId);

        if ($order->payment->method == "midtrans_e_wallet") {
            Config::$serverKey = core()->getConfigData("sales.paymentmethods.midtrans.server_key");
            Config::$clientKey = core()->getConfigData("sales.paymentmethods.midtrans.client_key");
            Config::$isProduction = core()->getConfigData("sales.paymentmethods.midtrans.environment") === "production";
            Config::$isSanitized = core()->getConfigData("sales.paymentmethods.midtrans.sanitize");
            Config::$is3ds = core()->getConfigData("sales.paymentmethods.midtrans.3ds");

            $refundKey = 'order-' . $orderId . 'ref';
            $orderTotal = (int) $order->base_grand_total;

            $params = array(
                'refund_key' => $refundKey,
                'amount' => $orderTotal,
                'reason' => 'Transaksi dibatalkan oleh admin. Mohon hubungi admin kami.'
            );

            try {
                $result = Transaction::refund($orderId, $params);
            } catch (\Exception $e) {
                $jsonRequest = json_encode($params);
                logger("{$e->getMessage()} \n Request : {$jsonRequest}");
                Log::error($e->getMessage());

                throw $e;
            }

            return $result;
        }
    }
}
