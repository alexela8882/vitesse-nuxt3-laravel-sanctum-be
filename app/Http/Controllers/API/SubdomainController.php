<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Subdomain;

class SubdomainController extends BaseController
{
    public function all () {
      $subdomains = Subdomain::all();
      return response()->json($subdomains);
    }
}
