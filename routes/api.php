<?php

use App\Http\Controllers\AdministratorsContoller;
use App\Http\Controllers\StadiumController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
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

//Route::middleware('auth:api')->get('/user',[UserController::class,'userinfo']);

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'userinfo');
        Route::post('/users/update', 'update');
        Route::post('/users/logout', 'logout');
    });
    Route::controller(TeamController::class)->group(function () {
        Route::post('/team/store', 'store');
        Route::get('/team/index', 'index');
        Route::delete('/team/destroy/{id}', 'destroy');
    });

    Route::controller(StadiumController::class)->group(function () {
        Route::post('/stadium/store', 'store');
        Route::get('/stadium/index', 'index');
        Route::delete('/stadium/destroy/{id}', 'destroy');
    });
});

Route::middleware('auth:administrator')->controller(AdministratorsContoller::class)->group(function () {
    Route::get('/users/index', 'getall');
    Route::post('/users/delete/{id}', 'deleteuser');
    Route::get('/users/bemanager', 'bemanager');
    Route::put('/users/bemanager/{id}', 'manager');
});

Route::post('/users/create', [UserController::class,'register']);
Route::post('/admins/create', [AdministratorsContoller::class, 'register']);
Route::post('/admins/login', [AdministratorsContoller::class, 'login']);
Route::post('/users/login', [UserController::class,'login']);
