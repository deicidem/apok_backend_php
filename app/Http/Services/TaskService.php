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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class TaskService
{
  private function getTaskResult($task)
  {
    $taskResultFiles = [];
    $taskResultViews = [];

    if ($task->result != null) {
      if ($task->result->files != null) {
        foreach ($task->result->files as $file) {
          array_push($taskResultFiles, [
            'id'           => $file->file->id,
            'name'         => $file->name,
            'downloadPath' => "/api/files/download?id=".$file->file->id
          ]);
        }
      }

      if ($task->result->views != null) {
        foreach ($task->result->views as $view) {
          $geography = null;

          if ($view->type_id == 2) {
            $geographyRaw = TaskResultView::selectRaw('ST_AsGeoJSON(ST_SimplifyPreserveTopology(geography::geometry, 1), 5, 1) as geography, id')->where('id', $view->id)->get();
            $geography    = json_decode($geographyRaw[0]->geography);
          }

          array_push($taskResultViews, [
            'id'           => $view->id,
            'title'        => $view->title,
            'type'         => $view->type_id,
            'previewPath'  => '/public'.Storage::url($view->preview->path),
            'downloadPath' => "/api/files/download?id=".$view->preview_id,
            'geography'    => $geography
          ]);
        }
      }
    }
    if (count($taskResultFiles) == 0 && count($taskResultViews) == 0) {
      return null;
    }
    return [
      'views' => $taskResultViews,
      'files' => $taskResultFiles
    ];
  }

  public function getAll()
  {
    $tasks  = Task::orderBy('id')->get();
    $result = [];
    foreach ($tasks as $task) {
      $user = $task->user;
      array_push($result, new TaskOutputDto([
        'id'        => $task->id,
        'title'     => $task->title,
        'date'      => $task->created_at,
        'status'    => $task->taskStatus->name,
        'result'    => $this->getTaskResult($task),
        'deletable' => $this->isTaskDeletable($task->id),
        'updatedAt' => $task->updated_at,
        'userId'    => $user->id,
        'userName'  => $user->first_name . " " . $user->last_name
      ]));
    };

    return $result;
  }

  public function getBySearch($search)
  {
    $tasks  = Task::where('title', 'ilike', '%'.$search.'%')->orderBy('id')->get();
    $result = [];
    foreach ($tasks as $task) {
      $user = $task->user;
      array_push($result, new TaskOutputDto([
        'id'        => $task->id,
        'title'     => $task->title,
        'date'      => $task->created_at,
        'status'    => $task->taskStatus->name,
        'result'    => $this->getTaskResult($task),
        'deletable' => $this->isTaskDeletable($task->id),
        'updatedAt' => $task->updated_at,
        'userId'    => $user->id,
        'userName'  => $user->first_name . " " . $user->last_name
      ]));
    };

    return $result;
  }

  public function getAllByUser($userId)
  {
    $tasks = Task::where('user_id', $userId)->orderBy('id')->get();
    $result = [];
    foreach ($tasks as $task) {
      $user = $task->user;
      array_push($result, new TaskOutputDto([
        'id'        => $task->id,
        'title'     => $task->title,
        'date'      => $task->created_at,
        'status'    => $task->taskStatus->name,
        'result'    => $this->getTaskResult($task),
        'deletable' => $this->isTaskDeletable($task->id),
        'updatedAt' => $task->updated_at,
        'userId'    => $user->id,
        'userName'  => $user->first_name . " " . $user->last_name
      ]));
    };

    return $result;
  }

  public function getOne($id)
  {
    $task = Task::find(intval($id));

    if (!$task) {
      return null;
    }
    $dto = new TaskOutputDto([
      'id'        => $task->id,
      'title'     => $task->title,
      'date'      => $task->created_at,
      'status'    => $task->taskStatus->name,
      'result'    => $this->getTaskResult($task),
      'deletable' => $this->isTaskDeletable($task->id),
      'updatedAt' => $task->updated_at
    ]);
    return $dto;
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

    $dto = new TaskOutputDto([
      'id'        => $task->id,
      'title'     => $task->title,
      'date'      => $task->created_at,
      'status'    => $task->taskStatus->name,
      'result'    => $this->getTaskResult($task),
      'deletable' => $this->isTaskDeletable($task->id),
      'updatedAt' => $task->updated_at
    ]);
    return $dto;
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

  public function isTaskDeletable($id) {
    $task = Task::find($id);

    if (!$task) {
      return false;
    } 

    return $task->status_id == 3 || $task->status_id == 1 ? true : false;
  }

  public function deleteUserTask($id)
  {
    
    $task = Task::find($id);

    if (!$task || $task->user_id != Auth::id()) {
      return null;
    } 

    $task->delete();

    return true;
  }



  public function post(TaskInputDto $dto)
  {
    $task = Task::Create([
      'title'     => Plan::Find($dto->planId)->title,
      'status_id' => 1,
      'plan_id'   => $dto->planId,
      'user_id'   => Auth::id()
    ]);

    $taskId = $task->id;

    if ($dto->params != null) {
      foreach ($dto->params as $key => $param) {
        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 1,
          'title'        => PlanData::Find($dto->links->params[$key])->title,
          'text'         => $param,
          'plan_data_id' => $dto->links->params[$key]
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
      }
    }

    if ($dto->files != null) {
      foreach ($dto->files as $key => $file) {
        $dzz =  Dzz::Create([
          'name' => 'user dzz',
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
            'user_id' => Auth::id()
          ]);
        } else {
          $newFile = File::Create([
            'name'    => $name,
            'path'    => $path,
            'type_id' => 2,
            'user_id' => Auth::id()
          ]);
        }

        $dzz->directory_id = $newFile->id;
        $dzz->save();

        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 2,
          'title'        => PlanData::Find($dto->links->files[$key])->title,
          'file_id'      => $newFile->id,
          'plan_data_id' => $dto->links->files[$key]
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
      }
    }


    return true;
  }
}
