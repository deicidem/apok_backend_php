<?php

use App\Http\Controllers\DzzController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Models\Satelite;
use App\Models\SateliteType;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::get('plans', "PlanController@index");
Route::post('files/polygon', [FileController::class, 'polygon']);


// // Route::get('plans/{id}', "PlanController@show");
// // Route::put('plans/{id', "PlanController@update");
// // Route::delete('plans/{id}', "PlanController@destroy");
  // Route::post('register', [AuthController::class, 'register']);
  // Route::post('token', [AuthController::class, 'token']);
  // Route::post('login', [AuthController::class, 'authenticate']);
// Route::middleware('auth:sanctum')->get('name', function (Request $request) {
//   return response()->json(['name' => $request->user()->name]);
// });
Route::resource('plans', PlanController::class);
Route::resource('images', ImageController::class);
Route::resource('dzzs', DzzController::class);

Route::get('user/auth', [UserController::class, 'auth']);
Route::get('user/check-auth', [UserController::class, 'checkAuth']);
Route::get('satelites', function () {
  $satelitesTypes = SateliteType::all();
  $res = [];
  foreach ($satelitesTypes as $st) {
    $satelites = [];
    foreach ($st->satelites as $s) {
      array_push($satelites, [
        'id' => $s->id,
        'name' => $s->name
      ]);
    };
    array_push($res, [
      'id' => $st->id,
      'name' => $st->name,
      'satelites' => $satelites
    ]);
  }
  return response()->json([
    'satelites' => $res
  ]);
});
// Route::middleware('admin')->resource('tasks', TaskController::class);
Route::resource('users', UserController::class);
Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::resource('tasks', TaskController::class);
  // Route::resource('files', FileController::class);
  Route::resource('alerts', AlertController::class);
  Route::get('files/download', [FileController::class, 'download']);
  Route::get('user/files', [FileController::class, 'userFiles']);
  Route::delete('user/files', [FileController::class, 'deleteUserFiles']);
  Route::delete('tasks', [TaskController::class, 'deleteUserTasks']);
});