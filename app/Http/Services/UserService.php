<?php

namespace App\Http\Services;

use App\Models\User;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\SearchDto;
use App\Http\Services\Dto\UserDto;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\UserLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class UserService
{
  public function getAll($input)
  {
    $query = User::query();

    if (isset($input['groupId'])) {
      if (isset($input['requests']) && filter_var($input['requests'], FILTER_VALIDATE_BOOLEAN)) {
        $query = Group::Find($input['groupId'])->users()->whereNull('verified')->getQuery();
      }else {
        $query = Group::Find($input['groupId'])->users()->whereNotNull('verified')->getQuery();
      }
    }


    $query->when(isset($input['firstName']), function ($q) use ($input) {
      return $q->where('first_name', 'ilike', '%' . $input['firstName'] . '%');
    });
    $query->when(isset($input['lastName']), function ($q) use ($input) {
      return $q->where('last_name', 'ilike', '%' . $input['lastName'] . '%');
    });
    $query->when(isset($input['email']), function ($q) use ($input) {
      return $q->where('email', 'ilike', '%' . $input['email'] . '%');
    });
    $query->when(isset($input['id']), function ($q) use ($input) {
      return $q->where('id', 'ilike', '%' . $input['id'] . '%');
    });
    $query->when(isset($input['date']), function ($q) use ($input) {
      return $q->where('created_at', '>=', $input['date']);
    });
    $query->when(isset($input['any']), function($q) use ($input) {
      return $q->where(function ($query) use ($input) {
        return $query->where('first_name', 'ilike', '%'.$input['any'].'%')
          ->orWhere('last_name', 'ilike', '%'.$input['any'].'%')
          ->orWhere('email', 'ilike', '%'.$input['any'].'%')
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
      if ($sortBy == 'firstName') {
        return $q->orderBy('first_name', $descending ? 'DESC' : 'ASC');
      } else if ($sortBy == 'lastName') {
        return $q->orderBy('last_name', $descending ? 'DESC' : 'ASC');
      } else if ($sortBy == 'date') {
        return $q->orderBy('created_at', $descending ? 'DESC' : 'ASC');
      } else if ($sortBy == 'email') {
        return $q->orderBy('email', $descending ? 'DESC' : 'ASC');
      } else {
        return $q->orderBy('users.id', $descending ? 'DESC' : 'ASC');
      }
    }, function ($q) {
      return $q->orderBy('users.id');
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

    return  $query->selectRaw("*, users.id as id")->paginate($paginationSize);
  }
  public function getOne($id)
  {
    $user = User::find($id);
    if (!$user) {
      return null;
    }
    return $user;
  }

  public function getLogs($id)
  {
    if (UserLog::where('user_id', $id)->exists()) {
      return UserLog::where('user_id', $id)->orderBy('created_at', "DESC")->paginate(10);
    } else {
      return null;
    }
    // foreach ($logs as $log) { 
    //   array_push($result, [
    //     'date' => $log->created_at,
    //     'message' => $log->message,
    //     'type' => $log->type
    //   ]);
    // }
    // return $result;
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
    UserLog::create([
      'user_id' => $id,
      'message' => 'Был заблокирован',
      'type' => 'block'
    ]);
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
    UserLog::create([
      'user_id' => $id,
      'message' => 'Был разблокирован',
      'type' => 'block'
    ]);
    return true;
  }

  public function create($input)
  {
    $user = User::create([
      'first_name' => $input['firstName'],
      'last_name'  => $input['lastName'],
      'email'      => $input['email'],
      'password'   => Hash::make($input['password']),
      'role_id'    => 2
    ]);
    $user->sendEmailVerificationNotification();
    UserLog::create([
      'user_id' => $user->id,
      'message' => 'Был зарегистрирован',
      'type' => 'store'
    ]);
    return $user;
  }
}
