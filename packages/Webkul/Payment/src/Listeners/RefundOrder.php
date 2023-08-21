<?php

namespace Webkul\Payment\Listeners;

use Webkul\CustomerReward\Repositories\PointHistoryRepository;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class RefundOrder
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

    public function updatePointCustomer($order_id)
    {
        $getpoint = $this->pointHistoryRepository->canceledCustomerPoint($order_id);

        return $getpoint;
    }
}
