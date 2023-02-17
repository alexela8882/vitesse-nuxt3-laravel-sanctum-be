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

    public function store (Request $request) {
      $role = new Role;
      foreach ($request->all() as $key => $value) {
        if ($key !== 'api' && $key !== 'store') {
          $role[$value['fieldName']] = $value['value'];
        }
      }
      $role->save();

      return response()->json($role);
    }

    public function update ($id, Request $request) {
      $role = Role::find($id);
      foreach ($request->all() as $key => $value) {
        if ($key !== 'api' && $key !== 'store') {
          $role[$value['fieldName']] = $value['value'];
        }
      }
      $role->update();

      $obj = Role::where('id', $id)->with(['permissions' => function ($qry) {
        $qry->select('id', 'name');
      }])->first();

      return response()->json($obj);
    }
}
