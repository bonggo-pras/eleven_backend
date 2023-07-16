<?php

namespace Webkul\CustomerReward\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRewardResource extends JsonResource
{
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
            'customer' => $this->customer,
            'order' => $this->order,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
