<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'date'      => $this->created_at,
            'status'    => $this->taskStatus->name,
            'result'    => $this->result == null ? null : [
                $this->mergeWhen($this->result != null, [
                    'views' => TaskResultViewResource::collection($this->result->views),
                    'files' => TaskResultFileResource::collection($this->result->files)
                ]),
            ],
            'deletable' => $this->isTaskDeletable($this->id),
            'updatedAt' => $this->updated_at,
            'user' => new UserResource($this->user)
        ];
    }
    private function isTaskDeletable($status_id) {    
        return $status_id == 3 || $status_id == 1 ? true : false;
      }
}
