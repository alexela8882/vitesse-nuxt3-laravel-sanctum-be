<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use Spatie\Tags\Tag;

use Validator;

class TagController extends BaseController
{
    public function all () {
      $tags = Tag::all();

      return response()->json($tags, 200);
    }

    public function store (Request $request) {
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:tags,name',
      ]);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      $tagWithType = Tag::findOrCreate($request->name, $request->type);

      $response = [
        'data' => $tagWithType,
        'message' => '"' . $tagWithType->name . '" tag was successfully added into our records.'
      ];

      return response()->json($response);
    }
}
