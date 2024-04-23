<?php

namespace Webkul\Payment\Listeners;

use Webkul\CustomerReward\Repositories\PointHistoryRepository;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class PaymentReceived
{
    protected $invoiceRepository;

    protected $orderRepository;

    protected $pointHistoryRepository;

    public function __construct(InvoiceRepository $invoiceRepository, OrderRepository $orderRepository, PointHistoryRepository $pointHistoryRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->orderRepository = $orderRepository;
        $this->pointHistoryRepository = $pointHistoryRepository;
    }

    public function updateOrder($notification)
    {
        $paymentStatus = $notification->transaction_status;

        $order = Order::where([
            'id' => $notification->order_id,
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

                    $this->updatePointCustomer($order);
                }

                $order->payment->save();
            }
        }
    }

    public function updatePointCustomer($order)
    {
        $getpoint = $this->pointHistoryRepository->setCustomerPoint($order);

        return $getpoint;
    }
}
