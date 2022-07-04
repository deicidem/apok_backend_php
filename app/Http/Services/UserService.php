<?php

namespace App\Http\Services;

use App\Models\User;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\SearchDto;
use App\Http\Services\Dto\UserDto;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
class UserService
{
  public function getAll()
  {
    $users  = User::all();
    $result = [];
    foreach ($users as $user) {
      array_push($result, new UserDto([
        'id'    => $user->id,
        'firstName' => $user->first_name,
        'lastName' => $user->last_name,
        'email'  => $user->email,
        'date' => $user->created_at,
        'role' => $user->role->name,
      ]));
    };
    return $result;
  }

  public function getOne($id)
  {
    $user = User::find($id);
    if (!$user) {
      return null;
    }
    return new UserDto([
      'id'    => $user->id,
      'firstName' => $user->first_name,
      'lastName' => $user->last_name,
      'email'  => $user->email,
      'date' => $user->created_at,
      'role' => $user->role->name,
    ]);
  }

  public function delete($id)
  {
    $user = User::find($id);
    if (!$user) {
      return null;
    }
    $user->delete();

    return true;
  }
}
