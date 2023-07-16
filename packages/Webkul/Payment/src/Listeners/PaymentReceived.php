<?php

namespace Webkul\Payment\Listeners;

use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class PaymentReceived
{
    protected $invoiceRepository;

    protected $orderRepository;

    public function __construct(InvoiceRepository $invoiceRepository, OrderRepository $orderRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->orderRepository = $orderRepository;
    }

    public function updateOrder($notification)
    {
        $paymentStatus = $notification->transaction_status;

        $order = Order::select(['id', 'grand_total'])->where([
            'increment_id' => $notification->order_id,
            'status' => 'pending'
        ])->first();

        if ($order) {
            if ($paymentStatus === 'capture' || $paymentStatus === 'settlement') {
                $orderItems = $order->items()->get();

                if ((int) $order->grand_total === (int) $notification->gross_amount) {
                    if (!$orderItems) {
                        return false;
                    }

                    $items = [];
                    foreach ($orderItems as $item) {
                        $items[$item->id] = $item->qty_ordered;
                    }

                    $invoiceData = [
                        'invoice' => ['items' => $items],
                        'order_id' => $order->id
                    ];

                    $order->payment->completed_at = $notification->transaction_time;

                    $this->invoiceRepository->create($invoiceData);

                    $this->orderRepository->updateOrderStatus($order, 'completed');
                }

                $order->payment->save();
            }
        }
    }
}
