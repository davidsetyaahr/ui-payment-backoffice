<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdvertisesController;
use App\Http\Controllers\AnnouncesController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\ParentStudentsController;
use App\Http\Controllers\PointCategoriesController;
use App\Http\Controllers\ReedemItemsController;
use App\Http\Controllers\ReedemPointController;
use App\Http\Controllers\ScheduleClassController;
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

Route::get('/print', [UsersController::class, 'print']);
Route::get('/printInvoice/{paymentId}', [PaymentController::class, 'printInvoice']);
Route::prefix('landing-page')->group(
    function () {
        Route::get('/redeem-point', [LandingPageController::class, 'redeemPoint']);
        Route::post('/store-redeem-point', [LandingPageController::class, 'storeReedemPoint']);
    }
);

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
    Route::delete('/schedule-class/delete', [ScheduleClassController::class, 'delete']);
    Route::resource('/schedule-class', ScheduleClassController::class);
    Route::post('/parentStudent', [ParentsController::class, 'storeParentStudents']);
    Route::prefix('score')->group(function () {
        Route::get('/form', [ScoreController::class, 'index']);
        Route::get('/form-create', [ScoreController::class, 'formCreate']);
        Route::get('/create', [ScoreController::class, 'create']);
        Route::get('/students/filter', [ScoreController::class, 'filterStudent']);
        Route::get('/filterScore', [ScoreController::class, 'filter']);
        Route::post('/store', [ScoreController::class, 'store']);
        Route::post('/update/{score}', [ScoreController::class, 'update']);
    });
    Route::get('/history-test', [ScoreController::class, 'historyTest']);
    Route::prefix('attendance')->group(function () {
        Route::get('/form/{priceId}', [AttendanceController::class, 'create']);
        Route::get('/class', [AttendanceController::class, 'index']);
        Route::post('/store', [AttendanceController::class, 'store']);
        Route::post('/update/{attendance}', [AttendanceController::class, 'update']);
        Route::get('/reminder', [AttendanceController::class, 'reminder']);
        Route::get('/reminder-done', [AttendanceController::class, 'reminderDone']);
        Route::get('/reminder-absen', [AttendanceController::class, 'reminderAbsen']);
        Route::get('/get-class', [AttendanceController::class, 'getClass']);
        Route::post('/update-class', [AttendanceController::class, 'updateClass']);
    });
    Route::get('/mutasi', [AttendanceController::class, 'mutasi']);
    Route::post('/mutasi', [AttendanceController::class, 'storeMutasi']);
    Route::get('/reedemPoint', [ReedemPointController::class, 'create']);
    Route::post('/reedemPoint', [ReedemPointController::class, 'store']);
    Route::get('/saldo-awal', [ReedemPointController::class, 'saldoAwal']);
    Route::get('/saldo-awal/form/{id}', [ReedemPointController::class, 'createSaldoAwal']);
    Route::post('/saldo-awal', [ReedemPointController::class, 'storeSaldoAwal']);
    Route::get('/students/filter', [ScoreController::class, 'filterStudentByName']);
    Route::get('/get-barcode-student', [ParentStudentsController::class, 'student']);
    Route::get('/barcode-student', [ParentStudentsController::class, 'barcode']);
    Route::get('/barcode-download/{id}', [ParentStudentsController::class, 'download']);
    Route::get('/barcode-print/{id}', [ParentStudentsController::class, 'print']);
});
