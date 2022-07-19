<?php

namespace App\Http\Services;

use App\Models\File;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\FileDto;
use App\Http\Services\Dto\SearchDto;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class FileService
{
  public function getAll($search, $userId)
  {
    $query = File::query();

    $query->when($userId != null, function ($q) use ($userId) {
      return $q->where('user_id', $userId);
    });

    $query->when($search != null, function ($q) use ($search) {
      return $q->where(function ($query) use ($search) {
        return $query->where('name', 'ilike', '%'.$search.'%')
          ->orWhere('id', 'ilike', '%'.$search.'%')
          ->orWhere('created_at', 'ilike', '%'.$search.'%');
      });
    });
    
    return  $query->orderBy('id')->paginate(5);
  }

  public function getAllByUser($userId) {
    $files  = File::where('user_id', $userId)->orderBy('id')->paginate(15);
    // $result = [];
    // foreach ($files as $file) {
    //   $user = $file->user;
    //   $relation = "";
    //   if ($file->taskResultFile != null) {
    //     $relation = "Задача: №" . $file->taskResultFile->taskResult->task->id . " - ";
    //   } else if (count($file->taskData) > 0) {
    //     $relation = "Задачи:";
    //     $td = $file->taskData;
    //     for ($i=0; $i < count($td); $i++) { 
    //       if ($i + 1 == count($td)) {
    //         $relation = $relation . " №" . $td[$i]->task->id  . " - ";
    //       } else {
    //         $relation = $relation . " №" . $td[$i]->task->id . ",";
    //       }
    //     }
    //   }
    //   array_push($result, new FileDto([
    //     'id'        => $file->id,
    //     'name'      => $relation . $file->name,
    //     'path'      => $file->path,
    //     'date'      => $file->created_at,
    //     'type'      => $file->type->name,
    //     'deletable' => $this->isFileDeletable($file->id),
    //     'userId'    => $user->id,
    //     'userName'  => $user->first_name . " " . $user->last_name
    //   ]));
    // };
    return $files;
  }

  

  public function getOne($id)
  {
    $file = File::find($id);
    if (!$file) {
      return null;
    }
    return $file;
  }

  public function update(FileDto $dto)
  {

    $file = File::find($dto->id);
    if (!$file) {
      return null;
    }

    $file->name    = $dto->name;
    $file->path    = $dto->path;
    $file->dzz_id  = $dto->dzzId;
    $file->type_id = $dto->fileTypeId;

    $file->save();

    return true;
  }

  public function delete($id)
  {
    $file = File::find($id);
    if (!$file) {
      return null;
    }
    $file->delete();

    return true;
  }

  public function deleteUserFile($id)
  {
    
    $file = File::find($id);
    if (!$file || $file->user_id != Auth::id()) {
      return null;
    } 

    $file->delete();
    UserLog::create([
      'user_id' => Auth::id(),
      'message' => 'Удалил файл '.$file->id,
      'type' => 'delete'
    ]);

    return true;
  }

  public function post(FileDto $dto)
  {
    File::create([
      'name'         => $dto->name,
      'path'         => $dto->path,
      'dzz_id'       => $dto->dzzId,
      'file_type_id' => $dto->fileTypeId,
    ]);
    return true;
  }
}
