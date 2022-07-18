<?php

namespace App\Http\Services;

use App\Models\Group;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\SearchDto;
use App\Http\Services\Dto\GroupDto;
use App\Models\GroupType;
use App\Models\GroupUser;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class GroupService
{
  public function getAll()
  {
    return Group::orderBy('id')->paginate(10);
    // $result = [];
    // foreach ($groups as $group) {
    //   array_push($result, new GroupDto([
    //     'id'        => $group->id,
    //     'title'     => $group->title,
    //     'ownerId'   => $group->owner_id,
    //     'ownerName' => $group->owner->first_name . " " . $group->owner->last_name,
    //     'type'      => $group->type->title
    //   ]));
    // };
    // return $result;
  }

  public function getAllByOwner($userId)
  {
    return Group::where('owner_id', $userId)->orderBy('id')->paginate(10);
    // $result = [];
    // foreach ($groups as $group) {
    //   array_push($result, new GroupDto([
    //     'id'        => $group->id,
    //     'title'     => $group->title,
    //     'ownerId'   => $group->owner_id,
    //     'ownerName' => $group->owner->first_name . " " . $group->owner->last_name,
    //     'type'      => $group->type->title
    //   ]));
    // };
    // return $result;
  }

  public function getAllByUser($userId)
  {
    return User::Find($userId)->groups()->paginate(10);
    // $groups = GroupUser::where('user_id', $userId)->orderBy('group_id')->get();
    // $result = [];
    // foreach ($groups as $group) {
    //   $g = $group->group;
    //   array_push($result, new GroupDto([
    //     'id'        => $g->id,
    //     'title'     => $g->title,
    //     'ownerId'   => $g->owner_id,
    //     'ownerName' => $g->owner->first_name . " " . $g->owner->last_name,
    //     'type'      => $g->type->title
    //   ]));
    // };
    // return $result;
  }

  public function getBySearch($search)
  {
    Group::orderBy('id')->paginate(10);
    // $groups = Group::where('first_name', 'ilike', '%' . $search . '%')
    //   ->orWhere('last_name', 'ilike', '%' . $search . '%')
    //   ->orWhere('email', 'ilike', '%' . $search . '%')
    //   ->orWhere('id', 'ilike', '%' . $search . '%')
    //   ->orWhere('created_at', 'ilike', '%' . $search . '%')
    //   ->orderBy('id')->get();
    // $result = [];
    // foreach ($groups as $group) {
    //   array_push($result, new GroupDto([
    //     'id'        => $group->id,
    //     'firstName' => $group->first_name,
    //     'lastName'  => $group->last_name,
    //     'email'     => $group->email,
    //     'date'      => $group->created_at,
    //     'role'      => $group->role->name,
    //     'blocked'   => $group->is_blocked
    //   ]));
    // };
    // return $result;
  }

  public function getOne($id)
  {
    $group = Group::find($id);
    if (!$group) {
      return null;
    }
    return $group;
  }

  public function delete($id)
  {
    $group = Group::find($id);
    if (!$group) {
      return null;
    }
    $group->delete();
    UserLog::create([
      'user_id' => $group->owner_id,
      'message' => 'Удалил группу '.$group->id,
      'type' => 'delete'
    ]);
    return true;
  } 

  public function create(GroupDto $dto)
  {
    $group = Group::create([
      'title'    => $dto->title,
      'type_id'  => $dto->type,
      'owner_id' => $dto->ownerId,
    ]);
    UserLog::create([
      'user_id' => $dto->ownerId,
      'message' => 'Создал группу '.$group->id,
      'type' => 'store'
    ]);
    return $group;
  }

  public function addUsers($users, $groupId) {
    foreach ($users as $id) {
      $gu = GroupUser::where('user_id', $id)->where('group_id', $groupId)->first();
      if ($gu == null) {
        GroupUser::create([
          'user_id' => $id,
          'group_id' => $groupId
        ]);
        UserLog::create([
          'user_id' => $id,
          'message' => 'Был добавлен в группу '.$groupId,
          'type' => 'store'
        ]);
      }
    }
    return true;
  }

  public function getTypes()
  {
    return GroupType::all();
  }
}
