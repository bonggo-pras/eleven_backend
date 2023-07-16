<?php

namespace Webkul\CustomerReward\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\CustomerReward\Http\Resources\CustomerRewardResource;
use Webkul\CustomerReward\Repositories\CustomerRewardRepository;

class CustomerRewardController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    protected $customerRewardRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerRewardRepository $customerRewardRepository)
    {
        $this->_config = request('_config');
        $this->customerRewardRepository = $customerRewardRepository;
    }

    public function getCustomerPoints()
    {
        $cek = $this->customerRewardRepository->getCustomerPointHistories() ?? null;

        dd($cek);

        return response([
            'data'    => new CustomerRewardResource($cek),
            'message' => 'Your review submitted successfully.',
        ]);
    }
}
