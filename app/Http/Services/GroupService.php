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
use App\Notifications\GroupNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class GroupService
{
  public function getAll($input)
  {
    $query = Group::query();

    if (isset($input['userId'])) {
      if (isset($input['requests']) && filter_var($input['requests'], FILTER_VALIDATE_BOOLEAN)) {
        $query = User::Find($input['userId'])->groups()->whereNull('verified')->getQuery();
      } else {
        $query = User::Find($input['userId'])->groups()->whereNotNull('verified')->getQuery();
      }
    }

    $query->when(isset($input['ownerId']), function ($q) use ($input) {
      return $q->where('owner_id', $input['ownerId']);
    });

    $query->when(isset($input['title']), function ($q) use ($input) {
      return $q->where('title', 'ilike', '%' . $input['title'] . '%');
    });

    $query->when(isset($input['id']), function ($q) use ($input) {
      return $q->where('id', $input['id']);
    });

    $query->when(isset($input['date']), function ($q) use ($input) {
      return $q->where('created_at', '>=', $input['date']);
    });

    $query->when(isset($input['any']), function ($q) use ($input) {
      return $q->where(function ($query) use ($input) {
        return $query->where('title', 'ilike', '%' . $input['any'] . '%')
          ->orWhere('id', 'ilike', '%' . $input['any'] . '%')
          ->orWhere('created_at', 'ilike', '%' . $input['any'] . '%');
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
      } else if ($sortBy == 'date') {
        return $q->orderBy('created_at', $descending ? 'desc' : 'asc');
      } else {
        return $q->orderBy('groups.id', $descending ? 'desc' : 'asc');
      }
    }, function ($q) {
      return $q->orderBy('groups.id');
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

    return  $query->selectRaw("*, groups.id as id")->paginate($paginationSize);
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

    if ($group->owner_id == $userId || User::find($userId)->role_id == 1) {
      $users = $group->users;
      $group->delete();
      UserLog::create([
        'user_id' => $userId,
        'message' => 'Удалил группу ' . $group->id,
        'type'    => 'delete'
      ]);
      Notification:: send($users, new GroupNotification("Группа «" . $group->title . "» была удалена"));
      return true;
    } else {
      return false;
    }
  }

  public function create($input)
  {
    $group = Group::create([
      'title'       => $input['title'],
      'description' => $input['description'],
      'type_id'     => $input['type'],
      'owner_id'    => $input['ownerId'],
    ]);
    GroupUser::create([
      'group_id' => $group->id,
      'user_id'  => $input['ownerId'],
    ]);
    UserLog::create([
      'user_id' => $input['ownerId'],
      'message' => 'Создал группу ' . $group->id,
      'type'    => 'store'
    ]);
    return $group;
  }

  public function update($id, $input)
  {
    $group              = Group::find($id);
    $group->forceFill([
      'title'       => $input['title'],
      'description' => $input['description'],
      'type_id'     => $input['type']
    ]);
    $group->title       = $input['title'];
    $group->description = $input['description'];
    $group->type_id     = $input['type'];
    $group->save();

    UserLog::create([
      'user_id' => $group->owner_id,
      'message' => 'Изменил информацию о группе' . $id,
      'type'    => 'change'
    ]);

    return true;
  }

  public function addUsers($users, $groupId)
  {
    foreach ($users as $id) {
      $gu = GroupUser::where('user_id', $id)->where('group_id', $groupId)->first();
      if ($gu == null) {
        $group = Group::find($groupId);
        $user  = User::find($id);

        GroupUser::create([
          'user_id'  => $id,
          'group_id' => $groupId
        ]);
        $group->owner->notify(new GroupNotification("Пользователь " . $user->first_name . ' ' . $user->last_name . ' присоединился к группе «' . $group->title . '»'));

        $user->notify(new GroupNotification('Вы присоединились к группе «' . $group->title . '»'));
        UserLog::create([
          'user_id' => $id,
          'message' => 'Был добавлен в группу ' . $groupId,
          'type'    => 'store'
        ]);
      }
    }
    return true;
  }
  public function addUser($userId, $groupId)
  {
    $gu    = GroupUser::where('user_id', $userId)->where('group_id', $groupId)->first();
    $user  = User::find($userId);
    $group = Group::find($groupId);
    if ($gu == null) {
      if ($group->type_id == 1) {
        GroupUser::create([
          'user_id'  => $userId,
          'group_id' => $groupId
        ]);
        UserLog::create([
          'user_id' => $userId,
          'message' => 'Был добавлен в открытую группу ' . $groupId,
          'type'    => 'store'
        ]);
        $group->owner->notify(new GroupNotification("Пользователь " . $user->first_name . ' ' . $user->last_name . ' присоединился к группе «' . $group->title . '»'));

        $user->notify(new GroupNotification('Вы присоединились к группе «' . $group->title . '»'));
      } else {
        GroupUser::create([
          'user_id'  => $userId,
          'group_id' => $groupId,
          'verified' => null
        ]);
        UserLog::create([
          'user_id' => $userId,
          'message' => 'Был добавлен в закрытую группу ' . $groupId,
          'type'    => 'store'
        ]);
        $group->owner->notify(new GroupNotification("Пользователь " . $user->first_name . ' ' . $user->last_name . ' хочет присоединиться к группе «' . $group->title . '»'));

        $user->notify(new GroupNotification('Ваша заявка на вступление в группу «' . $group->title . '» ожидает подтверждения'));
      }
    }
    return true;
  }

  public function verifyUser($userId, $groupId)
  {
    $gu = GroupUser::where('user_id', $userId)->where('group_id', $groupId)->first();
    if ($gu != null) {
      $gu->verified = now();
      $gu->save();
    }
    User:: find($userId)->notify(new GroupNotification('Вы присоединились к группе «' . Group::find($groupId)->title . '»'));
    return true;
  }

  public function removeUser($userId, $groupId)
  {
    $gu = GroupUser::where('user_id', $userId)->where('group_id', $groupId)->first();
    printf($groupId . ' ' .  $userId);
    if ($gu == null) {
      return null;
    }
    $gu->delete();
    UserLog::create([
      'user_id' => $userId,
      'message' => 'Был исключен из группы' . $groupId,
      'type'    => 'store'
    ]);
    User:: find($userId)->notify(new GroupNotification('Вы покинули группу «' . Group::find($groupId)->title . '»'));
    return true;
  }
  public function getTypes()
  {
    return GroupType:: all();
  }
}
