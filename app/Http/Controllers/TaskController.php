<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Services\TaskService;
use Illuminate\Http\Request;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Services\Dto\TaskDto;

class TaskController extends Controller
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
    public function __construct(TaskService $myService)
    {
        $this->service = $myService;
    }

    public function index()
    {
        $tasks = $this->service->getAll();
        return response()->json([
            'tasks' => $tasks
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request)
    {
        try {
            $dto = new TaskDto([
                'title'  => $request['title'],
                'statusId' => $request['statusId'],
                'dzzId' => $request['dzzId'],
                'result' => $request['result'],
            ]);
            $this->service->post($dto);
            return response()->json([
                'message' => "Task created"
            ], 200);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $dto = new TaskDto([
                'id'     => $id,
                'title'  => $request['title'],
                'statusId' => $request['statusId'],
                'dzzId' => $request['dzzId'],
                'result' => $request['result']
            ]);;

            $res = $this->service->update($dto);

            if ($res == null) {
                return response()->json([
                    'message' => 'Task not found'
                ], 404);
            }

            return response()->json([
                'message' => "Task successfully updated"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went wrong"
            ], 500);
        }
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
