<?php

namespace Webkul\CustomerReward\Repositories;

use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Models\Customer;
use Webkul\CustomerReward\Models\PointHistory;
use Webkul\CustomerReward\Models\RewardClaimHistory;

/**
 * Reward Repository
 *
 * @author    Bonggo Prasetyanto
 * @copyright 2023 CV. Gemary Digital
 */
class RewardRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\CustomerReward\Models\Reward';
    }

    function getRewards()
    {
        $start_date = Carbon::now()->format('Y-m-d H:i:s');
        $end_date = Carbon::now()->format('Y-m-d H:i:s');

        $rewards = $this->model->where('stock', '>', 0)->where([['start', '<=', $start_date], ['end', '>=', $end_date]])
            ->orwhereBetween('start', array($start_date, $end_date))
            ->orWhereBetween('end', array($start_date, $end_date))->get();

        return $rewards;
    }

    function claimReward($rewardId)
    {
        $customerId = null;
        $customerPoint = 0;
        $start_date = Carbon::now()->format('Y-m-d H:i:s');
        $end_date = Carbon::now()->format('Y-m-d H:i:s');

        if (auth()->guard()->check()) {
            $customerId = auth()->guard()->user()->id;
        }

        if (auth('sanctum')->check()) {
            $customerId = auth('sanctum')->user()->id;
        }

        if ($customerId != null) {
            $customerId = auth('sanctum')->user()->id;
            $customerPoint = auth('sanctum')->user()->total_point;
        }

        $reward = $this->model->where('id', $rewardId)->where([['start', '<=', $start_date], ['end', '>=', $end_date]])
            ->orwhereBetween('start', array($start_date, $end_date))
            ->orWhereBetween('end', array($start_date, $end_date))->first();

        if ($reward) {
            if ($customerPoint >= $reward->point_required && $reward->stock > 0) {
                try {
                    $reward_id = $reward->id;
                    $reward_point = $reward->point_required;

                    // apply the reward
                    $pointHistory = PointHistory::create([
                        'customer_id' => $customerId,
                        'order_id' => null,
                        'reward_id' => $reward_id,
                        'amount' => $reward_point,
                        'type' => 'out',
                        'status' => 'approve',
                        'remarks' => 'Point penukaran reward.'
                    ]);

                    // Update customer point
                    if ($pointHistory) {
                        $customer = Customer::find($customerId);

                        if ($customer) {
                            $customer_total_point = $customer->total_point;

                            $customer->total_point = $customer_total_point - $reward_point;
                            $customer->save();
                        }
                    }

                    return true;
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }
        
        return false;
    }
}
