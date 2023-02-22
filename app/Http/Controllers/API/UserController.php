<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;

use Validator;

class UserController extends BaseController
{
  public function authUser (Request $request) {
    $user = auth('sanctum')->user();
    $user->roles;

    return response()->json($user, 200);
  }

  public function userProfile ($token) {
    $user = User::where('_token', $token)->first();
    $user->getAllPermissions();

    return response()->json($user, 200);
  }
}
