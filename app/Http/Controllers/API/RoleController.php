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
}
