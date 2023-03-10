<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Album;
use App\Models\Gallery;
use App\Models\GalleryAlbumMap as GAMap;

use Carbon\Carbon;
use Validator;

class AlbumController extends BaseController
{
    public function store ($token, Request $request) {
      $rules = [
        'title' => 'required|unique:albums,title',
        'country' => 'required',
        'venue' => 'required',
        'date' => 'required',
        'description' => 'required',
        'img_path' => 'required',
      ];
  
      $message = [
        'title.required' => 'This field is required.',
        'title.unique' => 'Ttitle already taken. Please choose another title.',
        'country.required' => 'This field is required.',
        'venue.required' => 'This field is required.',
        'date.required' => 'This field is required.',
        'description.required' => 'This field is required.',
        'img_path.required' => 'Please upload image.',
      ];

      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      $user = auth('sanctum')->user();
      $gallery = Gallery::where('_token', $token)->first();

      $album = new Album;
      $album->user_id = $user->id;
      $album->title = $request->title;
      $album->description = $request->description;
      $album->country_id = $request->country;
      $album->venue = $request->venue;
      $album->event_date = Carbon::parse($request->date)->format('Y-m-d h:i:s');
      $album->img_path = $request->img_path;
      $album->_token = generateRandomString();
      $album->save();

      // save main gallery as tag
      $gamap = new GAMap;
      $gamap->gallery_id = $gallery->id;
      $gamap->album_id = $album->id;
      $gamap->save();

      // save sub-galleries as tag
      if (count($request->subgalleries) > 0) {
        foreach ($request->subgalleries as $subgallery) {
          $gamap = new GAMap;
          $gamap->gallery_id = $subgallery['id'];
          $gamap->album_id = $album->id;
          $gamap->save();
        }
      }

      // collect all tags from main-gallery tag and sub-gallery tags
      $allTags = [];
      foreach ($request->tags as $tag) array_push($allTags, $tag);
      foreach ($request->subgallerytags as $subtag) array_push($allTags, $subtag);

      // sync tags
      foreach ($allTags as $allTag) $album->attachTag($allTag['name']['en'], $allTag['type']);

      $response = [
        'data' => $album,
        'message' => 'Album "' . $album->title . '" has been successfully added in our records.'
      ];

      return response()->json($response, 200);
    }
}
