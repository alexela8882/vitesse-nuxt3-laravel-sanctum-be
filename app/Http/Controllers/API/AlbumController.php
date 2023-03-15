<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Photo;
use App\Models\Album;
use App\Models\Gallery;
use App\Models\GalleryAlbumMap as GAMap;
use Spatie\Tags\Tag;

use Carbon\Carbon;
use Validator;

class AlbumController extends BaseController
{

    public  function _get ($token) {
      $album = Album::where('_token', $token)
              ->with('photos')
              ->with(['gallerymaps' => function ($qry) {
                $qry->with('gallery');
              }])
              ->with('tags')
              ->with('country')
              ->first();

      $arrGalleries = [];
      if (count($album->gallerymaps) > 0) {
        foreach ($album->gallerymaps as $map) {
          $gallery = Gallery::where('id', $map->gallery_id)
                    ->with('tags')
                    ->first();
          array_push($arrGalleries, $gallery);
        }
      }

      $arrTags = [];
      foreach ($album->tags as $otherTag) {
        if ($otherTag->type == null) array_push($arrTags, $otherTag);
      }

      $album->galleries = $arrGalleries;
      $album->other_tags = $arrTags;

      return $album;
    }

    public function get ($token) {
      $album = $this->_get($token);
      return response()->json($album, 200);
    }

    public function store ($token, Request $request) {
      $rules = [
        'title' => 'required|unique:albums,title',
        'country_id' => 'required',
        'venue' => 'required',
        'event_date' => 'required',
        'description' => 'required',
        'img_path' => 'required',
      ];
  
      $message = [
        'title.required' => 'This field is required.',
        'title.unique' => 'Ttitle already taken. Please choose another title.',
        'country_id.required' => 'This field is required.',
        'venue.required' => 'This field is required.',
        'event_date.required' => 'This field is required.',
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
      $album->country_id = $request->country_id;
      $album->venue = $request->venue;
      $album->event_date = Carbon::parse($request->event_date)->format('Y-m-d h:i:s');
      $album->img_path = $request->img_path;
      $album->_token = generateRandomString();
      $album->save();

      // add photos
      $photo = new Photo;
      $photo->user_id = $user->id;
      $photo->album_id = $album->id;
      $photo->file_name = $request->photo['file_name'];
      $photo->file_size = $request->photo['file_size'];
      $photo->file_type = $request->photo['file_type'];
      $photo->description = $request->description;
      $photo->_token = generateRandomString();
      $photo->save();

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

      $album->tags;

      $response = [
        'data' => $album,
        'message' => 'Album "' . $album->title . '" has been successfully added in our records.'
      ];

      return response()->json($response, 200);
    }

    public function update ($token, Request $request) {
      $rules = [
        'title' => 'required|unique:albums,title,'.$request->id,
        'country_id' => 'required',
        'venue' => 'required',
        'event_date' => 'required',
        'description' => 'required',
        'img_path' => 'required',
      ];
  
      $message = [
        'title.required' => 'This field is required.',
        'title.unique' => 'Ttitle already taken. Please choose another title.',
        'country_id.required' => 'This field is required.',
        'venue.required' => 'This field is required.',
        'event_date.required' => 'This field is required.',
        'description.required' => 'This field is required.',
        'img_path.required' => 'Please upload image.',
      ];

      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      $user = auth('sanctum')->user();

      $album = Album::where('_token', $token)->first();
      $album->user_id = $user->id;
      $album->title = $request->title;
      $album->description = $request->description;
      $album->country_id = $request->country_id;
      $album->venue = $request->venue;
      $album->event_date = Carbon::parse($request->event_date)->format('Y-m-d h:i:s');
      $album->img_path = $request->img_path;
      $album->update();

      // delete current maps first
      GAMap::where('album_id', $album->id)->delete();
      // save galleries as tag
      if (count($request->subgalleries) > 0) {
        foreach ($request->subgalleries as $subgallery) {
          $gamap = new GAMap;
          $gamap->gallery_id = $subgallery['id'];
          $gamap->album_id = $album->id;
          $gamap->save();
        }
      }

      // collect all tags from album tags and gallery tags
      $allTags = [];
      foreach ($request->tags as $tag) array_push($allTags, $tag);
      foreach ($request->subgallerytags as $subtag) array_push($allTags, $subtag);

      // sync tags
      $album->syncTags([]); // reset first
      foreach ($allTags as $allTag) $album->attachTag($allTag['name']['en'], $allTag['type']);

      $_album = $this->_get($album->_token);

      $response = [
        'data' => $_album,
        'message' => 'Album "' . $_album->title . '" has been successfully updated.'
      ];

      return response()->json($response, 200);
    }

    public function addPhoto ($token, Request $request) {
      // get album
      $album = Album::where('_token', $token)->first();

      // get current user
      $user = auth('sanctum')->user();

      $photo = new Photo;
      $photo->user_id = $user->id;
      $photo->album_id = $album->id;
      $photo->file_name = $request->image;
      $photo->file_size = 10;
      $photo->file_type = 'img/png';
      $photo->description = $request->description;
      $photo->_token = generateRandomString();
      $photo->save();

      $photo->album = $album;

      $response = [
        'data' => $photo,
        'message' => 'Success!',
      ];

      return response()->json($response);
    }
}
