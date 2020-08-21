<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'category_id' => $this->category_id,
            'name' => $this->name, 
            'image'=>asset('/images/category/'.$this->image),
        ]; 
    }
}
