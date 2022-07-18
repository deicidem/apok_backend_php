<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DzzResource extends JsonResource
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
            'id'              => $this->id,
            "name"            => $this->name,
            "date"            => $this->date,
            "round"           => $this->round,
            "route"           => $this->route,
            "cloudiness"      => $this->cloudiness,
            "processingLevel" => $this->processingLevel->name,
            "satelite"        => $this->satelite->name,
            "previewPath"     => '/public'.Storage::url($this->preview->path),
            "geography"       => json_decode($this->geography)
        ];
    }
}
