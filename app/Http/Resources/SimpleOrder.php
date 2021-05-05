<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SimpleOrder extends JsonResource
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
            'customer_id'=> $this->customer_id,
            'prepared_by' => $this->prepared_by,
            'delivered_by' => $this->delivered_by,
            'status' => $this->status,
            'notes' => $this->notes,
            'total_price'=> $this->total_price,
            'date' => $this->date,
            'opened_at' => $this->opened_at,
            'current_status_at' => $this->current_status_at,
        ];
    }
}
