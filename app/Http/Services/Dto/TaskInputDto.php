<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class TaskInputDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $planId;
  public $files;
  public $geography;
  public $dzzs;
  public $params;
  public $links;


  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'planId'  => 'required',
      'files'   => 'nullable',
      'vectors' => 'nullable',
      'dzzs' => 'nullable',
      'params' => 'nullable',
      'links' => 'nullable',
    ];
  }

  /**
   * @inheritDoc
   */
  protected function map($data): bool
  {
    if (array_key_exists('files', $data)) {
      $this->files = $data['files'];
    } else {
      $this->files = null;
    }
    if (array_key_exists('dzzs', $data)) {
      $this->dzzs = $data['dzzs'];
    } else {
      $this->dzzs = null;
    }
    if (array_key_exists('vectors', $data)) {
      $this->vectors = $data['vectors'];
    } else {
      $this->vectors = null;
    }
    if (array_key_exists('params', $data)) {
      $this->params = $data['params'];
    } else {
      $this->params = null;
    }
    $this->planId = $data['planId'];
    $this->links = $data['links'];
    return true;
  }
}
