<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Company;

class CompanyController extends BaseController
{
    public function all () {
      $companies = Company::all();
    
      return response()->json($companies);
    }
}