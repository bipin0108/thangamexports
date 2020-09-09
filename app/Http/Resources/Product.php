<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

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
            'image'=> Storage::disk('s3')->url($this->image),
        ];

        // return parent::toArray($request);
    }
}
