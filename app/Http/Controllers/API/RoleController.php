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
              ->get();

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

      return response()->json($role);
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

      return response()->json($obj);
    }
}
