<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            'id'=> $this->id,
            'customer'=> $this->getCustomer(),
            'cooker' => $this->getCooker(),
            'deliveryman' => $this->getDeliveryman(),
            'status' => $this->status,
            'notes' => $this->notes,
            'total_price'=> $this->total_price,
            'date' => $this->date,
            'opened_at' => $this->opened_at,
            'current_status_at' => $this->current_status_at,
        ];
    }
}
