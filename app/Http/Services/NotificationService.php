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

class NotificationService
{
  public function getAll($input)
  {
    $query = User::find($input['userId'])->notifications()->getQuery();

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

    return  $query->orderBy('created_at', 'desc')->paginate($paginationSize);
  }

  public function delete($notificationId, $userId)
  {
    User::find($userId)->notifications->where('id', $notificationId)->first()->delete();
    return true;
  }

  public function unreadCount($userId) {
    return User::find($userId)->unreadNotifications()->count();
  }
  public function markAsRead($notificationId, $userId) {
    User::find($userId)->notifications->where('id', $notificationId)->first()->markAsRead();
    return true;
  }
}
