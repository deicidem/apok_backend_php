<?php

namespace App\Http\Controllers;

use App\Http\Services\Dto\FileDto;
use App\Http\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    protected $service;

    public function __construct(FileService $myService)
    {
        $this->service = $myService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = $this->service->getAll();

        return response()->json([
            'plans' => $files
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
            $_FILENAME = "file";
            $path      = $request->file('file')->storeAs('b', $_FILENAME);
            $params    = $request->all();

            $dto = new FileDto([
                'name'       => $_FILENAME,
                'path'       => $path,
                'dzzId'      => $params['dzzId'],
                'fileTypeId' => $params['fileTypeId']
            ]);
            $this->service->post($dto);

            return response()->json([
                'message' => "Plan created"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
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
        $file = $this->service->getOne($id);

        if ($file == null) {
            return response()->json([
                'message' => 'File Not Found.'
            ], 404);
        }

        return response()->json([
            'file' => $file
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $_FILENAME = "file";
            $path      = $request->file('file')->storeAs('b', $_FILENAME);
            $params    = $request->all();

            $dto = new FileDto([
                'id'         => $id,
                'name'       => $_FILENAME,
                'path'       => $path,
                'dzzId'      => $params['dzzId'],
                'fileTypeId' => $params['fileTypeId']
            ]);

            $res = $this->service->update($dto);

            if ($res == null) {
                return response()->json([
                    'message' => 'File not found'
                ], 404);
            }

            return response()->json([
                'message' => "File successfully updated"
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
                'message' => 'File not found'
            ], 404);
        }

        return response()->json([
            'message' => "Plan successfully deleted"
        ], 200);
    }
}
