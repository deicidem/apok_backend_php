<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Services\GroupService;
use Illuminate\Http\Request;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Services\Dto\GroupDto;

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
        $groups = null;
        if ($request->has('userId')) {
            $groups = $this->service->getAllByUser($request->userId);
        } else if ($request->has('ownerId')) {
            $groups = $this->service->getAllByOwner($request->ownerId);
        } else {
            $groups = $this->service->getAll();
        }


        return response()->json([
            'groups' => $groups
        ], 200);
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
            $dto = new GroupDto([
                'title' => $request['title'],
                'type'  => $request['type'],
                'ownerId' => $request['ownerId']
            ]);

            $this->service->create($dto);

            return response()->json([
                'message' => "Group created"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong!'
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
        $group = $this->service->getOne($id);

        if ($group == null) {
            return response()->json([
                'message' => 'Group Not Found.'
            ], 404);
        }

        return response()->json([
            'group' => $group
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
        $res = $this->service->delete($id);

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
