<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\Gallery;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\GalleryAccessMap as GACMap;

use Validator;

class UserController extends BaseController
{

  public function all () {
    $users = User::where('id', '!=', 1)
              ->with('roles')
              ->with('info')
              ->paginate(5);

    $permissions = auth('sanctum')->user()->getAllPermissions();

    $arrPerms = [];
    foreach ($permissions as $permission) {
      array_push($arrPerms, $permission->name);
    }

    // return $arrPerms;

    return response()->json($users, 200);
  }

  public function get ($token) {
    $user = User::where('_token', $token)
            ->select('id', '_token', 'name', 'email')
            ->with('info')
            ->first();

    // set roles
    $user->roles;

    // get galleries through access maps
    $galleries = [];
    $gacmaps = GACMap::where('user_id', $user->id)->get();
    foreach ($gacmaps as $gacmap) {
      $gallery = Gallery::where('id', $gacmap->gallery_id)->first();
      array_push($galleries, $gallery);
    }

    // set galleries
    $user->galleries = $galleries;



    return response()->json($user);
  }

  public function authUser (Request $request) {
    $_user = auth('sanctum')->user();

    $user = User::where('_token', $_user->_token)->first();
    $user->roles;
    $user->galleryaccessmaps;

    $rolesWithPermissions = [];
    foreach ($user->roles as $role) {
      $_role = Role::where('id', $role->id)->first();
      array_push($rolesWithPermissions, [
        'role' => $role->name,
        'permissions' => $_role->permissions
      ]);
    }

    $user->rolesWithPermissions = $rolesWithPermissions;

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

  public function store (Request $request) {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:users,name',
      'email' => 'required|unique:users,email',
      'password' => 'required|min:6',
    ]);

    if($validator->fails()) return response()->json($validator->errors(), 422);

    $user = new User;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->_token = generateRandomString();
    $user->save();

    $user->permissions; // get permissions

    $response = [
      'data' => $user,
      'message' => '"' . $user->name . '" has been successfully added.'
    ];

    return response()->json($response);
  }

  public function update ($token, Request $request) {
    // fetch data
    $user = User::where('_token', $token)->first();

    // run validation
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:users,name,'.$user->id,
      'email' => 'required|unique:users,email,'.$user->id
    ]);
    if($validator->fails()) return response()->json($validator->errors(), 422);

    // prevent altering super admin user
    if ($user->id == 1) return response()->json(['message' => 'Forbidden! You cannot alter this record.'], 403);

    // then update
    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->password != null || $request->password != '') $user->password = bcrypt($request->password);
    $user->update();

    // return data to FE
    $obj = User::where('id', $user->id)->with(['permissions' => function ($qry) {
      $qry->select('id', 'name', '_token');
    }])->first();

    $response = [
      'data' => $obj,
      'message' => 'User "' . $request->name . '" has been successfully updated.'
    ];

    return response()->json($response);
  }

  public function delete ($token) {
    // fetch data
    $user = User::where('_token', $token)->first();

    // prevent altering super admin user
    if ($user->id == 1) return response()->json(['message' => 'Forbidden! You cannot alter this record.'], 403);

    $savedUser = $user;

    // delete user info first
    $userInfo = UserInfo::where('user_id', $user->id)->first();
    if ($userInfo) $userInfo->delete();

    // then delete user
    $user->delete();

    // return data to FE
    $response = [
      'data' => $savedUser,
      'message' => '"' . $savedUser->name . '" user has been successfully deleted.'
    ];

    return response()->json($response);
  }

  public function updateAccess ($token, Request $request) {
    // get user
    $user = User::where('_token', $token)->first();

    // delete Gallery Access Maps first
    GACMap::where('user_id', $user->id)->delete();

    // then re-populate new galleries
    foreach ($request->all() as $gallery) {
      $gacmap = new GACMap;
      $gacmap->user_id = $user->id;
      $gacmap->gallery_id = $gallery['id'];
      $gacmap->save();
    }

    $response = [
      'data' => $user,
      'message' => 'Access for this user has been successfully updated.',
    ];
  
    return response()->json($response, 200);
  }
}
