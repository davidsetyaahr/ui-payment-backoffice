<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdvertisesController;
use App\Http\Controllers\AnnouncesController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\PointCategoriesController;
use App\Http\Controllers\ReedemItemsController;
use App\Http\Controllers\TestItemsController;
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

Route::get('/dashboard', [UsersController::class, 'index']);
Route::resource('/advertise', AdvertisesController::class);
Route::resource('/announces', AnnouncesController::class);
Route::resource('/reedemItems', ReedemItemsController::class);
Route::resource('/pointCategories', PointCategoriesController::class);
Route::resource('/tests', TestItemsController::class);
Route::resource('/parents', ParentsController::class);
Route::post('/parentStudent', [ParentsController::class, 'storeParentStudents']);

Route::get('/', function () {
    return view('welcome');
});
