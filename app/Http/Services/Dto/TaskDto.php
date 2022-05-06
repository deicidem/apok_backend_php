<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class TaskDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $id;
  public $title;
  public $date;
  public $statusId;
  public $dzzId;
  public $result;

  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'id'       => 'nullable',
      'title'    => 'required',
      'date'     => 'nullable',
      'statusId' => 'required',
      'dzzId'    => 'required',
      'result'   => 'nullable',
    ];
  }

  /**
   * @inheritDoc
   */
  protected function map($data): bool
  {
    // if (array_key_exists('id', $data)) {
    //   $this->id     = $data['id'];
    // } else {
    //   $this->id     = null;
    // }
    $this->id = 1;
    $this->title  = $data['title'];
    if (array_key_exists('date', $data)) {
      $this->date = $data['date'];
    } else {
      $this->date = null;
    }
    $this->statusId = $data['statusId'];
    $this->dzzId = $data['dzzId'];
    if (array_key_exists('result', $data)) {
      $this->result = $data['result'];
    } else {
      $this->result = null;
    }

    return true;
  }
}
