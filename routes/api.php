<?php

use App\Http\Controllers\DzzController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PlanController;

use Illuminate\Http\Request;
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
// // Route::post('posts', "PlanController@store");
// // Route::get('plans/{id}', "PlanController@show");
// // Route::put('plans/{id', "PlanController@update");
// // Route::delete('plans/{id}', "PlanController@destroy");
  // Route::post('register', [AuthController::class, 'register']);
  // Route::post('token', [AuthController::class, 'token']);
  Route::post('login', [AuthController::class, 'authenticate']);
Route::middleware('auth:sanctum')->get('name', function (Request $request) {
  return response()->json(['name' => $request->user()->name]);
});
Route::resource('plans', PlanController::class);
Route::resource('images', ImageController::class);
Route::resource('dzzs', DzzController::class);

Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::resource('files', FileController::class);
  Route::resource('alerts', AlertController::class);
  Route::resource('tasks', TaskController::class);
});