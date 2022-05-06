<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class AlertDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $id;
  public $title;
  public $description;

  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'id'          => 'nullable',
      'title'       => 'required',
      'description' => 'required',
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

    $this->title       = $data['title'];
    $this->description = $data['description'];


    return true;
  }
}
