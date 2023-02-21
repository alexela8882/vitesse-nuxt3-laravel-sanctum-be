<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Validator;

class RoleController extends BaseController
{
    public function all () {
      $roles = Role::select('id', '_token', 'name')
              ->with(['permissions' => function ($qry) {
                $qry->select('_token', 'name', '_token');
              }])
              ->where('id', '!=', 1)
              ->orderBy('id', 'asc')
              ->paginate(5);

      return response()->json($roles);
    }

    public function get ($token) {
      $role = Role::where('_token', $token)
              ->select('id', '_token', 'name')
              ->with(['permissions' => function ($qry) {
                $qry->select('id', 'name', '_token');
              }])
              ->first();

      return response()->json($role);
    }

    public function store (Request $request) {
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:roles,name'
      ]);

      if($validator->fails()) return response()->json($validator->errors(), 422);

      $role = new Role;
      $role->name = $request->name;
      $role->_token = generateRandomString();
      $role->save();

      $role->permissions; // get permissions

      $response = [
        'data' => $role,
        'message' => '"' . $role->name . '" has been successfully added.'
      ];

      return response()->json($response);
    }

    public function update ($token, Request $request) {
      // fetch data
      $role = Role::where('_token', $token)->first();

      // run validation
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:roles,name,'.$role->id
      ]);
      if($validator->fails()) return response()->json($validator->errors(), 422);

      // prevent altering super admin role
      if ($role->id == 1) return response()->json('Forbidden', 403);

      // then update
      $role->name = $request->name;
      $role->update();

      // return data to FE
      $obj = Role::where('id', $role->id)->with(['permissions' => function ($qry) {
        $qry->select('id', 'name');
      }])->first();

      $response = [
        'data' => $obj,
        'message' => '"' . $role->name . '" has been successfully updated.'
      ];

      return response()->json($response);
    }

    public function delete ($token) {
      // fetch data
      $role = Role::where('_token', $token)->first();

      // prevent altering super admin role
      if ($role->id == 1) return response()->json('Forbidden', 403);

      // then delete
      $savedRole = $role;
      $role->delete();

      // return data to FE
      $response = [
        'data' => $role,
        'message' => '"' . $role->name . '" role has been successfully deleted.'
      ];

      return response()->json($response);
    }
}
