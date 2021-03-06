<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Calendar\EventsController;
use App\Http\Controllers\Calendar\HomeController;
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



Route::middleware('auth:api')->group(function(){
	Route::get('/user', fn(Request $request) => $request->user());
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::post('/event', [EventsController::class, 'store']);
  Route::get('/getSchedule', [HomeController::class, 'read']);
    
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);