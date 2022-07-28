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

class UserTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $taskService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    

    public function index(Request $request)
    {
        $input = $request->all();
        $input['userId'] = Auth::id();
        $tasks = $this->taskService->getAll($input);
        return new TaskCollection($tasks);
    }
    public function show($id)
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
    public function store(Request $request)
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

    public function destroyBanch(Request $request)
    {
        $deletable = [];

        foreach ($request['ids'] as $id) {
            // $res = $this->taskService->isTaskDeletable($id);
            array_push($deletable, [
                'id' => $id,
            ]);
        }

        foreach ($deletable as $task) {
            $res = $this->taskService->deleteUserTask($task['id']);
            if ($res == null) {
                return response()->json([
                    'message' => 'Task  not found'
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
    public function destroy($id)
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

    public function update($id, Request $request) {
        $res = $this->taskService->update($id, $request->all());

        if ($res == null) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'message' => "Task successfully deleted"
        ], 200);
    }
}
