<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class Slider extends JsonResource
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
            'slider_id' => $this->slider_id,
            'image'=> Storage::disk('s3')->url($this->image), 
        ];
        // return parent::toArray($request);
    }
}
