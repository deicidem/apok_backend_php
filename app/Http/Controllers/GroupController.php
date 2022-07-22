<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Services\GroupService;
use Illuminate\Http\Request;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupTypeResource;
use App\Http\Services\Dto\GroupDto;
use App\Models\GroupType;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
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
    public function __construct(GroupService $myService)
    {
        $this->service = $myService;
    }

    public function index(Request $request)
    {
        $groups = $this->service->getAll($request->all());

        return new GroupCollection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dto = [
            'title'   => $request['title'],
            'type'    => $request['type'],
            'ownerId' => Auth::id()
        ];

        $group = $this->service->create($dto);

        return response()->json([
            'group' => $group
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
        $group = $this->service->getOne($id);

        if ($group == null) {
            return response()->json([
                'message' => 'Group Not Found.'
            ], 404);
        }

        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = $this->service->delete($id, Auth::id());

        if ($res == null) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }

        return response()->json([
            'message' => "Group successfully deleted"
        ], 200);
    }

    public function addUsers($groupId, Request $request)
    {
        $res = $this->service->addUsers($request->all()['users'], $groupId);

        if ($res == null) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }

        return response()->json([
            'message' => "Group successfully deleted"
        ], 200);
    }
    public function getTypes()
    {
        $types = $this->service->getTypes();
        return GroupTypeResource::collection($types);
    }
}
