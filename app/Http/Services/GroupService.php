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
  public function getAll($search, $userId, $ownerId )
  {
    $query = Group::query();

    if ($userId != null) {
      $query = User::Find($userId)->groups()->getQuery();
    } 

    $query->when($ownerId != null, function($q) use ($ownerId) {
      return $q->where('owner_id', $ownerId);
    });

    $query->when($search != null, function($q) use ($search) {
      return $q->where(function ($query) use ($search) {
        return $query->where('title', 'ilike', '%'.$search.'%')
          ->orWhere('id', 'ilike', '%'.$search.'%')
          ->orWhere('created_at', 'ilike', '%'.$search.'%');
      });
    });

    return $query->orderBy('id')->paginate(15);
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
