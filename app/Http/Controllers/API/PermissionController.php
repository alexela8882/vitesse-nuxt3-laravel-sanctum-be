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
      $permissions = Permission::select('id', 'name')
              ->orderBy('id', 'asc')
              ->paginate(5);

      return response()->json($permissions);
    }
}
