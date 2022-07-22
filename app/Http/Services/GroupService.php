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
  public function getAll($input)
  {
    $query = Group::query();

    if (isset($input['userId'])) {
      $query = User::Find($input['userId'])->groups()->getQuery();
    } 

    $query->when(isset($input['ownerId']), function($q) use ($input) {
      return $q->where('owner_id', $input['ownerId']);
    });

    $query->when(isset($input['title']), function ($q) use ($input) {
      return $q->where('title', 'ilike', '%' . $input['title'] . '%');
    });
    $query->when(isset($input['id']), function ($q) use ($input) {
      return $q->where('id', 'ilike', '%' . $input['id'] . '%');
    });
    $query->when(isset($input['date']), function ($q) use ($input) {
      return $q->where('created_at', '>=', $input['date']);
    });
    $query->when(isset($input['any']), function($q) use ($input) {
      return $q->where(function ($query) use ($input) {
        return $query->where('title', 'ilike', '%'.$input['any'].'%')
          ->orWhere('id', 'ilike', '%'.$input['any'].'%')
          ->orWhere('created_at', 'ilike', '%'.$input['any'].'%');
      });
  });


    $query->when(isset($input['sortBy']), function ($q) use ($input) {
      $descending = false;
      if (isset($input['desc'])) {
        $descending = filter_var($input['desc'], FILTER_VALIDATE_BOOLEAN);  
      }

      $sortBy = $input['sortBy'];
      if ($sortBy == 'title') {
        return $q->orderBy('title', $descending ? 'desc' : 'asc');
      }  else if ($sortBy == 'date') {
        return $q->orderBy('created_at', $descending ? 'desc' : 'asc');
      }  else {
        return $q->orderBy('id', $descending ? 'desc' : 'asc');
      }
    }, function ($q) {
      return $q->orderBy('id');
    });

    $paginationSize = 15;
    if (isset($input['size'])) {
      if ($input['size'] > 50) {
        $paginationSize = 50;
      } else if ($input['size'] < 1) {
        $paginationSize = 1;
      } else {
        $paginationSize = $input['size'];
      }
    }

    return  $query->paginate($paginationSize);
  }


  public function getOne($id)
  {
    $group = Group::find($id);
    if (!$group) {
      return null;
    }
    return $group;
  }

  public function delete($id, $userId)
  {
    $group = Group::find($id);
    if (!$group) {
      return null;
    }
    if ($group->owner_id != $userId) {
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

  public function create($input)
  {
    $group = Group::create([
      'title'    => $input['title'],
      'type_id'  => $input['type'],
      'owner_id' => $input['ownerId'],
    ]);
    GroupUser::create([
      'group_id' => $group->id,
      'user_id'  => $input['ownerId'],
    ]);
    UserLog::create([
      'user_id' => $input['ownerId'],
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
