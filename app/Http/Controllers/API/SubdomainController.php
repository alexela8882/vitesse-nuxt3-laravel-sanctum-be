<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Subdomain;

use Validator;

class SubdomainController extends BaseController
{
    public function all () {
      $subdomains = Subdomain::all();
      return response()->json($subdomains);
    }

    public function allPaginated () {
      $subdomains = Subdomain::paginate(10);
      return response()->json($subdomains);
    }

    public function store (Request $request) {
      // run validation
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:subdomains,name'
      ]);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      // then store subdomain
      $subdomain = new Subdomain;
      $subdomain->name = $request->name;
      $subdomain->_token = generateRandomString();
      $subdomain->save();

      $response = [
        'data' => $subdomain,
        'message' => '"' . $subdomain->name . '" subdomain was successfully added into our records.'
      ];

      return response()->json($response, 200);
    }

    public function update ($token, Request $request) {
      // get subdomain
      $subdomain = Subdomain::where('_token', $token)->first();

      // run validation
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:subdomains,name,'.$subdomain->id
      ]);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      // then update subdomain
      $subdomain->name = $request->name;
      $subdomain->_token = generateRandomString();
      $subdomain->update();

      $response = [
        'data' => $subdomain,
        'message' => '"' . $subdomain->name . '" subdomain was successfully added into our records.'
      ];

      return response()->json($response, 200);
    }

    public function delete ($token) {
      // fetch data
      $subdomain = Subdomain::where('_token', $token)->first();
  
      $savedSubdomain = $subdomain;
  
      // then delete subdomain
      $subdomain->delete();
  
      // return data to FE
      $response = [
        'data' => $savedSubdomain,
        'message' => '"' . $savedSubdomain->name . '" subdomain has been successfully deleted.'
      ];
  
      return response()->json($response);
    }
}
