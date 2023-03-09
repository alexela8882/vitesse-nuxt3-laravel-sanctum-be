<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Album;
use App\Models\Gallery;
use App\Models\GalleryAlbumMap as GAMap;

use Spatie\Tags\Tag;

use Validator;

class GalleryController extends BaseController
{
    public function all () {
      $galleries = Gallery::where('parent_id', null)->paginate(5);

      return $galleries;
    }

    public function listsE ($token) {
      $galleries = Gallery::where('_token', '!=', $token)->get();

      return response()->json($galleries, 200);
    }

    public function allParents ($token) {
      $galleries = Gallery::where('_token', '!=', $token)->where('parent_id', null)->get();

      return response()->json($galleries, 200);
    }

    public function get ($token) {
      $gallery = Gallery::where('_token', $token)
              ->select('id', 'parent_id', '_token', 'name')
              ->with('tags')
              ->first();

      $subgalleries = Gallery::where('parent_id', $gallery->id)->get();
      $gallery->subgalleries = $subgalleries;
  
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

    public function sync ($token, Request $request) {
      if ($request->parent) {
        // update this gallery
        $gallery = Gallery::where('_token', $token)->first();
        $gallery->parent_id = $request->parent;
        $gallery->update();

        // revoke sub-galleries of this gallery
        Gallery::where('parent_id', $gallery->id)->update(['parent_id' => null]);
      } else {
        // update this gallery
        $gallery = Gallery::where('_token', $token)->first();
        $gallery->parent_id = null;
        $gallery->update();

        // revoke sub-galleries of this gallery
        Gallery::where('parent_id', $gallery->id)->update(['parent_id' => null]);

        // re-populate gallery with selected sub-galleries
        if (count($request->subs) > 0) {
          foreach ($request->subs as $sub) {
            Gallery::where('id', $sub['id'])->update(['parent_id' => $gallery->id]);
          }
        }
      }

      $response = [
        'data' => $gallery,
        'message' => '"' . $gallery->name . '" gallery has been successfully updated.'
      ];
      return response()->json($response, 200);
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

    public function albums ($token) {
      $galleryArr = [];

      // get main gallery
      $gallery = Gallery::where('_token', $token)->first();

      // push main gallery
      array_push($galleryArr, $gallery->id);

      // push subs-galleries
      $subgalleries = Gallery::where('parent_id', $gallery->id)->pluck('id');
      foreach ($subgalleries as $subgallery) array_push($galleryArr, $subgallery);

      // get album ids from map
      $album_ids = GAMap::whereIn('gallery_id', $galleryArr)->pluck('album_id');

      // get albums
      $albums = Album::whereIn('id', $album_ids)
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with('gallery');
                }])
                ->with('country')->get();

      return response()->json($albums, 200);
    }
}
