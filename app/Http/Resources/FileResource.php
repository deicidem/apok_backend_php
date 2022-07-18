<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class FileResource extends JsonResource
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
          'name'      => $this->name,
          'path'      => $this->path,
          'date'      => $this->created_at,
          'type'      => $this->type->name,
          'deletable' => $this->isFileDeletable($this->id),
          'user'      => $this->when(Auth::user()->role_id == 1, new UserResource($this->user))
       ];
    }

    private function isFileDeletable($id) {
        $file = File::find($id);

        if (!$file || $file->user_id != Auth::id()) {
          return false;
        } 
    
        $deletable = true;
    
        foreach ($file->taskData as $taskData) {
          if ($taskData->task->status_id != 3) {
            $deletable = false;
            break;
          }
        }
    
        return $deletable;
    }
}
