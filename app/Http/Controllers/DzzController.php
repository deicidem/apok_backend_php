<?php

namespace App\Http\Controllers;

use App\Http\Resources\DzzCollection;
use App\Http\Services\Dto\SearchDto;
use App\Models\Dzz;
use App\Models\Sensor;
use App\Http\Services\DzzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $input = Validator::make($request->all(), [
            'polygon'         => ['required', 'json'],
            'months'          => ['required', 'array'],
            'satelites'       => ['required', 'array'],
            'startDate'       => ['required', 'date','before_or_equal:endDate'],
            'endDate'         => ['required', 'date'],
            'startCloudiness' => ['required', 'numeric'],
            'endCloudiness'   => ['required', 'numeric'],
            'size'            => ['nullable', 'numeric'],
            'page'            => ['nullable', 'numeric'],
            'desc'            => ['nullable', Rule::in('true', 'false', '1', '0', 1, 0, true, false)],
            'sortBy'          => ['nullable', 'string']
        ])->validate();
        $dzzs = $this->dzzService->get($input);

        return new DzzCollection($dzzs);
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
