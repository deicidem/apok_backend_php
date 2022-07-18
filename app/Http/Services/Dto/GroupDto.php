<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class GroupDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $id;
  public $title;
  public $type;
  public $ownerId;
    public $ownerName;

  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'id'        => 'nullable',
      'title'     => 'required',
      'type'      => 'required',
      'ownerId'   => 'required',
      'ownerName' => 'nullable',
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

    $this->title     = $data['title'];
    $this->type      = $data['type'];
    $this->ownerId   = $data['ownerId'];
    if (array_key_exists('ownerName', $data)) {
      $this->ownerName = $data['ownerName'];
    } else {
      $this->ownerName = null;
    }
  

    return true;
  }
}
