<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class FileDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $id;
  public $name;
  public $type;
  public $date;
  public $path;
  public $deletable;


  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'id'        => 'nullable',
      'name'      => 'required',
      'type'      => 'required',
      'date'      => 'required',
      'path'      => 'required',
      'deletable' => 'required',
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
    $this->name      = $data['name'];
    $this->type      = $data['type'];
    $this->date      = $data['date'];
    $this->path      = $data['path'];
    $this->deletable = $data['deletable'];


    return true;
  }
}
