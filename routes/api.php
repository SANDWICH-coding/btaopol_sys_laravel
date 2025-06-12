<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\{
    BillingConfigurationController,
    BillingDiscountController,
    BillingDiscountEnrollmentController,
    ClassArmController,
    SchoolYearController,
    StudentController,
    YearLevelController,
    EnrollmentController,
    PaymentController
};

// Public routes
Route::post('/register-user', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/users', [UserController::class, 'getAllUsers']);

    Route::resource('/school-year', SchoolYearController::class);
    Route::resource('/year-level', YearLevelController::class);
    Route::resource('/class-arm', ClassArmController::class);
    Route::resource('/billing-configuration', BillingConfigurationController::class);
    Route::resource('/enrollment', EnrollmentController::class);
    Route::resource('/student', StudentController::class);
    Route::resource('/billing-discount', BillingDiscountController::class);
    Route::resource('/billing-discount-enrollment', BillingDiscountEnrollmentController::class);
    Route::resource('/payment', PaymentController::class);

    Route::post('/logout', [UserController::class, 'logout']);
});
