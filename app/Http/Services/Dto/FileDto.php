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
  public $userId;
  public $userName;


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
      'userId'    => 'nullable',
      'userName'  => 'nullable',
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
    if (array_key_exists('userId', $data)) {
      $this->userId = $data['userId'];
    } else {
      $this->userId = null;
    }
    if (array_key_exists('userName', $data)) {
      $this->userName = $data['userName'];
    } else {
      $this->userName = null;
    }

    return true;
  }
}
