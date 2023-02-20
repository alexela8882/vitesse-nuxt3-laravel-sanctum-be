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
      $roles = Role::select('id', 'name')
              ->with(['permissions' => function ($qry) {
                $qry->select('id', 'name');
              }])
              ->orderBy('id', 'asc')
              ->paginate(5);

      return response()->json($roles);
    }

    public function store (Request $request) {
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:roles,name'
      ]);

      if($validator->fails()) return response()->json($validator->errors(), 422);

      $role = new Role;
      $role->name = $request->name;
      $role->save();

      $response = [
        'data' => $role,
        'message' => '"' . $role->name . '" has been successfully added.'
      ];

      return response()->json($response);
    }

    public function update ($id, Request $request) {
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:roles,name,'.$id
      ]);

      if($validator->fails()) return response()->json($validator->errors(), 422);

      $role = Role::find($id);
      $role->name = $request->name;
      $role->update();

      $obj = Role::where('id', $id)->with(['permissions' => function ($qry) {
        $qry->select('id', 'name');
      }])->first();

      $response = [
        'data' => $obj,
        'message' => '"' . $role->name . '" has been successfully updated.'
      ];

      return response()->json($response);
    }

    public function delete ($id) {
      $role = Role::find($id);
      $savedRole = $role;
      $role->delete();

      $response = [
        'data' => $role,
        'message' => '"' . $role->name . '" role has been successfully deleted.'
      ];

      return response()->json($response);
    }
}
