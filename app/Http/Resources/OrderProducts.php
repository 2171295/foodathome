<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProducts extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'photo'=> $this->photo_url,
            'quantity' => $this->pivot->quantity,
            'unit_price' => $this->price,
            'sub_total_price' => $this->pivot->sub_total_price,
        ];
    }
}
