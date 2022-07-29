<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Services\TaskService;
use Illuminate\Http\Request;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Http\Services\Dto\FileDto;
use App\Http\Services\Dto\TaskInputDto;
use App\Http\Services\FileService;
use App\Mail\EmailVerification;
use App\Models\Dzz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public function index(Request $request)
    {
        $input = Validator::make($request->all(), [
            'userId' => ['nullable', 'numeric', 'exists:users,id'],
            'size'   => ['nullable', 'numeric'],
            'page'   => ['nullable', 'numeric'],
            'desc'   => ['nullable', Rule::in('true', 'false', '1', '0', 1, 0, true, false)],
            'sortBy' => ['nullable', 'string'],
            'title'  => ['nullable', 'string'],
            'id'     => ['nullable', 'numeric'],
            'date'   => ['nullable', 'date'],
            'any'    => ['nullable', 'string'],
        ])->validate();
        $tasks = $this->service->getAll($input); 

        return new TaskCollection($tasks);
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

        return new TaskResource($task);
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
