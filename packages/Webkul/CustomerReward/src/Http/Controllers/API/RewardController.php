<?php

namespace Webkul\CustomerReward\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\Customer\Models\Customer;
use Webkul\CustomerReward\Http\Resources\RewardResource;
use Webkul\CustomerReward\Repositories\RewardRepository;

class RewardController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    protected $rewardRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RewardRepository $rewardRepository)
    {
        $this->_config = request('_config');
        $this->rewardRepository = $rewardRepository;
    }

    public function getRewards()
    {
        $rewards = $this->rewardRepository->getRewards();

        return response([
            'data'    => [
                'rewards' => RewardResource::collection($rewards)
            ],
            'message' => 'Get data all rewards',
        ]);
    }

    public function detailReward($id)
    {
        $reward = $this->rewardRepository->detailReward();

        return response(([
            'data' => new RewardResource($reward),
            'message' => 'Get data detail reward'
        ]));
    }

    public function claimReward(Request $request)
    {
        $customerId = null;
        $rewardId = $request->reward_id;
        $claimReward = $this->rewardRepository->claimReward($rewardId);

        if (auth()->guard()->check()) {
            $customerId = auth()->guard()->user()->id;
        }

        if (auth('sanctum')->check()) {
            $customerId = auth('sanctum')->user()->id;
        }
        
        $customer = Customer::where('id', $customerId)->first();

        if ($claimReward) {
            return response(([
                'data' => [
                    'is_claimed' => $claimReward,
                    'total_point' => $customer->total_point,
                ],
                'message' => 'Claim reward successfully'
            ]));
        } else {
            return response(([
                'data' => [
                    'is_claimed' => $claimReward
                ],
                'message' => 'Claim reward failed, your point is not enough to get this reward'
            ]));
        }
    }
}
