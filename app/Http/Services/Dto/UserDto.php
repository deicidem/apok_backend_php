<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class UserDto extends AbstractDto implements DtoInterface
{
  /* @var string */
  public $id;
  public $firstName;
  public $lastName;
  public $email;
  public $role;
  public $date;
  public $updated;
  public $blocked;
  public $logs;

  /* @return array */
  protected function configureValidatorRules(): array
  {
    return [
      'id'        => 'nullable',
      'firstName' => 'nullable',
      'lastName'  => 'nullable',
      'email'     => 'nullable',
      'role'      => 'nullable',
      'date'      => 'nullable',
      'updated'   => 'nullable',
      'blocked'   => 'nullable',
      'logs'   => 'nullable',
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

    $this->firstName = $data['firstName'];
    $this->lastName  = $data['lastName'];
    $this->email     = $data['email'];
    $this->role      = $data['role'];
    $this->date      = $data['date'];
    $this->updated   = $data['updated'];
    if (array_key_exists('blocked', $data)) {
      $this->blocked = $data['blocked'];
    } else {
      $this->blocked = null;
    }
    if (array_key_exists('logs', $data)) {
      $this->logs = $data['logs'];
    } else {
      $this->logs = null;
    }


    return true;
  }
}
