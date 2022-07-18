<?php

namespace App\Http\Services;

use App\Http\Services\Dto\TaskOutputDto;
use App\Models\Task;
use App\Http\Services\Dto\TaskInputDto;
use App\Models\Dzz;
use App\Models\File;
use App\Models\Plan;
use App\Models\PlanData;
use App\Models\TaskData;
use App\Models\TaskResultView;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class TaskService
{
  public function getAll()
  {
    return  Task::orderBy('id')->paginate(10);
  }

  public function getBySearch($search)
  {
    return  Task::where('title', 'ilike', '%'.$search.'%')->orderBy('id')->paginate(10);
  }

  public function getAllByUser($userId)
  {
    return  Task::where('user_id', $userId)->orderBy('id')->paginate(10);
  }

  public function getOne($id)
  {
    $task = Task::find($id);

    if (!$task) {
      return null;
    }
    return $task;
  }

  public function getOneByUser($userId, $id)
  {
    $task = Task::find(intval($id));

    if (!$task) {
      return null;
    }

    if ($task->user_id != $userId) {
      return null;
    }

    return $task;
  }

  public function update(TaskOutputDto $dto)
  {

    $task = Task::find($dto->id);
    if (!$task) {
      return null;
    }
    $task->title     = $dto->title;
    $task->status_id = $dto->statusId;
    $task->dzz_id    = $dto->dzzId;
    $task->result    = $dto->result;
    $task->save();

    return true;
  }

  public function delete($id)
  {
    $task = Task::find($id);
    if (!$task) {
      return null;
    }
    $task->delete();

    return true;
  }

  public function deleteUserTask($id)
  {
    
    $task = Task::find($id);

    if (!$task || $task->user_id != Auth::id()) {
      return null;
    } 
    
    $task->delete();
    UserLog::create([
      'user_id' => Auth::id(),
      'message' => 'Удалил задачу '.$task->id,
      'type' => 'delete'
    ]);
    return true;
  }



  public function post(TaskInputDto $dto, $userId)
  {
    $task = Task::Create([
      'title'     => Plan::Find($dto->planId)->title,
      'status_id' => 1,
      'plan_id'   => $dto->planId,
      'user_id'   => $userId
    ]);

    UserLog::create([
      'user_id' => $userId,
      'message' => 'Запланировал задачу '.$task->id,
      'type' => 'store'
    ]);
    
    $taskId = $task->id;

    if ($dto->params != null) {
      foreach ($dto->params as $key => $param) {
        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 4,
          'title'        => PlanData::Find($dto->links->params[$key])->title,
          'text'         => $param,
          'plan_data_id' => $dto->links->params[$key]
        ]);
        UserLog::create([
          'user_id' => $userId,
          'message' => 'Добавил данные для задачи '.$taskId,
          'type' => 'store'
        ]);
      }
    }

    if ($dto->dzzs != null) {
      foreach ($dto->dzzs as $key => $dzzId) {
        $file = Dzz::Find($dzzId)->directory;

        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 2,
          'title'        => PlanData::Find($dto->links->dzzs[$key])->title,
          'file_id'      => $file->id,
          'plan_data_id' => $dto->links->dzzs[$key]
        ]);
        UserLog::create([
          'user_id' => $userId,
          'message' => 'Добавил данные для задачи '.$taskId,
          'type' => 'store'
        ]);
      }
    }

    if ($dto->files != null) {
      foreach ($dto->files as $key => $file) {
        $dzz =  Dzz::Create([
          'name' => 'user dzz',
        ]);
        UserLog::create([
          'user_id' => $userId,
          'message' => 'Добавил снимок '.$dzz->id,
          'type' => 'store'
        ]);
        $directory = "files/dzzs/" . str_pad($dzz->id, 32, "0", STR_PAD_LEFT);
        $name      = $file->getClientOriginalName();
        Storage:: makeDirectory($directory);
        $path = Storage::putFileAs($directory, $file, $name);
        $newFile = null;

        if ($file->extension() == "zip") {
          $zip = new \ZipArchive;
          $res = $zip->open(Storage::path($path));

          if ($res === TRUE) {
            $zip->extractTo(Storage::path($directory));
            $zip->close();
          }

          Storage::delete($path);

          $newFile = File::Create([
            'name'    => $name,
            'path'    => $directory,
            'type_id' => 3,
            'user_id' => $userId
          ]);
          
        } else {
          $newFile = File::Create([
            'name'    => $name,
            'path'    => $path,
            'type_id' => 2,
            'user_id' => $userId
          ]);
        }

        $dzz->directory_id = $newFile->id;
        $dzz->save();
        UserLog::create([
          'user_id' => $userId,
          'message' => 'Добавил файл '.$newFile->id,
          'type' => 'store'
        ]);
        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 2,
          'title'        => PlanData::Find($dto->links->files[$key])->title,
          'file_id'      => $newFile->id,
          'plan_data_id' => $dto->links->files[$key]
        ]);
        UserLog::create([
          'user_id' => $userId,
          'message' => 'Добавил данные для задачи '.$taskId,
          'type' => 'store'
        ]);
      }
    }



    if ($dto->vectors != null) {
      foreach ($dto->vectors as $key => $vector) {
        $json    = json_decode($vector, true);
        $polygon = json_encode(\GeoJson\GeoJson::jsonUnserialize($json)
          ->getGeometry()->jsonSerialize());

        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 3,
          'title'        => PlanData::Find($dto->links->vectors[$key])->title,
          'geography'    => DB::raw("ST_GeomFromGeoJSON('$polygon')"),
          'plan_data_id' => $dto->links->vectors[$key]
        ]);
        UserLog::create([
          'user_id' => $userId,
          'message' => 'Добавил данные для задачи '.$taskId,
          'type' => 'store'
        ]);
      }
    }


    return true;
  }
}
