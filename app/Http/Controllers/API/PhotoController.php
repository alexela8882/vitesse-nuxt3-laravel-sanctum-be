<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

use App\Models\Photo;
use App\Models\Gallery;
use App\Models\GalleryPhotoMap as GPMap;

use Validator;
use Carbon\Carbon;

class PhotoController extends BaseController
{

    public  function _get ($token) {
      $photo = Photo::where('_token', $token)
              ->with(['gallerymaps' => function ($qry) {
                $qry->with(['gallery' => function ($qry) {
                  $qry->with('tags');
                }]);
              }])
              ->with('country')
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
        'file_name' => 'required'
      ];
  
      $message = [
        'file_name.required' => 'This field is required.',
      ];
      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      // update some details
      $photo->file_name = $request->file_name;
      if ($request->description !== '') $photo->description = $request->description;
      $photo->country_id = $request->country_id ? $request->country_id : null;
      $photo->event_date = $request->event_date ? Carbon::parse($request->event_date)->addDay()->format('Y-m-d') : null;
      $photo->update();

      // delete current maps first
      GPMap::where('photo_id', $photo->id)->delete();

      // save galleries as tag
      if ($request->galleries && count($request->galleries) > 0) {
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

    public function delete ($token) {
      // get photo
      $photo = Photo::where('_token', $token)->with('album')->first();

      // delete galleries
      GPMap::where('photo_id', $photo->id)->delete();

      // get photo
      $_photo = $photo;

      // delete photo
      $photo->delete();

      // destroy photo
      destroyPhoto($photo->album, $photo);

      // return for FE use
      $response = [
        'data' => $_photo,
        'message' => $_photo->file_name . ' has been successfully deleted.',
      ];

      return response()->json($response, 200);
    }

    public function download ($token) {
      //get photo
      $photo = Photo::where('_token', $token)->with('album')->first();

      $file = $photo->album->_token.'/'.$photo->_token.'.'.$photo->file_extension;
      return Storage::disk('images')->download($file, $photo->file_name);
    }
}
