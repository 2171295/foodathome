<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'email' => $this->email,
            'status' => $this->userStatus(),
            'type' => $this->type,
            'blocked' => $this->blockedToStr(),
            'photo_url'=> $this->photo_url,
            'deleted_at'=> $this->deleted_at,
            'logged_at' => $this->logged_at,
            'available_at' => $this->available_at,
        ];
    }
}
