<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

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
            'image'=> Storage::disk('s3')->url($this->image),
        ]; 
    }
}
