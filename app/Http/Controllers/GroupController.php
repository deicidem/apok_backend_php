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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $input = Validator::make($request->all(), [
            'ownerId' => ['nullable', 'numeric', 'exists:users,id'],
            'userId'  => ['nullable', 'numeric', 'exists:users,id'],
            'size'    => ['nullable', 'numeric'],
            'page'    => ['nullable', 'numeric'],
            'desc'    => ['nullable', Rule::in('true', 'false', '1', '0', 1, 0, true, false)],
            'sortBy'  => ['nullable', 'string'],
            'title'   => ['nullable', 'string'],
            'id'      => ['nullable', 'numeric'],
            'date'    => ['nullable', 'date'],
            'any'     => ['nullable', 'string'],
        ])->validated();
        $groups = $this->service->getAll($input);

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
        $input = $request->all();
        $input['ownerId'] = Auth::id();
        $input = Validator::make($input, [
            'ownerId'     => ['required', 'numeric', 'exists:users,id'],
            'title'       => ['required', 'string', 'unique:groups'],
            'description' => ['required', 'string'],
            'type'        => ['required', 'numeric', 'exists:group_types,id'],
        ])->validated();
        print_r($input);
        $group = $this->service->create($input);

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

    public function update($id, Request $request)
    {
        $input = Validator::make($request->all(), [
            'title'       => ['required', Rule::unique('groups')->ignore($id), 'string'],
            'description' => ['required', 'string'],
            'type'        => ['required', 'numeric', 'exists:group_types,id'],
        ])->validated();

        $res = $this->service->update($id, $input);

        if ($res == null) {
            return response()->json([
                'message' => 'Group not found'  
            ], 404);
        }

        return response()->json([
            'message' => "Group successfully deleted"
        ], 200);
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
    public function excludeUser($groupId, $userId)
    {
        $this->service->removeUser($userId, $groupId);
        return new JsonResponse(null, 200);
    }
}
