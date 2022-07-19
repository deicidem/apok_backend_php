<?php

namespace App\Http\Resources;

use App\Models\TaskResultView;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TaskResultViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $geography = TaskResultView::where('id', $this->id)->selectRaw('ST_AsGeoJSON(ST_SimplifyPreserveTopology(geography::geometry, 1), 5, 1) as geography')->first()->geography;
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'type'         => $this->type_id,
            'previewPath'  => '/public'.Storage::url($this->preview->path),
            'downloadPath' => "/api/download?id=".$this->preview_id,
            'geography'    => json_decode($geography)
        ];
    }
    public static $wrap = null;
}
