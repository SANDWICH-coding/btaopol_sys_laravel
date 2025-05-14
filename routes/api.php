<?php
use App\Http\Controllers\BillingConfigurationController;
use App\Http\Controllers\ClassArmController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\YearLevelController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/register-user', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/users', [UserController::class, 'getAllUsers']);
Route::resource('/school-year', SchoolYearController::class);
Route::resource('/year-level', YearLevelController::class);
Route::resource('/class-arm', ClassArmController::class);
Route::resource('/billing-configuration', BillingConfigurationController::class);
Route::resource('/enrollment', EnrollmentController::class);


Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out']);
});