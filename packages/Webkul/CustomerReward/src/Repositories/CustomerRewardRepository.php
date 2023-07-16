<?php

namespace Webkul\CustomerReward\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Cart Address Repository
 *
 * @author    Bonggo Prasetyanto
 * @copyright 2023 CV. Gemary Digital
 */
class CustomerRewardRepository extends Repository
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

    function getCustomerPointHistories()
    {
        $totalPoint = 0;
        $customerId = null;

        if (auth()->guard()->check()) {
            $customerId = auth()->guard()->user()->id;
        }

        if (auth('sanctum')->check()) {
            $customerId = auth('sanctum')->user()->id;
        }

        if ($customerId != null) {
            $pointCustomerHistories = $this->model->where('customer_id', $customerId)->get();

            return $pointCustomerHistories;
        }

        if ($customerId == null) {
            return abort(401);
        }
    }
}
