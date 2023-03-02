<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Position;

class PositionController extends BaseController
{
    public function all () {
      $positions = Position::all();
    
      return response()->json($positions);
    }
}
