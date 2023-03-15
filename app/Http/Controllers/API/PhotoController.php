<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Photo;

use Validator;

class PhotoController extends BaseController
{

    public function get ($token) {
      $photo = Photo::where('_token', $token)->first();

      return response()->json($photo, 200);
    }

    public function update ($token, Request $request) {
      $rules = [
        'description' => 'required',
      ];
  
      $message = [
        'description.required' => 'This field is required.',
      ];
      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      $photo = Photo::where('_token', $token)->first();
      $photo->description = $request->description;
      $photo->update();

      $response = [
        'data' => $photo,
        'message' => 'Success!'
      ];

      return response()->json($response, 200);
    }
}
