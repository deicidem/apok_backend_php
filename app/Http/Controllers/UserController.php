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

    protected $service;
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

    public function getLogs($id) {
        $logs = $this->userService->getLogs($id);
        if ($logs == null) {
            return response()->json([
                'message' => 'Logs Not Found.'
            ], 404);
        }
        return new UserLogsCollection($logs);
    }

    public function logout(Request $request) {
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
    public function store(Request $request) {
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

    public function getTasks(Request $request)
    {
        $input = $request->all();
        $input['userId'] = Auth::id();
        $tasks = $this->taskService->getAll($input);
        return new TaskCollection($tasks);
    }
    public function getTask($id)
    {
        $task = $this->taskService->getOneByUser(Auth::id(), $id);

        if ($task == null) {
            return response()->json([
                'message' => 'Task Not Found.'
            ], 404);
        }

        return new TaskResource($task);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTask(Request $request)
    {
        try {
            print_r($request->all());
            $input = [
                'dzzs'    => $request['dzzs'],
                'planId'  => $request['planId'],
                'vectors' => $request['vectors'],
                'files'   => $request['files'],
                'params'  => $request['params'],
                'note'    => $request['note'],
                'links'   => json_decode($request['links']),
                'userId' => Auth::id()
            ];

            $this->taskService->post($input);

            return response()->json([
                'message' => "Task created"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteTasks(Request $request)
    {
        $deletable = [];

        foreach ($request['ids'] as $id) {
            $res = $this->taskService->isTaskDeletable($id);
            array_push($deletable, [
                'id' => $id,
                'delete' => $res
            ]);
        }

        foreach ($deletable as $task) {
            if ($task['delete']) {
                $res = $this->taskService->deleteUserTask($task['id']);
                if ($res == null) {
                    return response()->json([
                        'message' => 'Task  not found'
                    ], 404);
                }
            }
        }

        return response()->json([
            "deleted" => $deletable
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTask($id)
    {
        $res = $this->taskService->deleteUserTask($id);

        if ($res == null) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'message' => "Task successfully deleted"
        ], 200);
    }


    public function getFiles(Request $request) {
    
        $input = $request->all();
        $input['userId'] = Auth::id();
        $files = $this->fileService->getAll($input);

        return new FileCollection($files);
    }

    public function deleteFile($id)
    {
        $res = $this->fileService->deleteUserFile($id);

        if ($res == null) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        return response()->json([
            'message' => "File successfully deleted"
        ], 200);
    }

    public function deleteFiles(Request $request) {

        $deletable = [];

        foreach ($request['ids'] as $id) {
            array_push($deletable, [
                'id' => $id,
            ]);
        }

        

        foreach ($deletable as $file) {
            $res = $this->fileService->deleteUserFile($file['id']);
            if ($res == null) {
                return response()->json([
                    'message' => 'File  not found'
                ], 404);
            }
        }

        return response()->json([
            "deleted" => $deletable
        ], 200);
    }
    
    public function getGroups(Request $request)
    {
        $input = $request->all();
        $groups = null;
        if ($request->owner) {
            $input['ownerId'] = Auth::id();
            $groups = $this->groupService->getAll($input);
        } else {
            $input['userId'] = Auth::id();
            $groups = $this->groupService->getAll($input);
        }
        return new GroupCollection($groups);
    }
    public function getGroup($id)
    {
        $group = $this->groupService->getOneByUser(Auth::id(), $id);

        if ($group == null) {
            return response()->json([
                'message' => 'Group Not Found.'
            ], 404);
        }

        return new GroupResource($group);
    }
    public function getUsersByGroup($groupId, Request $request)
    {
        $input = $request->all();
        $input['groupId'] = $groupId;
        $users = $this->userService->getAll($input);
        return new UserCollection($users);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createGroup(Request $request)
    {
        try {
            $dto = [
                'title'   => $request['title'],
                'type'    => $request['type'],
                'ownerId' => Auth::id()
            ];

            $this->groupService->create($dto);

            return response()->json([
                'message' => "Group created"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteGroups(Request $request)
    {
        $deletable = [];

        foreach ($request['ids'] as $id) {
            array_push($deletable, [
                'id' => $id,
            ]);
        }

        foreach ($deletable as $group) {
            $res = $this->groupService->delete($group['id'], Auth::id());
            if ($res == null) {
                return response()->json([
                    'message' => 'Group  not found'
                ], 404);
            }
        }

        return response()->json([
            "deleted" => $deletable
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteGroup($id)
    {
        $res = $this->groupService->delete($id, Auth::id());

        if ($res == null) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }

        return response()->json([
            'message' => "Group successfully deleted"
        ], 200);
    }

}
