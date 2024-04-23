<?php

namespace Webkul\CustomerReward\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\RestApi\Http\Resources\V1\Shop\Customer\CustomerResource;
use Webkul\RestApi\Http\Resources\V1\Shop\Sales\OrderResource;

class CustomerRewardResource extends JsonResource
{

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer' => new CustomerResource($this->customer),
            'order' => new OrderResource($this->order),
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => ucfirst($this->status),
            'remarks' => $this->remarks,
            'created_at' => Carbon::parse($this->created_at)->format('l, j F Y h:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('l, j F Y h:i')
        ];
    }
}
