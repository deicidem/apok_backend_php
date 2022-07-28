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

class UserGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $userService;
    protected $groupService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(UserService $userService, GroupService $groupService)
    {
        $this->userService = $userService;
        $this->groupService = $groupService;
    }

    

    public function index(Request $request)
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
    // public function getGroup($id)
    // {
    //     $group = $this->groupService->getOneByUser(Auth::id(), $id);

    //     if ($group == null) {
    //         return response()->json([
    //             'message' => 'Group Not Found.'
    //         ], 404);
    //     }

    //     return new GroupResource($group);
    // }
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
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $input['ownerId'] = Auth::id();
            $this->groupService->create($input);

            return response()->json([
                'message' => "Group created"
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
    public function destroy($id)
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

    public function update($id, Request $request)
    {
        $res = $this->groupService->update($id, $request->all());

        if ($res == null) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }

        return response()->json([
            'message' => "Group successfully deleted"
        ], 200);
    }

    public function generateInvite($groupId, Request $request)
    {
        $time = now();
        $url = null;
        if ($request->has('type') && $request->has('value')) {
            $type = $request->type;
            $value = $request->value;
            if ($type == 'minutes') {
                $time->addMinutes($value);
            } else if ($type == 'hours') {
                $time->addHours($value);
            } else if ($type == 'days') {
                $time->addDays($value);
            } else if ($type == 'months') {
                $time->addMonths($value);
            } else if ($type == 'years') {
                $time->addYears($value);
            } else {
                $time->addWeek();
            }
            $url = URL::temporarySignedRoute('join-group', $time, ['group' => $groupId]);
        } else {
            $url = URL::temporarySignedRoute('join-group', $time->addWeek(), ['group' => $groupId]);
        }
        $url = str_replace('192.168.1.104/apok_backend_php/public', 'localhost:8080', $url);
        return new JsonResponse(['data' => $url], 200);
    }

    public function join(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return redirect('http://localhost:8080?status=401');
        };
        $this->groupService->addUser(Auth::id(), $request->group);
        return redirect('http://localhost:8080?status=200');
    }

    public function quit($groupId)
    {   
       $gu =  $this->groupService->removeUser(Auth::id(), $groupId);
        return new JsonResponse($gu);
    }
    public function excludeUser($groupId, $userId)
    {
        $this->groupService->removeUser($userId, $groupId);
        return new JsonResponse(null, 200);
    }
    public function verifyUser($groupId, $userId)
    {
        $this->groupService->verifyUser($userId, $groupId);
        return new JsonResponse(null, 200);
    }
}
