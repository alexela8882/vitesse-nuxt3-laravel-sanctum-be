<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Gallery;

use Validator;

class GalleryController extends BaseController
{
    public function all () {
      $galleries = Gallery::where('parent_id', null)->paginate(5);

      return $galleries;
    }

    public function get ($token) {
      $gallery = Gallery::where('_token', $token)
              ->select('id', '_token', 'name')
              ->first();
  
      return response()->json($gallery);
    }

    public function store (Request $request) {
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:galleries,name',
      ]);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);
  
      $gallery = new Gallery;
      $gallery->name = $request->name;
      $gallery->_token = generateRandomString();
      $gallery->save();
  
      $response = [
        'data' => $gallery,
        'message' => '"' . $gallery->name . '" has been successfully added.'
      ];
  
      return response()->json($response);
    }

    public function update ($token, Request $request) {
      // fetch data
      $gallery = Gallery::where('_token', $token)->first();
  
      // run validation
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:galleries,name,'.$gallery->id,
      ]);
      if($validator->fails()) return response()->json($validator->errors(), 422);
  
      // then update
      $gallery->name = $request->name;
      $gallery->update();
  
      $response = [
        'data' => $gallery,
        'message' => 'Gallery "' . $request->name . '" has been successfully updated.'
      ];
  
      return response()->json($response);
    }

    public function delete ($token) {
      // fetch data
      $gallery = Gallery::where('_token', $token)->first();
  
      $savedGallery = $gallery;
  
      // then delete gallery
      $gallery->delete();
  
      // return data to FE
      $response = [
        'data' => $savedGallery,
        'message' => '"' . $savedGallery->name . '" gallery has been successfully deleted.'
      ];
  
      return response()->json($response);
    }
}
