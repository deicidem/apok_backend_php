<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileCollection;
use App\Http\Services\Dto\FileDto;
use App\Http\Services\FileService;
use App\Models\Dzz;
use App\Models\File;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
    public function index(Request $request)
    {
        $input = Validator::make($request->all(), [
            'userId' => ['nullable', 'numeric', 'exists:users,id'],
            'size'   => ['nullable', 'numeric'],
            'page'   => ['nullable', 'numeric'],
            'desc'   => ['nullable', Rule::in('true', 'false', '1', '0', 1, 0, true, false)],
            'sortBy' => ['nullable', 'string'],
            'name'   => ['nullable', 'string'],
            'id'     => ['nullable', 'numeric'],
            'date'   => ['nullable', 'date'],
            'any'    => ['nullable', 'string'],
        ])->validated();
        $files = $this->service->getAll($input);
        return new FileCollection($files);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function processFile($request, $fileName)
    {
        $path   = $request->file($fileName)->storeAs('b', $fileName . '.json');
        $params = $request->all();

        $dzz = Dzz::Create([
            'name' => 'new Dzz'
        ]);

        $dto = new FileDto([
            'name'       => $fileName,
            'path'       => $path,
            'dzzId'      => $dzz->id,
            'fileTypeId' => $params['fileTypeId']
        ]);
        $this->service->post($dto);
    }
    public function store(Request $request)
    {
        try {
            $res    = [];
            $params = $request->all();
            foreach ($request->files as $file) {
                array_push($res, $file->getSize());

                $name = $file->getClientOriginalName();
                $path = Storage::putFileAs('b', $file, $name);

                $dzz = Dzz::Create([
                    'name' => 'new Dzz'
                ]);


                $dto = new FileDto([
                    'name'       => $name,
                    'path'       => $path,
                    'dzzId'      => $dzz->id,
                    'fileTypeId' => $params['fileTypeId']
                ]);
                $this->service->post($dto);
            }

            // if ($request->hasFile('dzz_archive')) {
            //     FileController::processFile($request, 'dzz_archive');
            // }
            // if ($request->hasFile('dzz_actual')) {
            //     FileController::processFile($request, 'dzz_actual');
            // }
            // $path      = $request->file('file')->storeAs('b', $_FILENAME);
            // $params    = $request->all();



            // $dzz = Dzz::Create([
            //     'name' => 'new Dzz'
            // ]);


            // $dto = new FileDto([
            //     'name'       => $_FILENAME,
            //     'path'       => $path,
            //     'dzzId'      => $dzz->id,
            //     'fileTypeId' => $params['fileTypeId']
            // ]);
            // $this->service->post($dto);

            return response()->json([
                'message' => "???????? ????????????"
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
                'message' => '???????? ???? ????????????.'
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
    public function update($id, Request $request)
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
                    'message' => '???????? ???? ????????????'
                ], 404);
            }

            return response()->json([
                'message' => "???????? ?????????????? ????????????????"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "??????-???? ?????????? ???? ??????"
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
        $res = $this->service->deleteUserFile($id);

        if ($res == null) {
            return response()->json([
                'message' => '???????? ???? ????????????'
            ], 404);
        }

        return response()->json([
            'message' => "???????? ?????????????? ????????????"
        ], 200);
    }



    public function polygon(Request $request)
    {
        $input = Validator::make($request->all(), [
            'file' => ['required', 'file']
        ])->validated();

        $file = $input['file'];
        $ext = $file->extension();
        if ($ext == "zip") {
            $temp = "temp";
            $zip = new \ZipArchive;

            Storage::makeDirectory($temp);
            $filePath = Storage::putFile($temp, $file);
            $res = $zip->open(Storage::path($filePath));

            if ($res === TRUE) {
                $zip->extractTo(Storage::path($temp));
                $zip->close();
            }



            Storage::copy('public/result/preview.json', $temp . "/temp.geojson");
            Storage::delete($filePath);
            $res = null;
            if (Storage::exists($temp . "/temp.geojson")) {
                $res = Storage::get($temp . "/temp.geojson");
            }
            // Storage::deleteDirectory($temp);
            return response()->json([
                'file' => json_decode($res)
            ], 200);
        } else if ($ext == "json" || $ext == "geojson") {
            $data = $file->openFile()->fread($file->getSize());
            return response()->json([
                'file' => json_decode($data)
            ], 200);
        } else {
            return response()->json([
                'message' => "???????????????????? ???????????????????? ?????????? .json, .geojson, .zip"
            ], 422);
        }
    }

    public function download(Request $request)
    {
        $file = File::find($request->id);
        if ($file->type_id == 5) {
            $zip_file = 'archive.zip';
            $zip = new \ZipArchive();

            if ($zip->open(public_path($zip_file), \ZipArchive::CREATE) === TRUE) {
                $path = Storage::path($file->path);
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator(
                        $path,
                        \FilesystemIterator::FOLLOW_SYMLINKS
                    ),
                    \RecursiveIteratorIterator::SELF_FIRST
                );

                while ($iterator->valid()) {
                    if (!$iterator->isDot()) {
                        $filePath = $iterator->getPathName();
                        $relativePath = substr($filePath, strlen($path) + 1);

                        if (!$iterator->isDir()) {
                            $zip->addFile($filePath, $relativePath);
                        } else {
                            if ($relativePath !== false) {
                                $zip->addEmptyDir($relativePath);
                            }
                        }
                    }
                    $iterator->next();
                }
                // $files = Storage::allFiles($file->path);
                // foreach ($files as $filePath) {
                //     $path = Storage::path($filePath);
                //     $zip->addFile($path, basename($path));
                // }

                $zip->close();
            }
            return response()->download(public_path($zip_file))->deleteFileAfterSend(true);
        } else {
            return response()->download(Storage::path($file->path));
        }
    }
}
