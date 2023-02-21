<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Validator;

class PermissionController extends BaseController
{
    public function all () {
      $permissions = Permission::select('id', '_token', 'name')
              ->orderBy('id', 'asc')
              ->where('id', '!=', 1)
              ->get();

      return response()->json($permissions);
    }

    public function syncToRole ($token, Request $request) {
      $role = Role::where('_token', $token)->first();

      $arrPerm = [];
      foreach ($request->all() as $permission) {
        array_push($arrPerm, $permission['name']);
      }

      $role->syncPermissions($arrPerm);
      $role->permissions;

      $response = [
        'data' => $role,
        'message' => 'Permissions successfully synced to role "' . $role->name . '".'
      ];

      return response()->json($response, 200);
    }
}
