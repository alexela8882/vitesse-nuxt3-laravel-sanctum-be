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

    public function allp () {
      $tags = Tag::paginate(5);

      return response()->json($tags, 200);
    }

    public function withoutType () {
      $tags = Tag::where('type', null)->get();

      return response()->json($tags, 200);
    }

    public function store (Request $request) {
      // run validation
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:tags,name',
        'type' => 'required',
      ]);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      // then store tag
      $tagWithType = Tag::findOrCreate($request->name, $request->type);
      $tagWithType->color = !$request->type || ($request->color == '#000000' || $request->color == '#ffffff') ? null : $request->color;
      $tagWithType->second_color = !$request->type || ($request->second_color == '#000000' || $request->second_color == '#ffffff') ? null : $request->second_color;
      $tagWithType->_token = generateRandomString();
      $tagWithType->update();

      $response = [
        'data' => $tagWithType,
        'message' => '"' . $tagWithType->name . '" tag was successfully added into our records.'
      ];

      return response()->json($response, 200);
    }

    public function update ($token, Request $request) {
      // get tag
      $tag = Tag::where('_token', $token)->first();

      // run validation
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:tags,name,'.$tag->id,
      ]);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      // update tag
      $tag->name = $request->name;
      $tag->type = $request->type;
      $tag->color = !$request->type || ($request->color == '#000000' || $request->color == '#ffffff') ? null : $request->color;
      $tag->second_color = !$request->type || ($request->second_color == '#000000' || $request->second_color == '#ffffff') ? null : $request->second_color;
      $tag->update();

      $response = [
        'data' => $tag,
        'message' => '"' . $tag->name . '" tag was successfully updated.'
      ];

      return response()->json($response, 200);
    }

    public function delete ($token) {
      // fetch data
      $tag = Tag::where('_token', $token)->first();
  
      $savedTag = $tag;
  
      // then delete tag
      $tag->delete();
  
      // return data to FE
      $response = [
        'data' => $savedTag,
        'message' => '"' . $savedTag->name . '" tag has been successfully deleted.'
      ];
  
      return response()->json($response);
    }
}
