<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'firstName'    => $this->first_name,
            'lastName'     => $this->last_name,
            'email'        => $this->email,
            'role'         => $this->role->name,
            'phoneNumber'  => $this->phone_number,
            'organisation' => $this->organisation,
            'blocked'      => $this->is_blocked,
            'date'         => $this->created_at,
            'updated'      => $this->updated_at,
        ];
    }
}
