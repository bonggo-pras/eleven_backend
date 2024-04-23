<?php

namespace Webkul\CustomerReward\Repositories;

use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Models\Customer;
use Webkul\CustomerReward\Models\PointHistory;

/**
 * Point History Repository
 *
 * @author    Bonggo Prasetyanto
 * @copyright 2023 CV. Gemary Digital
 */
class PointHistoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\CustomerReward\Models\PointHistory';
    }

    function getCustomerPointHistories($customerId)
    {
        if ($customerId != null) {
            $pointCustomerHistories = $this->model->where('customer_id', $customerId)->with('customer')->orderBy('id', 'desc')->get();

            return $pointCustomerHistories;
        }

        if ($customerId == null) {
            return abort(401);
        }
    }

    function setCustomerPoint($order)
    {
        $totalPoint = 0;
        $customerId = null;
        $orderItemCollection = collect([]);

        $customerId = $order->customer_id;

        if ($customerId != null) {
            foreach ($order->items as $item) {
                if ($item->product != null) {
                    $product = $item->product;
                    $productTypeInstance = $product->getTypeInstance();
                    $productPoint = $productTypeInstance->getPoint();

                    $subTotalPoint = $productPoint * $item->qty_ordered;
                    $totalPoint = $totalPoint + $subTotalPoint;

                    $orderItemCollection->push([
                        'customer_id' => $customerId,
                        'order_id' => $order->id,
                        'amount' => $subTotalPoint,
                        'type' => 'in',
                        'status' => 'approve',
                        'remarks' => 'Point pembelian.',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }

            $insertPoint = PointHistory::insert($orderItemCollection->toArray());

            // Update customer point
            if ($insertPoint) {
                $customer = Customer::find($customerId);

                if ($customer) {
                    $customerTotalPoint = $customer->total_point;

                    $customer->total_point = $customerTotalPoint + $totalPoint;
                    $customer->save();
                }
            }

            return $totalPoint;
        }
    }

    function canceledCustomerPoint($order_id)
    {
        $customerId = null;
        $totalPoint = 0;
        $orderItemCollection = collect([]);
        $pointCustomerHistories = PointHistory::where([
            'order_id' => $order_id,
            'type' => 'in',
            'status' => 'approve'
        ])->first();

        if ($pointCustomerHistories) {
            $customerId = $pointCustomerHistories->customer_id;
            $totalPoint = $pointCustomerHistories->amount;

            $insertPoint = PointHistory::create([
                'customer_id' => $customerId,
                'order_id' => $pointCustomerHistories->order_id,
                'amount' => $totalPoint,
                'type' => 'out',
                'status' => 'approve',
                'remarks' => 'Refund pembelian',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Update customer point
            if ($insertPoint) {
                $customer = Customer::find($customerId);

                if ($customer) {
                    $customerTotalPoint = $customer->total_point;

                    $customer->total_point = $customerTotalPoint - $totalPoint;
                    $customer->save();
                }
            }
        }

        return $totalPoint;
    }
}
