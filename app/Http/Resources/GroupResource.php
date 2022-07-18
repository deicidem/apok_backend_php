<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id'        => $this->id,
            'title'     => $this->title,
            'ownerId'   => $this->owner_id,
            'ownerName' => $this->owner->first_name . " " . $this->owner->last_name,
            'type'      => $this->type->title,
            'owner' => new UserResource($this->owner)
          ];
    }
}
