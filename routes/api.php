<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  $user = auth('sanctum')->user();
  $user->roles;

  return $user;
});

Route::controller(RoleController::class)->group(function (){
  Route::group(['prefix' => '/roles', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', 'all');
    Route::get('/get/{token}', 'get');
    Route::put('/store', 'store');
    Route::post('/update/{token}', 'update');
    Route::delete('/delete/{token}', 'delete');
  });
});

Route::controller(PermissionController::class)->group(function (){
  Route::group(['prefix' => '/permissions', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', 'all');
    Route::put('/sync-to-role/{token}', 'syncToRole');
    Route::post('/update/{token}', 'update');
    Route::delete('/delete/{token}', 'delete');
  });
});
