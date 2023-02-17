<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

    public function update ($id, Request $request) {
      $role = Role::find($id);
      foreach ($request->all() as $key => $value) {
        if ($key !== 'api') {
          $role[$value['fieldName']] = $value['value'];
        }
      }
      $role->update();

      return response()->json($role);
    }
}
