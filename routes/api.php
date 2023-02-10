<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\UserRoleController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*Route::get('countries', [CountryController::class, 'index']);
Route::get('countries/{country}', [CountryController::class, 'show']);
Route::post('countries', [CountryController::class, 'store']);
Route::put('countries/{country}', [CountryController::class, 'update']);
Route::delete('countries/{country}', [CountryController::class, 'destroy']);*/





Route::apiResource('countries', CountryController::class);
Route::apiResource('cities', CityController::class);
Route::apiResource('subjects', SubjectController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('notifications', NotificationController::class);
Route::apiResource('user-notifications', UserNotificationController::class);
Route::apiResource('classrooms', ClassroomController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('user-roles', UserRoleController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('role-permissions', RolePermissionController::class);
Route::apiResource('schedules', ScheduleController::class);

Route::prefix('auth')->group(function () {
    Route::post('registerAsUser', [AuthController::class, 'registerAsUser']);
    Route::post('registerAsInstructor', [AuthController::class, 'registerAsInstructor']);
});

