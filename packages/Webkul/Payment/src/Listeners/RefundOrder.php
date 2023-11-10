<?php

namespace Webkul\Payment\Listeners;

use Webkul\CustomerReward\Repositories\PointHistoryRepository;
use Webkul\Midtrans\Refund;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class RefundOrder
{
    protected $invoiceRepository;

    protected $orderRepository;

    protected $pointHistoryRepository;

    protected $refund;

    public function __construct(InvoiceRepository $invoiceRepository, OrderRepository $orderRepository, PointHistoryRepository $pointHistoryRepository, Refund $refund)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->orderRepository = $orderRepository;
        $this->pointHistoryRepository = $pointHistoryRepository;
        $this->refund = $refund; 
    }

    public function refundTransaction($orderId) 
    {
        $this->refund->createRefund($orderId);
        
        $this->updatePointCustomer($orderId);

        return true;
    }

    public function updatePointCustomer($orderId)
    {
        $getpoint = $this->pointHistoryRepository->canceledCustomerPoint($orderId);

        return $getpoint;
    }
}
