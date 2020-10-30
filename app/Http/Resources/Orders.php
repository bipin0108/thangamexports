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
            'order_no' => $this->order_no,  
            'status' => $this->status,  
            'order_date' => $this->created_at,  
        ];

        // return parent::toArray($request);
    }
}
