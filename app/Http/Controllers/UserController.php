<?php

namespace App\Http\Controllers;

use App\Http\Services\Dto\TaskInputDto;
use App\Http\Services\TaskService;
use App\Http\Services\UserService;
use App\Http\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function __construct(UserService $userService, TaskService $taskService, FileService $fileService)
    {
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        $users = null;  
        if ($request->has('search')) {
            $users = $this->userService->getBySearch($request->search);
        } else {
            $users = $this->userService->getAll();
        }

        return response()->json([
            'users' => $users
        ], 200);
    }

    public function auth()
    {
        $user = $this->userService->getOne(Auth::id());

        return response()->json([
            'user' => $user
        ], 200);
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

        return response()->json([
            'user' => $user
        ], 200);
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
        
        return response()->json([
            'user' => $user,
        ]);
    }

    public function getTasks()
    {
        $tasks = $this->taskService->getAllByUser(Auth::id());

        return response()->json([
            'tasks' => $tasks
        ], 200);
    }
    public function getTask($id)
    {
        $task = $this->taskService->getOneByUser(Auth::id(), $id);

        if ($task == null) {
            return response()->json([
                'message' => 'Task Not Found.'
            ], 404);
        }

        return response()->json([
            'task' => $task
        ], 200);
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
            $dto = new TaskInputDto([
                'dzzs'    => $request['dzzs'],
                'planId'  => $request['planId'],
                'vectors' => $request['vectors'],
                'files'   => $request['files'],
                'params'  => $request['params'],
                'links'   => json_decode($request['links']),
            ]);

            $this->taskService->post($dto);

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


    public function getFiles() {
    
        $files = $this->fileService->getAllByUser(Auth::id());

        return response()->json([
            'files' => $files
        ]);
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
            $res = $this->fileService->isFileDeletable($id);
            array_push($deletable, [
                'id' => $id,
                'delete' => $res
            ]);
        }

        

        foreach ($deletable as $file) {
            if ($file['delete']) {
                $res = $this->fileService->deleteUserFile($file['id']);
                if ($res == null) {
                    return response()->json([
                        'message' => 'File  not found'
                    ], 404);
                }
            }            
        }

        return response()->json([
            "deleted" => $deletable
        ], 200);
    }
    
}
