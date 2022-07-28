<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Http\Services\PlanService;
use Illuminate\Http\Request;
use App\Http\Requests\PlanStoreRequest;
use App\Http\Resources\PlanCollection;
use App\Http\Resources\PlanResource;
use App\Http\Services\Dto\PlanDto;
use App\Mail\EmailVerification;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class PlanController extends Controller
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
    public function __construct(PlanService $myService)
    {
        $this->service = $myService;
    }

    public function index()
    {   

        $plans = $this->service->getAll();

        return new PlanCollection($plans);
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
            $dto = new PlanDto([
                'title'       => $request['title'],
                'description' => $request['description']
            ]);

            $this->service->post($dto);

            return response()->json([
                'message' => "Plan created"
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
        $plan = $this->service->getOne($id);

        if ($plan == null) {
            return response()->json([
                'message' => 'Plan Not Found.'
            ], 404);
        }

        return new PlanResource($plan);
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
            $dto = new PlanDto([
                'id'          => $id,
                'title'       => $request['title'],
                'description' => $request['description']
            ]);

            $res = $this->service->update($dto);

            if ($res == null) {
                return response()->json([
                    'message' => 'Plan not found'
                ], 404);
            }

            return response()->json([
                'message' => "Plan successfully updated"
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
                'message' => 'Plan not found'
            ], 404);
        }

        return response()->json([
            'message' => "Plan successfully deleted"
        ], 200);
    }
}
