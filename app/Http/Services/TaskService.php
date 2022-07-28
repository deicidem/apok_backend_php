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
  public function getAll($input)
  {
    $query = Task::query();

    $query->when(isset($input['userId']), function ($q) use ($input) {
      return $q->where('user_id', $input['userId']);
    });


    $query->when(isset($input['title']), function ($q) use ($input) {
      return $q->where('title', 'ilike', '%' . $input['title'] . '%');
    });
    $query->when(isset($input['id']), function ($q) use ($input) {
      return $q->where('id', 'ilike', '%' . $input['id'] . '%');
    });
    $query->when(isset($input['date']), function ($q) use ($input) {
      return $q->where('created_at', '>=', $input['date']);
    });
    $query->when(isset($input['any']), function ($q) use ($input) {
      return $q->where(function ($query) use ($input) {
        return $query->where('title', 'ilike', '%' . $input['any'] . '%')
          ->orWhere('id', 'ilike', '%' . $input['any'] . '%')
          ->orWhere('created_at', 'ilike', '%' . $input['any'] . '%');
      });
    });


    $query->when(isset($input['sortBy']), function ($q) use ($input) {
      $descending = false;
      if (isset($input['desc'])) {
        $descending = filter_var($input['desc'], FILTER_VALIDATE_BOOLEAN);
      }

      $sortBy = $input['sortBy'];
      if ($sortBy == 'title') {
        return $q->orderBy('title', $descending ? 'desc' : 'asc');
      } else if ($sortBy == 'date') {
        return $q->orderBy('created_at', $descending ? 'desc' : 'asc');
      } else {
        return $q->orderBy('id', $descending ? 'desc' : 'asc');
      }
    }, function ($q) {
      return $q->orderBy('id');
    });


    $paginationSize = 5;
    if (isset($input['size'])) {
      if ($input['size'] > 50) {
        $paginationSize = 50;
      } else if ($input['size'] < 1) {
        $paginationSize = 1;
      } else {
        $paginationSize = $input['size'];
      }
    }

    return  $query->paginate($paginationSize);
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

  public function update($id, $input)
  {

    $task = Task::find($id);
    if (!$task) {
      return null;
    }
    $task->note = $input['note'];
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
      'type'    => 'delete'
    ]);
    return true;
  }



  public function post($input){
    $task = Task::Create([
      'title'     => Plan::Find($input['planId'])->title,
      'status_id' => 1,
      'plan_id'   => $input['planId'],
      'user_id'   => $input['userId'],
      'note'      => $input['note']
    ]);

    UserLog::create([
      'user_id' => $input['userId'],
      'message' => 'Запланировал задачу '.$task->id,
      'type'    => 'store'
    ]);
    
    $taskId = $task->id;

    if ($input['params'] != null) {
      foreach ($input['params'] as $key => $param) {
        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 4,
          'title'        => PlanData::Find($input['links']->params[$key])->title,
          'text'         => $param,
          'plan_data_id' => $input['links']->params[$key]
        ]);
        UserLog::create([
          'user_id' => $input['userId'],
          'message' => 'Добавил данные для задачи '.$taskId,
          'type'    => 'store'
        ]);
      }
    }

    if ($input['dzzs'] != null) {
      foreach ($input['dzzs'] as $key => $dzzId) {
        $file = Dzz::Find($dzzId)->directory;

        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 2,
          'title'        => PlanData::Find($input['links']->dzzs[$key])->title,
          'file_id'      => $file->id,
          'plan_data_id' => $input['links']->dzzs[$key]
        ]);
        UserLog::create([
          'user_id' => $input['userId'],
          'message' => 'Добавил данные для задачи '.$taskId,
          'type'    => 'store'
        ]);
      }
    }

    if ($input['files'] != null) {
      foreach ($input['files'] as $key => $file) {
        $dzz =  Dzz::Create([
          'name' => 'user dzz',
        ]);
        UserLog::create([
          'user_id' => $input['userId'],
          'message' => 'Добавил снимок '.$dzz->id,
          'type'    => 'store'
        ]);
        $directory = "files/dzzs/" . str_pad($dzz->id, 32, "0", STR_PAD_LEFT);
        $name      = $file->getClientOriginalName();
        Storage::makeDirectory($directory);
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
            'user_id' => $input['userId']
          ]);
          
        } else {
          $newFile = File::Create([
            'name'    => $name,
            'path'    => $path,
            'type_id' => 2,
            'user_id' => $input['userId']
          ]);
        }

        $dzz->directory_id = $newFile->id;
        $dzz->save();
        UserLog::create([
          'user_id' => $input['userId'],
          'message' => 'Добавил файл '.$newFile->id,
          'type'    => 'store'
        ]);
        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 2,
          'title'        => PlanData::Find($input['links']->files[$key])->title,
          'file_id'      => $newFile->id,
          'plan_data_id' => $input['links']->files[$key]
        ]);
        UserLog::create([
          'user_id' => $input['userId'],
          'message' => 'Добавил данные для задачи '.$taskId,
          'type'    => 'store'
        ]);
      }
    }



    if ($input['vectors'] != null) {
      foreach ($input['vectors'] as $key => $vector) {
        $json    = json_decode($vector, true);
        $polygon = json_encode(\GeoJson\GeoJson::jsonUnserialize($json)
          ->getGeometry()->jsonSerialize());

        TaskData::Create([
          'task_id'      => $taskId,
          'type_id'      => 3,
          'title'        => PlanData::Find($input['links']->vectors[$key])->title,
          'geography'    => DB::raw("ST_GeomFromGeoJSON('$polygon')"),
          'plan_data_id' => $input['links']->vectors[$key]
        ]);
        UserLog::create([
          'user_id' => $input['userId'],
          'message' => 'Добавил данные для задачи '.$taskId,
          'type'    => 'store'
        ]);
      }
    }


    return true;
  }
}
