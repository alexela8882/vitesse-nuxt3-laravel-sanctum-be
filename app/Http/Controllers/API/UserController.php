<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;

use Validator;

class UserController extends BaseController
{

  public function all () {
    $user = User::all();

    $permissions = auth('sanctum')->user()->getAllPermissions();

    $arrPerms = [];
    foreach ($permissions as $permission) {
      array_push($arrPerms, $permission->name);
    }

    // return $arrPerms;

    return response()->json($user, 200);
  }

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

  public function changePassword ($token, Request $request) {
    $rules = [
      'password' => 'required|max:255',
      'newpassword' => 'required|min:6|max:255|confirmed',
    ];

    $message = [
      'password.required' => 'The old password field is required.',
      'newpassword.required' => 'The new password field is required.',
      'newpassword.confirmed' => 'The new password confirmation does not match. ',
      'newpassword.min' => 'The new password must be at least 6 characters. ',
    ];
    $validator = Validator::make($request->all(), $rules, $message);
    $validator->after(function($validator) use($request) {
      if (!\Hash::check($request->get('password'), auth('sanctum')->user()->password)) {
        $validator->errors()->add('password', 'Old password don\'t match in our records.');
      }
    });

    if($validator->fails()) return response()->json($validator->errors(), 422);

    $user = User::where('_token', $token)->first();
    $user->password = bcrypt($request->newpassword);
    $user->update();

    $response = [
      'data' => $user,
      'message' => 'Password successfully changed.'
    ];

    return response()->json($response, 200);
  }

}
