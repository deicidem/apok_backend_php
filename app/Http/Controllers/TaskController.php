<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Services\TaskService;
use Illuminate\Http\Request;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Services\Dto\FileDto;
use App\Http\Services\Dto\TaskInputDto;
use App\Http\Services\FileService;
use App\Mail\EmailVerification;
use App\Models\Dzz;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    protected $service;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(TaskService $myService)
    {
        $this->service = $myService;
    }

    public function index()
    {

        try {
        // Mail::to('uyc100@yandex.ru')->send(new EmailVerification);

            $tasks = $this->service->getAll();

            return response()->json([
                'tasks' => $tasks
            ], 200);
        } catch (\Throwable $th) {
            $tasks = $this->service->getAll();

            return response()->json([
                'tasks' => $tasks,
                'message' => $th->getMessage()
            ], 200);
        }

        
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
            $dto = new TaskInputDto([
                'dzzs'    => $request['dzzs'],
                'planId'  => $request['planId'],
                'vectors' => $request['vectors'],
                'files'   => $request['files'],
                'params'  => $request['params'],
                'links'   => json_decode($request['links']),
            ]);

            $this->service->post($dto);

            return response()->json([
                'message' => "Task created"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = $this->service->getOne($id);

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // $dto = new TaskOutputDto([
            //     'id'       => $id,
            //     'title'    => $request['title'],
            //     'statusId' => $request['statusId'],
            //     'dzzId'    => $request['dzzId'],
            //     'result'   => $request['result']
            // ]);;

            // $res = $this->service->update($dto);

            // if ($res == null) {
            //     return response()->json([
            //         'message' => 'Task not found'
            //     ], 404);
            // }

            return response()->json([
                'message' => "Task successfully updated"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went wrong"
            ], 500);
        }
    }

    public function deleteUserTasks(Request $request) {
        $deletable = [];

        foreach ($request['ids'] as $id) {
            $res = $this->service->isTaskDeletable($id);
            array_push($deletable, [
                'id' => $id,
                'delete' => $res
            ]);
        }

        

        foreach ($deletable as $task) {
            if ($task['delete']) {
                $res = $this->service->deleteUserTask($task['id']);
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
    public function destroy($id)
    {
        $res = $this->service->delete($id);

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
