<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/user', [AuthController::class,'user']);
    

    Route::apiResource('departments', DepartmentController::class);

   
    Route::apiResource('employees', EmployeeController::class);

    
    Route::get('employees-search', [EmployeeController::class, 'search']);

});

Route::fallback(function () {
    return response()->json([
        'status' => false,
        'message' => 'Route not found.'
    ], 404);
});
