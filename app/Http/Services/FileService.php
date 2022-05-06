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
      $files = File::all();

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

      $file->title = $dto->title;
      $file->text = $dto->text;

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
        'title' => $dto->title,
        'text' => $dto->text
      ]);
      return true;
    }
}
