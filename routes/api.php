<?php

use App\Http\Controllers\API\InfoController;
use App\Http\Controllers\API\UsersController;
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

Route::post('signin', [UsersController::class, 'getOtp']);
Route::post('submit-otp', [UsersController::class, 'submitOtp']);


Route::get('listStudents/{parentId}', [UsersController::class, 'students']);
Route::get('listStudents/{parentId}', [UsersController::class, 'students']);
Route::get('announcement', [InfoController::class, 'getAnnouncement']);
Route::get('advertise', [InfoController::class, 'getAdvertise']);
Route::get('agenda', [InfoController::class, 'getAgenda']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
