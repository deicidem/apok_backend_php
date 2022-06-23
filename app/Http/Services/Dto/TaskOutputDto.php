<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class TaskOutputDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $id;
  public $title;
  public $date;
  public $status;
  public $result;
  public $deletable;
  public $updatedAt;


  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'id'        => 'nullable',
      'title'     => 'nullable',
      'date'      => 'nullable',
      'status'    => 'nullable',
      'result'    => 'nullable',
      'deletable' => 'required',
      'updatedAt' => 'required',
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
    if (array_key_exists('title', $data)) {
      $this->title = $data['title'];
    } else {
      $this->title = null;
    }
    if (array_key_exists('date', $data)) {
      $this->date = $data['date'];
    } else {
      $this->date = null;
    }
    if (array_key_exists('status', $data)) {
      $this->status = $data['status'];
    } else {
      $this->status = null;
    }
    if (array_key_exists('result', $data)) {
      $this->result = $data['result'];
    } else {
      $this->result = null;
    }
    $this->deletable = $data['deletable'];
    $this->updatedAt = $data['updatedAt'];
    return true;
  }
}
