<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileCollection;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
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
use App\Models\UserLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $userService;
    protected $taskService;
    protected $fileService;
    protected $groupService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(UserService $userService, TaskService $taskService, FileService $fileService, GroupService $groupService)
    {
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->fileService = $fileService;
        $this->groupService = $groupService;
    }

    public function index(Request $request)
    {
        $users = null;

        // if ($request->has('groupId'))  {
        //     $users = $this->userService->getAllByGroup($request->groupId);
        // }else  if ($request->has('search')) {
        //     $users = $this->userService->getBySearch($request->search);
        // } else {
        //     $users = $this->userService->getAll();
        // }
        $users = $this->userService->getAll($request->all());
        return new UserCollection($users);
    }

    public function auth()
    {
        $user = Auth::user();
            return new UserResource($user);
    }
    public function checkAuth()
    {
        $isAuth = Auth::check();

        return response()->json([
            'isAuth' => $isAuth
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userService->getOne($id);

        if ($user == null) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }

        return new UserResource($user);
    }

    public function getLogs($id)
    {
        $logs = $this->userService->getLogs($id);
        if ($logs == null) {
            return response()->json([
                'message' => 'Logs Not Found.'
            ], 404);
        }
        return new UserLogsCollection($logs);
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        UserLog::create([
            'user_id' => $userId,
            'message' => "Пользователь вышел из системы",
            'type' => 'logout'
        ]);

        return  new JsonResponse('', 204);
    }

    public function block($id)
    {
        if ($this->userService->block($id) == null) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => "User successfully blocked"
        ], 200);
    }

    public function unblock($id)
    {
        if ($this->userService->unblock($id) == null) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => "User successfully unblocked"
        ], 200);
    }

    public function destroy($id)
    {
        if ($this->userService->delete($id) == null) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => "User successfully deleted"
        ], 200);
    }
    public function store(Request $request)
    {
        $input = $request->all();
        Validator::make($input, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ])->validate();

        $user = $this->userService->create($input);

        return new UserResource($user);
    }
}
