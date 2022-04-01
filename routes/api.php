<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
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
Route::prefix('v1')->group(function(){
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    // auth routes
    Route::middleware('auth:api')->group(function(){
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        Route::prefix('jobs')->name('jobs.')->group(function(){
            Route::get('', [JobController::class, 'index'])->name('index');
            Route::post('', [JobController::class, 'store'])->name('store');
        });
    });
});
