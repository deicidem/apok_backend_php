<?php

namespace App\Http\Services;

use App\Models\User;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\SearchDto;
use App\Http\Services\Dto\UserDto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
class UserService
{
  public function getAll()
  {
    $users  = User::orderBy('id')->get();
    $result = [];
    foreach ($users as $user) {
      array_push($result, new UserDto([
        'id'        => $user->id,
        'firstName' => $user->first_name,
        'lastName'  => $user->last_name,
        'email'     => $user->email,
        'date'      => $user->created_at,
        'role'      => $user->role->name,
        'blocked'   => $user->is_blocked
      ]));
    };
    return $result;
  }

  public function getBySearch($search)
  {
    $users = User::where('first_name', 'ilike', '%'.$search.'%')
      ->orWhere('last_name', 'ilike', '%'.$search.'%')
      ->orWhere('email', 'ilike', '%'.$search.'%')
      ->orWhere('id', 'ilike', '%'.$search.'%')
      ->orWhere('created_at', 'ilike', '%'.$search.'%')
      ->orderBy('id')->get();
    $result = [];
    foreach ($users as $user) {
      array_push($result, new UserDto([
        'id'        => $user->id,
        'firstName' => $user->first_name,
        'lastName'  => $user->last_name,
        'email'     => $user->email,
        'date'      => $user->created_at,
        'role'      => $user->role->name,
        'blocked'   => $user->is_blocked
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
      'id'        => $user->id,
      'firstName' => $user->first_name,
      'lastName'  => $user->last_name,
      'email'     => $user->email,
      'date'      => $user->created_at,
      'role'      => $user->role->name,
      'blocked'   => $user->is_blocked
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

  public function block($id)
  {
    $user = User::find($id);
    if (!$user) {
      return null;
    }
    $user->is_blocked = true;
    $user->save();

    return true;
  }

  public function unblock($id)
  {
    $user = User::find($id);
    if (!$user) {
      return null;
    }
    $user->is_blocked = false;
    $user->save();

    return true;
  }

  public function create($input) {
    $user = User::create([
      'first_name' => $input['firstName'],
      'last_name'  => $input['lastName'],
      'email'      => $input['email'],
      'password'   => Hash::make($input['password']),
      'role_id'    => 2
    ]);
    $user->sendEmailVerificationNotification();
    return new UserDto([
      'id'        => $user->id,
      'firstName' => $user->first_name,
      'lastName'  => $user->last_name,
      'email'     => $user->email,
      'date'      => $user->created_at,
      'role'      => $user->role->name,
      'blocked'   => $user->is_blocked
    ]);
  }
}
