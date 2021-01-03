<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItems extends JsonResource
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
            'order' => $this->getOrder(),
            'product' => $this->getProduct(),
            'quantity'=> $this->quantity,
            'unit_price' => $this->unit_price,
            'sub_total_price' => $this->sub_total_price,
        ];
    }
}
