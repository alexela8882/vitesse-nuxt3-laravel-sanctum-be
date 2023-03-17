<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Region;

class RegionController extends BaseController
{
    public function all () {
      $regions = Region::with('countries')->get();

      return response()->json($regions, 200);
    }
}
