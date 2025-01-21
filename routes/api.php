<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\PhotoController;
use App\Http\Controllers\API\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::resource('/login', LoginController::class);
Route::resource('/allphoto', PhotoController::class)->only('index', 'show');

Route::middleware('auth:sanctum')->group(function (){
    Route::resource('logout', LogoutController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::resource('photo', PhotoController::class);
    Route::resource('video', VideoController::class);
});
