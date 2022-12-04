<?php

use App\Http\Controllers\AdministratorsContoller;
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

Route::middleware('auth:sanctum')->get('/user',[UserController::class,'userinfo']);

Route::middleware('auth:sanctum')->post('/users/logout',[UserController::class,'logout']);

Route::middleware('auth:administrator')->post('/users/index', [AdministratorsContoller::class, 'getall']);

Route::middleware('auth:administrator')->post('/users/delete/{id}', [AdministratorsContoller::class, 'deleteuser']);

Route::post('/users/create', [UserController::class,'register']);
Route::post('/admins/create', [AdministratorsContoller::class, 'register']);
Route::post('/admins/login', [AdministratorsContoller::class, 'login']);
Route::post('/users/login', [UserController::class,'login']);
