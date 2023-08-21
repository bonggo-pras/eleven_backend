<?php

namespace Webkul\CustomerReward\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\Customer\Models\Customer;
use Webkul\CustomerReward\Http\Resources\CustomerRewardResource;
use Webkul\CustomerReward\Repositories\PointHistoryRepository;
use Webkul\Sales\Models\Order;

class CustomerRewardController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    protected $pointHistoryRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PointHistoryRepository $pointHistoryRepository)
    {
        $this->_config = request('_config');
        $this->pointHistoryRepository = $pointHistoryRepository;
    }

    public function getCustomerPoints()
    {
        $customerId = null;

        if (auth()->guard()->check()) {
            $customerId = auth()->guard()->user()->id;
        }

        if (auth('sanctum')->check()) {
            $customerId = auth('sanctum')->user()->id;
        }

        $pointHistories = $this->pointHistoryRepository->getCustomerPointHistories($customerId) ?? null;
        $customer = Customer::where('id', $customerId)->first();

        return response([
            'data'    => [
                'total_point' => $customer->total_point,
                'total' => $pointHistories->count(),
                'histories' => CustomerRewardResource::collection($pointHistories)
            ],
            'message' => 'Get point history selected customer',
        ]);
    }

    public function setCustomerPoint($order)
    {
        if ($order->status == 'completed') {
            $getpoint = $this->pointHistoryRepository->setCustomerPoint($order);

            return response([
                'data' => [
                    'total_point' => $getpoint
                ],
                'message' => 'Success update point customer',
            ]);
        } else {
            return response([
                'data' => [
                    'total_point' => 0
                ],
                'message' => 'Failed to update point customer',
            ]);
        }
    }
}
