<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ResponsibilityController;
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

// Company API
Route::prefix("company")->middleware("auth:sanctum")->name("company.")->group(function () {
    Route::get('', [CompanyController::class, 'fetch'])->name("fetch");
    Route::post('', [CompanyController::class, "create"])->name("create");
    Route::post('/update/{id}', [CompanyController::class, "update"])->name("update");
});

// Team API
Route::prefix("team")->middleware("auth:sanctum")->name("team.")->group(function () {
    Route::get('', [TeamController::class, 'fetch'])->name("fetch");
    Route::post('', [TeamController::class, "create"])->name("create");
    Route::post('/update/{id}', [TeamController::class, "update"])->name("update");
    Route::delete('/delete/{id}', [TeamController::class, "delete"])->name("delete");
});

// Role API
Route::prefix("role")->middleware("auth:sanctum")->name("role.")->group(function () {
    Route::get('', [RoleController::class, 'fetch'])->name("fetch");
    Route::post('', [RoleController::class, "create"])->name("create");
    Route::post('/update/{id}', [RoleController::class, "update"])->name("update");
    Route::delete('/delete/{id}', [RoleController::class, "delete"])->name("delete");
});


// Responsibility API
Route::prefix("responsibility")->middleware("auth:sanctum")->name("responsibility.")->group(function () {
    Route::get('', [ResponsibilityController::class, 'fetch'])->name("fetch");
    Route::post('', [ResponsibilityController::class, "create"])->name("create");
    Route::delete('/delete/{id}', [ResponsibilityController::class, "delete"])->name("delete");
});

// Auth API
Route::name("auth.")->group(function () {
    Route::post('login', [UserController::class, 'login'])->name("login");
    Route::post('register', [UserController::class, 'register'])->name("register");

    Route::middleware("auth:sanctum")->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name("logout");
        Route::get('user', [UserController::class, 'fetch'])->name("fetch");
    });
});
