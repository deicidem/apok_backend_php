<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\TaskDto;
use App\Http\Services\Dto\SearchDto;
use InvalidArgumentException;
class TaskService
{
    public function getAll()
    {
      $tasks = Task::all();
      $result = [];
      foreach ($tasks as $task) {
        array_push($result, new TaskDto([
          'id'       => $task->id,
          'title'    => $task->title,
          'date'     => $task->created_at,
          'statusId' => $task->task_status_id,
          'dzzId'    => $task->dzz_id,
          'result'   => $task->result,
        ]));
      };
      return $result;
    }

    public function getOne($id)
    {
      $task = Task::find($id);
      if (!$task) {
          return null;
      }
      $dto = new TaskDto([
        'id'       => $task->id,
        'title'    => $task->title,
        'date'     => $task->created_at,
        'statusId' => $task->task_status_id,
        'dzzId'    => $task->dzz_id,
        'result'   => $task->result,
      ]);
      return $dto;
    }

    public function update(TaskDto $dto)
    {
      
      $task = Task::find($dto->id);
      if (!$task) {
        return null;
      }
      $task->title          = $dto->title;
      $task->task_status_id = $dto->statusId;
      $task->dzz_id         = $dto->dzzId;
      $task->result         = $dto->result;
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

    public function post(TaskDto $dto)
    {
      Task::create([
        'title'          => $dto->title,
        'result'         => $dto->result,
        'dzz_id'         => $dto->dzzId,
        'task_status_id' => $dto->statusId
      ]);
      return true;
    }
}
