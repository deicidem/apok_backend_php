<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class PlanDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $id;
  public $title;
  public $excerpt;
  public $description;
  public $data;
  public $requirements;
  public $previewPath;

  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'id'    => 'nullable',
      'title' => 'required',
      'excerpt' => 'required',
      'description'  => 'required',
      'data'  => 'nullable',
      'requirements'  => 'nullable',
      'previewPath' => 'nullable',
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
    $this->title = $data['title'];
    $this->excerpt = $data['excerpt'];
    $this->description  = $data['description'];
    if (array_key_exists('requirements', $data)) {
      $this->requirements = $data['requirements'];
    } else {
      $this->requirements = null;
    }
    if (array_key_exists('data', $data)) {
      $this->data = $data['data'];
    } else {
      $this->data = null;
    }

    if (array_key_exists('previewPath', $data)) {
      $this->previewPath = $data['previewPath'];
    } else {
      $this->previewPath = null;
    }

    return true;
  }
}
