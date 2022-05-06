<?php

namespace App\Http\Controllers;

use App\Http\Services\Dto\SearchDto;
use App\Models\Dzz;
use App\Models\Sensor;
use App\Http\Services\DzzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Promise\each;

class DzzController extends Controller
{
    protected $dzzService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(DzzService $myService)
    {
        $this->dzzService = $myService;
    }

    public function index(Request $request)
    {
        $params = $request->all();
        // print_r($params);
        // $dzzs = Dzz::all()->where([
        //     ['date', '>=', $params['startDate']],
        //     ['date', '<=', $params['endDate']],
        //     ['cloudiness', '>=', $params['startCloudiness']],
        //     ['cloudiness', '<=', $params['endCloudiness']],
        //     ['date', '<=', $params['endDate']],
        //     ['date', '<=', $params['endDate']],
        // ]);
        

        

        // $polygon = array(array(27.449790329784214,3.251953125),array(28.767659105691255,13.183593750000002),array(31.57853542647338,44.47265625000001),array(50.233151832472245,36.47460937500001),array(45.82879925192134,31.289062500000004),array(46.92025531537454,19.687500000000004),array(47.040182144806664,13.447265625000002),array(36.87962060502676,4.042968750000001));

        // $point1 = array(36.94989178681327, 18.413085937500004);
        // $point2 = array(44.465151013519645, 0.6591796875000001);
        // $point3 = array(38.685509760012025, 42.5390625);

        // printf(contains($point1,$polygon)?'IN':'OUT');
        // printf(contains($point2,$polygon)?'IN':'OUT');
        // printf(contains($point3,$polygon)?'IN':'OUT');
        $searchDto = new SearchDto($params);
        $data = $this->dzzService->get($searchDto);
        return response()->json([
            'dzzs' => $data
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $dzz = Dzz::find($id);
        $files = $dzz->files;
        $data = [
            'id' => $dzz->id,
            "name" => $dzz->name,
            "date" => $dzz->date,
            "round" => $dzz->round,
            "route" => $dzz->route,
            "cloudiness" => $dzz->cloudiness,
            "processingLevel" => $dzz->processingLevel->name,
            "sensor" => $dzz->sensor->name
        ];
        if (!$dzz) {
            return response()->json([
                'message' => 'Dzz Not Found.'
            ], 404);
        }
        return response()->json(
            $data,
            200
        );
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
}
