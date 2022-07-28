<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class GroupResource extends JsonResource
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
            'id'    => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type'  => $this->type->title,
            'typeId'  => $this->type_id,
            'owner' => new UserResource($this->owner),
            'isOwner' => $this->owner_id == Auth::id()
          ];
    }
}
