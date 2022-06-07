<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Http\Services\AlertService;
use Illuminate\Http\Request;
use App\Http\Requests\AlertStoreRequest;
use App\Http\Services\Dto\AlertDto;

class AlertController extends Controller
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
    public function __construct(AlertService $myService)
    {
        $this->service = $myService;
    }

    public function index()
    {
        $alerts = $this->service->getAll();

        return response()->json([
            'alerts' => $alerts
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
            $dto = new AlertDto([
                'title'       => $request['title'],
                'description' => $request['description']
            ]);

            $this->service->post($dto);

            return response()->json([
                'message' => "Alert created"
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
        $alert = $this->service->getOne($id);

        if ($alert == null) {
            return response()->json([
                'message' => 'Alert Not Found.'
            ], 404);
        }

        return response()->json([
            'alert' => $alert
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
            $dto = new AlertDto([
                'id'          => $id,
                'title'       => $request['title'],
                'description' => $request['description']
            ]);

            $res = $this->service->update($dto);

            if ($res == null) {
                return response()->json([
                    'message' => 'Alert not found'
                ], 404);
            }

            return response()->json([
                'message' => "Alert successfully updated"
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
                'message' => 'Alert not found'
            ], 404);
        }

        return response()->json([
            'message' => "Alert successfully deleted"
        ], 200);
    }
}
