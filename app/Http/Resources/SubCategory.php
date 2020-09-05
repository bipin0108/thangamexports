<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class SubCategory extends JsonResource
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
            'sub_category_id' => $this->sub_category_id,
            'category_id' => $this->category_id,
            'category_name' => $this->category->name,
            'name' => $this->name, 
            'image'=> Storage::disk('s3')->url($this->image),
        ]; 
        // return parent::toArray($request);
    }
}
