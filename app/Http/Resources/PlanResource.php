<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PlanResource extends JsonResource
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
            'excerpt' => $this->excerpt,
            'description'  => $this->description,
            'data' => PlanDataResource::collection($this->data),
            'requirements' => PlanRequirementResource::collection($this->requirements),
            'previewPath' => '/public'.Storage::url($this->preview->path)
        ];
    }
}
