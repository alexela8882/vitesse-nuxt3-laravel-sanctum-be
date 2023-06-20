<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;
use App\Models\UserInfo;

use Validator;

class UserInfoController extends BaseController
{
    public function update ($token, Request $request) {
      $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'country_id' => 'required',
        'company_id' => 'required',
        'position_id' => 'required',
      ];
  
      $message = [
        'first_name.required' => 'Please enter a first name.',
        'last_name.required' => 'Please enter a last name.',
        'country_id.required' => 'Please select a country.',
        'company_id.required' => 'Please select a company.',
        'position_id.required' => 'Please select a position.',
      ];
      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      $user = User::where('_token', $token)->first();
      $checkUserInfo = UserInfo::where('user_id', $user->id)->first();

      if (!$checkUserInfo)  $userinfo = new UserInfo;
      else  $userinfo = UserInfo::where('user_id', $user->id)->first();

      $userinfo->user_id = $user->id;
      $userinfo->first_name = $request->first_name;
      $userinfo->last_name = $request->last_name;
      $userinfo->country_id = $request->country_id;
      $userinfo->company_id = $request->company_id;
      $userinfo->position_id = $request->position_id;
      $userinfo->avatar = $request->avatar ? $request->avatar : 'USER AVATAR_ESCO PHOTOS-70px-16.png';
      $userinfo->save();

      // update users table
      $user->name = $request->first_name . ' ' . $request->last_name;
      $user->update();

      $response = [
        'data' => $userinfo,
        'message' => 'User "' . $user->name . '" has been successfully updated.'
      ];

      return response()->json($response, 200);
    }
}
