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
  public function getAll($input)
  {
    $query = File::query();

    $query->when(isset($input['userId']), function ($q) use ($input) {
      return $q->where('user_id', $input['userId']);
    });


    $query->when(isset($input['name']), function ($q) use ($input) {
      return $q->where('name', 'ilike', '%' . $input['name'] . '%');
    });
    $query->when(isset($input['id']), function ($q) use ($input) {
      return $q->where('id', 'ilike', '%' . $input['id'] . '%');
    });
    $query->when(isset($input['date']), function ($q) use ($input) {
      return $q->where('created_at', '>=', $input['date']);
    });
    $query->when(isset($input['any']), function ($q) use ($input) {
      return $q->where(function ($query) use ($input) {
        return $query->where('name', 'ilike', '%' . $input['any'] . '%')
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
      if ($sortBy == 'name') {
        return $q->orderBy('name', $descending ? 'desc' : 'asc');
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
      'message' => 'Удалил файл ' . $file->id,
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
