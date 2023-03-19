<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdvertisesController;
use App\Http\Controllers\AnnouncesController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\PointCategoriesController;
use App\Http\Controllers\ReedemItemsController;
use App\Http\Controllers\ReedemPointController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\TestItemsController;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UsersController::class, 'viewLogin']);
Route::post('/login', [UsersController::class, 'login']);
Route::get('/logout', [UsersController::class, 'logout']);




Route::middleware(['web'])->group(function () {
    Route::get('/user', [UsersController::class, 'profile']);
    Route::post('/user/{user}', [UsersController::class, 'update']);
    Route::get('/dashboard', [UsersController::class, 'index']);
    Route::resource('/advertise', AdvertisesController::class);
    Route::resource('/announces', AnnouncesController::class);
    Route::resource('/reedemItems', ReedemItemsController::class);
    Route::resource('/pointCategories', PointCategoriesController::class);
    Route::resource('/tests', TestItemsController::class);
    Route::resource('/parents', ParentsController::class);
    Route::post('/parentStudent', [ParentsController::class, 'storeParentStudents']);
    Route::prefix('score')->group(function () {
        Route::get('/form', [ScoreController::class, 'index']);
        Route::get('/students/filter', [ScoreController::class, 'filterStudent']);
        Route::get('/filterScore', [ScoreController::class, 'filter']);
        Route::post('/store', [ScoreController::class, 'store']);
        Route::post('/update/{score}', [ScoreController::class, 'update']);
    });
    Route::prefix('attendance')->group(function () {
        Route::get('/form', [AttendanceController::class, 'index']);
        Route::get('/filterScore', [ScoreController::class, 'filter']);

        Route::post('/store', [ScoreController::class, 'store']);
    });
    Route::get('/reedemPoint', [ReedemPointController::class, 'create']);
    Route::post('/reedemPoint', [ReedemPointController::class, 'store']);
});
