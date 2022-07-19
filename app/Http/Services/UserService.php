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
  public function getAll($search, $groupId)
  {  
    $query = User::query();

    if ($groupId != null) {
      $query = Group::Find($groupId)->users()->getQuery();
    }  

    $query->when($search != null, function($q) use ($search) {
      return $q->where(function ($query) use ($search) {
        return $query->where('first_name', 'ilike', '%'.$search.'%')
          ->orWhere('last_name', 'ilike', '%'.$search.'%')
          ->orWhere('email', 'ilike', '%'.$search.'%')
          ->orWhere('id', 'ilike', '%'.$search.'%')
          ->orWhere('created_at', 'ilike', '%'.$search.'%');
      });
    });
    return $query->orderBy('id')->paginate(15);
  }
  public function getOne($id)
  {
    $user = User::find($id);
    if (!$user) {
      return null;
    }
    return $user;
  }

  public function getLogs($id) {
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

  public function create($input) {
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
