<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;

use App\Models\User;

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

Route::controller(AuthController::class)->group(function(){
  Route::post('register', 'register');
  Route::post('login', 'login');
  Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->group(function () {
  Route::group(['prefix' => '/user', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', 'authUser');
    Route::get('/get/{token}', 'userProfile');
    Route::put('/change-password/{token}', 'changePassword');
  });
});

Route::controller(UserController::class)->group(function () {
  Route::group(['prefix' => '/users', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', 'all')->middleware(['nd_permission:view user|add user|edit user|delete user']);
    Route::get('/get/{token}', 'get')->middleware(['nd_permission:view user']);
    Route::put('/store', 'store')->middleware(['nd_permission:add user']);
    Route::post('/update/{token}', 'update')->middleware(['nd_permission:edit user']);
    Route::delete('/delete/{token}', 'delete')->middleware(['nd_permission:delete user']);
  });
});

Route::controller(RoleController::class)->group(function () {
  Route::group(['prefix' => '/roles', 'middleware' => ['auth:sanctum', 'nd_permission:assign role|assign permission']], function () {
    Route::get('/', 'all');
    Route::put('/sync-to-user/{token}', 'syncToUser');
    Route::get('/lists', 'uall');
    Route::get('/get/{token}', 'get');
    Route::put('/store', 'store');
    Route::post('/update/{token}', 'update');
    Route::delete('/delete/{token}', 'delete');
  });
});

Route::controller(PermissionController::class)->group(function () {
  Route::group(['prefix' => '/permissions', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', 'all');
    Route::put('/sync-to-role/{token}', 'syncToRole');
    Route::post('/update/{token}', 'update');
    Route::delete('/delete/{token}', 'delete');
  });
});
