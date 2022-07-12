<?php

namespace App\Http\Services;

use App\Models\Group;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\SearchDto;
use App\Http\Services\Dto\GroupDto;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class GroupService
{
  public function getAll()
  {
    $groups = Group::orderBy('id')->get();
    $result = [];
    foreach ($groups as $group) {
      array_push($result, new GroupDto([
        'id'        => $group->id,
        'title'     => $group->title,
        'ownerId'   => $group->owner_id,
        'ownerName' => $group->owner->first_name . " " . $group->owner->last_name,
        'type'      => $group->type->title
      ]));
    };
    return $result;
  }

  public function getAllByOwner($userId)
  {
    $groups = Group::where('owner_id', $userId)->orderBy('id')->get();
    $result = [];
    foreach ($groups as $group) {
      array_push($result, new GroupDto([
        'id'        => $group->id,
        'title'     => $group->title,
        'ownerId'   => $group->owner_id,
        'ownerName' => $group->owner->first_name . " " . $group->owner->last_name,
        'type'      => $group->type->title
      ]));
    };
    return $result;
  }

  public function getAllByUser($userId)
  {
    $groups = GroupUser::where('user_id', $userId)->orderBy('id')->get();
    $result = [];
    foreach ($groups as $group) {
      $g = $group->group;
      array_push($result, new GroupDto([
        'id'        => $g->id,
        'title'     => $g->title,
        'ownerId'   => $g->owner_id,
        'ownerName' => $g->owner->first_name . " " . $g->owner->last_name,
        'type'      => $g->type->title
      ]));
    };
    return $result;
  }

  public function getBySearch($search)
  {
    $groups = Group::where('first_name', 'ilike', '%' . $search . '%')
      ->orWhere('last_name', 'ilike', '%' . $search . '%')
      ->orWhere('email', 'ilike', '%' . $search . '%')
      ->orWhere('id', 'ilike', '%' . $search . '%')
      ->orWhere('created_at', 'ilike', '%' . $search . '%')
      ->orderBy('id')->get();
    $result = [];
    foreach ($groups as $group) {
      array_push($result, new GroupDto([
        'id'        => $group->id,
        'firstName' => $group->first_name,
        'lastName'  => $group->last_name,
        'email'     => $group->email,
        'date'      => $group->created_at,
        'role'      => $group->role->name,
        'blocked'   => $group->is_blocked
      ]));
    };
    return $result;
  }

  public function getOne($id)
  {
    $group = Group::find($id);
    if (!$group) {
      return null;
    }
    return new GroupDto([
      'id'        => $group->id,
      'title'     => $group->title,
      'ownerId'   => $group->owner_id,
      'ownerName' => $group->owner->first_name . " " . $group->owner->last_name,
      'type'      => $group->type->title
    ]);
  }

  public function delete($id)
  {
    $group = Group::find($id);
    if (!$group) {
      return null;
    }
    $group->delete();

    return true;
  } 

  public function create(GroupDto $dto)
  {
    Group::create([
      'title'    => $dto->title,
      'type_id'  => $dto->type,
      'owner_id' => $dto->ownerId,
    ]);
    return true;
  }
}
