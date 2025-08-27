<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// -----------------------------
// Department Routes (CRUD)
// -----------------------------
Route::apiResource('departments', DepartmentController::class);

// -----------------------------
// Employee Routes (CRUD)
// -----------------------------
Route::apiResource('employees', EmployeeController::class);

// -----------------------------
// Search Employees by name/email/department
// Example: /api/employees-search?name=John
// -----------------------------
Route::get('employees-search', [EmployeeController::class, 'search']);

Route::fallback(function () {
    return response()->json([
        'status' => false,
        'message' => 'Route not found.'
    ], 404);
});