<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
            'product_id' => $this->product_id,
            'category_id' => $this->category_id,
            'product_code' => $this->product_code,
            'weight' => $this->weight,
            'stone' => $this->stone,
            'kt' => $this->kt,
            'image'=>asset('/images/product/'.$this->image),
            'category_name' => $this->category->name,
        ];

        // return parent::toArray($request);
    }
}
