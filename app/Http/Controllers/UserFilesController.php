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

class UserFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    protected $fileService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct( FileService $fileService)
    {
        $this->fileService = $fileService;
    }





    public function index(Request $request)
    {

        $input = $request->all();
        $input['userId'] = Auth::id();
        $files = $this->fileService->getAll($input);

        return new FileCollection($files);
    }

    public function show($id)
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

    public function destroy($id)
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

    public function destroyBanch(Request $request)
    {

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
}
