<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\PositionController;
use App\Http\Controllers\API\UserInfoController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\AlbumController;
use App\Http\Controllers\API\PhotoController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\SubdomainController;
use App\Http\Controllers\API\SearchController;

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

Route::get('/phpinfo', function () {
  return phpinfo();
});

Route::get('/getsubdomain', function () {
  return getRefererSubdomain();
});

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
    Route::post('/update-access/{token}', 'updateAccess')->middleware(['nd_permission:edit user']);
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
  Route::group(['prefix' => '/permissions', 'middleware' => ['auth:sanctum', 'nd_permission:assign role|assign permission']], function () {
    Route::get('/', 'all');
    Route::put('/sync-to-role/{token}', 'syncToRole');
    Route::post('/update/{token}', 'update');
    Route::delete('/delete/{token}', 'delete');
  });
});

Route::controller(CountryController::class)->group(function () {
  Route::group(['prefix' => '/countries'], function () {
    Route::get('/', 'all');
  });
});

Route::controller(CompanyController::class)->group(function () {
  Route::group(['prefix' => '/companies', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', 'all');
  });
});

Route::controller(PositionController::class)->group(function () {
  Route::group(['prefix' => '/positions', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', 'all');
  });
});

Route::controller(UserInfoController::class)->group(function () {
  Route::group(['prefix' => '/user-infos', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/update/{token}', 'update');
  });
});

Route::controller(GalleryController::class)->group(function () {
  Route::group(['prefix' => '/galleries'], function () {
    Route::get('/all-unpaginated-public', 'puall');
    Route::get('/all-unpaginated-persub', 'psuall');
    Route::get('/all-unpaginated', 'uall');
    Route::get('/all-unpaginated-bou', 'buall');
    Route::get('/', 'all')->middleware(['nd_permission:view gallery|add gallery|edit gallery|delete gallery']);
    Route::get('/all-paginated-bou', 'ball')->middleware(['auth:sanctum'])->middleware(['nd_permission:view gallery']);
    Route::get('/albums/{token}', 'albums');
    Route::post('/filtered-albums/{token}', 'filteredAlbums');
    Route::get('/lists-e/{token}', 'listsE');
    Route::get('/lists-parent/{token}', 'allParents');
    Route::get('/parents', 'parents');
    Route::get('/get/{token}', 'get');
    Route::get('/deep-get/{token}', 'deepGet');
    Route::put('/store', 'store')->middleware(['auth:sanctum'])->middleware(['nd_permission:add gallery']);
    Route::post('/update/{token}', 'update')->middleware(['auth:sanctum'])->middleware(['nd_permission:edit gallery']);
    Route::post('/sync/{token}', 'sync')->middleware(['auth:sanctum'])->middleware(['nd_permission:edit gallery']);
    Route::delete('/delete/{token}', 'delete')->middleware(['auth:sanctum'])->middleware(['nd_permission:delete gallery']);
  });
});

Route::controller(AlbumController::class)->group(function () {
  Route::group(['prefix' => '/albums'], function () {
    Route::get('/public', 'public');
    Route::get('/public/{token}', 'publicGet');
    Route::get('/get/{token}', 'get')->middleware(['auth:sanctum'])->middleware(['nd_permission:view album']);
    Route::get('/paginated-photos/{token}', 'pphotos');
    Route::post('/store/{token}', 'store')->middleware(['auth:sanctum'])->middleware(['nd_permission:add album']);
    Route::post('/update/{token}', 'update')->middleware(['auth:sanctum'])->middleware(['nd_permission:edit album']);
    Route::post('/change-status/{token}', 'changeStatus')->middleware(['auth:sanctum'])->middleware(['nd_permission:edit album']);
    Route::delete('/delete/{token}', 'delete')->middleware(['auth:sanctum'])->middleware(['nd_permission:delete album']);
    Route::post('/add-photo/{token}', 'addPhoto')->middleware(['auth:sanctum'])->middleware(['nd_permission:add photo|add album|edit album']);
    Route::post('/upload-photos/{galleryToken}/{albumToken}', 'uploadPhotos')->middleware(['auth:sanctum'])->middleware(['nd_permission:add photo|add album|edit album']);
    Route::post('/empty/{token}', 'empty')->middleware(['auth:sanctum'])->middleware(['nd_permission:delete photo']);
    Route::get('/download-album/{token}', 'downloadAlbum');
  });
});

Route::controller(PhotoController::class)->group(function () {
  Route::group(['prefix' => '/photos'], function () {
    Route::get('/get/{token}', 'get')->middleware('auth:sanctum');
    Route::post('/update/{token}', 'update')->middleware(['auth:sanctum'])->middleware(['nd_permission:edit photo']);
    Route::delete('/delete/{token}', 'delete')->middleware(['auth:sanctum'])->middleware(['nd_permission:delete photo']);
    Route::get('/download/{token}', 'download');
  });
});

Route::controller(TagController::class)->group(function () {
  Route::group(['prefix' => '/tags'], function () {
    Route::get('/', 'all');
    Route::get('/all-paginated', 'allp')->middleware(['auth:sanctum'])->middleware(['nd_permission:view tag']);
    Route::get('/without-type', 'withoutType')->middleware(['auth:sanctum']);
    Route::put('/store', 'store')->middleware(['auth:sanctum'])->middleware(['nd_permission:add tag']);
    Route::post('/update/{id}', 'update')->middleware(['auth:sanctum'])->middleware(['nd_permission:edit tag']);
    Route::delete('/delete/{id}', 'delete')->middleware(['auth:sanctum'])->middleware(['nd_permission:delete tag']);
    Route::post('/multiple-delete', 'multiDelete')->middleware(['auth:sanctum'])->middleware(['nd_permission:delete tag']);
  });
});

Route::controller(RegionController::class)->group(function () {
  Route::group(['prefix' => '/regions'], function () {
    Route::get('/', 'all');
  });
});

Route::controller(SubdomainController::class)->group(function () {
  Route::group(['prefix' => '/subdomains'], function () {
    Route::get('/', 'all')->middleware('auth:sanctum')->middleware(['nd_permission:view subdomain|view gallery|add gallery|edit gallery|delete gallery']);
    Route::get('/all-paginated', 'allPaginated')->middleware('auth:sanctum')->middleware(['nd_permission:view subdomain']);
    Route::put('/store', 'store')->middleware(['auth:sanctum'])->middleware(['nd_permission:add subdomain']);
    Route::post('/update/{token}', 'update')->middleware(['auth:sanctum'])->middleware(['nd_permission:edit subdomain']);
    Route::delete('/delete/{token}', 'delete')->middleware(['auth:sanctum'])->middleware(['nd_permission:delete subdomain']);
  });
});

Route::controller(SearchController::class)->group(function () {
  Route::group(['prefix' => '/search'], function () {
    Route::post('/get', 'get');
  });
});
