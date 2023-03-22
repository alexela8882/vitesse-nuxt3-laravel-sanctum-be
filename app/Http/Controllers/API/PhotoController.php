<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Photo;
use App\Models\Gallery;
use App\Models\GalleryPhotoMap as GPMap;

use Validator;

class PhotoController extends BaseController
{

    public  function _get ($token) {
      $photo = Photo::where('_token', $token)
              ->with(['gallerymaps' => function ($qry) {
                $qry->with('gallery');
              }])
              ->with('tags')
              ->first();

      $arrGalleries = [];
      if (count($photo->gallerymaps) > 0) {
        foreach ($photo->gallerymaps as $map) {
          $gallery = Gallery::where('id', $map->gallery_id)
                    ->with('tags')
                    ->first();
          array_push($arrGalleries, $gallery);
        }
      }

      $arrTags = [];
      foreach ($photo->tags as $otherTag) {
        if ($otherTag->type == null) array_push($arrTags, $otherTag);
      }

      $photo->galleries = $arrGalleries;
      $photo->other_tags = $arrTags;

      return $photo;
    }


    public function get ($token) {
      $photo = $this->_get($token);
      return response()->json($photo, 200);
    }

    public function update ($token, Request $request) {
      // get photo
      $photo = Photo::where('_token', $token)->first();

      $rules = [
        'file_name' => 'required|unique:photos,file_name,'.$photo->id,
        'description' => 'required'
      ];
  
      $message = [
        'file_name.required' => 'This field is required.',
        'file_name.unique' => 'The name is already taken. Please choose another.',
        'description.required' => 'This field is required.',
      ];
      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      // update some details
      $photo->file_name = $request->file_name;
      $photo->description = $request->description;
      $photo->update();

      // delete current maps first
      GPMap::where('photo_id', $photo->id)->delete();
      // save galleries as tag
      if (count($request->galleries) > 0) {
        foreach ($request->galleries as $gallery) {
          $gpmap = new GPMap;
          $gpmap->gallery_id = $gallery['id'];
          $gpmap->photo_id = $photo->id;
          $gpmap->save();
        }
      }

      // collect all tags from photo tags and gallery tags
      $allTags = [];
      foreach ($request->tags as $tag) array_push($allTags, $tag);
      foreach ($request->gallerytags as $gallerytag) array_push($allTags, $gallerytag);

      // sync tags
      $photo->syncTags([]); // reset first
      foreach ($allTags as $allTag) $photo->attachTag($allTag['name']['en'], $allTag['type']);

      // generate photo for FE
      $_photo = $this->_get($photo->_token);

      $response = [
        'data' => $_photo,
        'message' => 'Photo ' . $_photo->file_name . ' has been updated.'
      ];

      return response()->json($response, 200);
    }
}