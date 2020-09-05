<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderItems as OrderItemsResource; 
use App\OrderItems;

class Orders extends JsonResource
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
            'order_id' => $this->order_id,
            'user' => $this->user,
            'products' => OrderItemsResource::collection(OrderItems::where('order_id', $this->order_id)->get())
        ];

        // return parent::toArray($request);
    }
}
