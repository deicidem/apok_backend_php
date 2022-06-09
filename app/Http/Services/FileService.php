<?php

namespace App\Http\Services;

use App\Models\File;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\FileDto;
use App\Http\Services\Dto\SearchDto;
use InvalidArgumentException;

class FileService
{
  public function getAll()
  {
    $files  = File::all();
    $result = [];
    foreach ($files as $file) {
      array_push($result, new FileDto([
        'id'         => $file->id,
        'name'       => $file->name,
        'path'       => $file->path,
        'fileTypeId' => $file->file_type_id,
      ]));
    };
    return $result;
  }

  public function getOne($id)
  {
    $file = File::find($id);
    if (!$file) {
      return null;
    }
    return new FileDto([
      'id'         => $file->id,
      'name'       => $file->name,
      'path'       => $file->path,
      'fileTypeId' => $file->type_id,
    ]);
  }

  public function update(FileDto $dto)
  {

    $file = File::find($dto->id);
    if (!$file) {
      return null;
    }

    $file->name         = $dto->name;
    $file->path         = $dto->path;
    $file->dzz_id       = $dto->dzzId;
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
