<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class FileDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $id;
  public $name;
  public $fileTypeId;
  public $dzzId;
  public $path;


  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'id'         => 'nullable',
      'name'       => 'required',
      'fileTypeId' => 'required',
      'dzzId'      => 'required',
      'path'       => 'required',
    ];
  }

  /**
   * @inheritDoc
   */
  protected function map($data): bool
  {
    if (array_key_exists('id', $data)) {
      $this->id = $data['id'];
    } else {
      $this->id = null;
    }
    $this->name       = $data['name'];
    $this->fileTypeId = $data['fileTypeId'];
    $this->dzzId      = $data['dzzId'];
    $this->path       = $data['path'];


    return true;
  }
}
