<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileCollection;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserLogsCollection;
use App\Http\Resources\UserResource;
use App\Http\Services\Dto\TaskInputDto;
use App\Http\Services\Dto\GroupDto;
use App\Http\Services\TaskService;
use App\Http\Services\UserService;
use App\Http\Services\FileService;
use App\Http\Services\GroupService;
use App\Http\Services\NotificationService;
use App\Models\UserLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class UserNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $service;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(NotificationService $service)
    {
        $this->service = $service;

    }

    public function index(Request $request)
    {
        $input = $request->all();
        $input['userId'] = Auth::id();

        // if ($request->has('groupId'))  {
        //     $users = $this->service->getAllByGroup($request->groupId);
        // }else  if ($request->has('search')) {
        //     $users = $this->service->getBySearch($request->search);
        // } else {
        //     $users = $this->service->getAll();
        // }
        $notifications = $this->service->getAll($input);
        return new NotificationCollection($notifications);
    }
    public function unreadCount() {
        return new JsonResponse($this->service->unreadCount(Auth::id()));
    }
    public function destroy($id)
    {
        if ($this->service->delete($id) == null) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => "User successfully deleted"
        ], 200);
    }
}
