<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StaffApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\HolidayRequestApiController;
use App\Http\Controllers\Api\MaintenanceApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RotaApiController;
use App\Http\Controllers\Api\ComplaintApiController;
use App\Http\Controllers\Api\KitchenInventoryApiController;
use App\Http\Controllers\Api\KitchenMobileController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\PasswordResetApiController;

Route::post('/reset-password',[PasswordResetApiController::class, 'reset']);
Route::post('/forgot-password',[PasswordResetApiController::class, 'forgot']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);

    Route::get('/users', [StaffApiController::class, 'index']);

    Route::get('/news', [NewsApiController::class, 'index']);
    Route::get('/news/{id}', [NewsApiController::class, 'show']);

    Route::get('/maintenance/jobs', [MaintenanceApiController::class, 'index']);
    Route::post('/maintenance/jobs', [MaintenanceApiController::class, 'store']);
    Route::get('/maintenance/jobs/{id}', [MaintenanceApiController::class, 'show']);
    Route::patch('/maintenance/jobs/{id}/status', [MaintenanceApiController::class, 'updateStatus']);
    Route::patch('/maintenance/jobs/{id}/note', [MaintenanceApiController::class, 'updateNote']);
    Route::get('/maintenance/my-jobs', [MaintenanceApiController::class, 'myJobs']);

    //Holiday request
    Route::post('/holiday-requests', [HolidayRequestApiController::class, 'store']);
    Route::get('/my-holiday-requests', [HolidayRequestApiController::class, 'myRequests']);
    Route::delete('/holiday-requests/{id}', [HolidayRequestApiController::class, 'destroy']);

    //rota
    Route::get('/my-rota', [RotaApiController::class, 'myRota']);

    //complaints
    Route::get('/complaints', [ComplaintApiController::class, 'index']);

    //inventry
    Route::get('/kitchen/inventory', [KitchenInventoryApiController::class, 'index']);
    Route::post('/kitchen/inventory/stock-in', [KitchenMobileController::class, 'stockIn']);

    Route::get('/kitchen/recipes', [KitchenMobileController::class, 'recipes']);
    Route::post('/kitchen/recipes/{menuItem}/sale', [KitchenMobileController::class, 'recipeSale']);

    Route::get('/kitchen/buffets', [KitchenMobileController::class, 'buffets']);
    Route::post('/kitchen/buffets/{buffetMenu}/sale', [KitchenMobileController::class, 'buffetSale']);

    Route::post('/kitchen/wastage', [KitchenMobileController::class, 'wastage']);

    //attendance

Route::get('/attendance/status', [AttendanceApiController::class, 'status']);
Route::post('/attendance/clock-in', [AttendanceApiController::class, 'clockIn']);
Route::post('/attendance/clock-out', [AttendanceApiController::class, 'clockOut']);
Route::get('/attendance/history', [AttendanceApiController::class, 'history']);



    Route::get('/departments', function () {
    return response()->json([
        'success' => true,
        'departments' => \App\Models\Department::all()
    ]);
});
});