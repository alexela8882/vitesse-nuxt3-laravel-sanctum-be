<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Photo;
use App\Models\Album;
use App\Models\Gallery;
use App\Models\GalleryAlbumMap as GAMap;

use Spatie\Tags\Tag;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Validator;
use File;
use ZipArchive;

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
      $req_photo = json_decode($request->photo);
      $req_subgalleries = json_decode($request->subgalleries);
      $req_subgallerytags = json_decode($request->subgallerytags);
      $req_tags = json_decode($request->tags);

      $rules = [
        'title' => 'required|unique:albums,title',
        'country_id' => 'required',
        'venue' => 'required',
        'event_date' => 'required',
        'description' => 'required',
        'img_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4086',
      ];
  
      $message = [
        'title.required' => 'This field is required.',
        'title.unique' => 'Ttitle already taken. Please choose another title.',
        'country_id.required' => 'This field is required.',
        'venue.required' => 'This field is required.',
        'event_date.required' => 'This field is required.',
        'description.required' => 'This field is required.',
        'img_path.required' => 'Please upload image.',
        'img_path.max' => 'Please upload image with maximum size of 4086.',
        'img_path.mimes' => 'Image with jpeg, png, jpg, gif & svg file type is only allowed.',
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

      // add photo
      $photo = new Photo;
      $photo->user_id = $user->id;
      $photo->album_id = $album->id;
      $photo->file_name = $req_photo->file_name;
      $photo->file_size = $req_photo->file_size;
      $photo->file_type = $req_photo->file_type;
      $photo->file_extension = $request->img_path->getClientOriginalExtension();
      $photo->description = $request->description;
      $photo->_token = generateRandomString();
      $photo->save();

      // upload image
      $file_location = 'images/'.$album->_token;
      $image = $photo->_token . '.' . $photo->file_extension;
      $request->img_path->move(public_path($file_location), $image);

      // generate thumbnail
      generateThumbnail($album, $photo);

      // save main gallery as tag
      $gamap = new GAMap;
      $gamap->gallery_id = $gallery->id;
      $gamap->album_id = $album->id;
      $gamap->save();

      // save sub-galleries as tag
      if (count($req_subgalleries) > 0) {
        foreach ($req_subgalleries as $subgallery) {
          $gamap = new GAMap;
          $gamap->gallery_id = $subgallery->id;
          $gamap->album_id = $album->id;
          $gamap->save();
        }
      }

      // collect all tags from main-gallery tag and sub-gallery tags
      $allTags = [];
      foreach ($req_tags as $tag) array_push($allTags, $tag);
      foreach ($req_subgallerytags as $subtag) array_push($allTags, $subtag);

      // sync tags
      foreach ($allTags as $allTag) $album->attachTag($allTag->name->en, $allTag->type);

      $album->tags;

      $response = [
        'data' => $album,
        'message' => 'Album "' . $album->title . '" has been successfully added in our records.'
      ];

      return response()->json($response, 200);
    }

    public function update ($token, Request $request) {
      $req_photo = json_decode($request->photo);
      $req_subgalleries = json_decode($request->subgalleries);
      $req_subgallerytags = json_decode($request->subgallerytags);
      $req_tags = json_decode($request->tags);

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
      if (count($req_subgalleries) > 0) {
        foreach ($req_subgalleries as $subgallery) {
          $gamap = new GAMap;
          $gamap->gallery_id = $subgallery->id;
          $gamap->album_id = $album->id;
          $gamap->save();
        }
      }

      // collect all tags from album tags and gallery tags
      $allTags = [];
      foreach ($req_tags as $tag) array_push($allTags, $tag);
      foreach ($req_subgallerytags as $subtag) array_push($allTags, $subtag);

      // sync tags
      $album->syncTags([]); // reset first
      foreach ($allTags as $allTag) $album->attachTag($allTag->name->en, $allTag->type);

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

      $rules = [
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
      ];
  
      $message = [
        'image.required' => 'Please upload an image.',
        'image.max' => 'Please upload image with maximum size of 2048.',
        'image.mimes' => 'Image with jpeg, png, jpg, gif & svg file type is only allowed.'
      ];
      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      // get current user
      $user = auth('sanctum')->user();

      $photo = new Photo;
      $photo->user_id = $user->id;
      $photo->album_id = $album->id;
      $photo->file_name = $request->file('image')->getClientOriginalName();
      $photo->file_size = $request->file('image')->getSize();
      $photo->file_type = $request->file('image')->getClientMimeType();
      $photo->file_extension = $request->image->getClientOriginalExtension();
      $photo->description = $request->description;
      $photo->_token = generateRandomString();
      $photo->save();

      // upload image
      $file_location = 'images/'.$album->_token;
      $image = $photo->_token . '.' . $photo->file_extension;
      $request->image->move(public_path($file_location), $image);

      // include album in collection
      $photo->album = $album;

      // generate thumbnail
      generateThumbnail($album, $photo);

      $response = [
        'data' => $photo,
        'message' => 'Success!',
      ];

      return response()->json($response);
    }

    // paginated photos
    public function pphotos ($token) {
      // get album
      $album = Album::where('_token', $token)->first();

      $photos = Photo::where('album_id', $album->id)->paginate(6);

      return response()->json($photos, 200);
    }

    public function delete ($token) {
      // get album
      $album = Album::where('_token', $token)->first();

      // store album
      $_album = $album;

      // delete photos
      Photo::where('album_id', $album->id)->delete();

      // delete galleries
      GAMap::where('album_id', $album->id)->delete();

      // delete album
      $album->delete();

      // destroy album together with the photos
      destroyAlbum($album);

      // return for FE use
      $response = [
        'data' => $_album,
        'message' => $_album->title . ' has been successfully deleted.'
      ];

      return response()->json($response, 200);
    }

    public function empty ($token) {
      // get album
      $album = Album::where('_token', $token)->with('photos')->first();

      // store album
      $_album = $album;

      // delete photos
      Photo::where('album_id', $album->id)->delete();

      // destroy all photos from this album
      emptyAlbum($album, $album->photos);

      // return for FE use
      $response = [
        'data' => $_album,
        'message' => $_album->title . ' has been successfully emptied.'
      ];

      return response()->json($response, 200);
    }

    public function downloadAlbum ($token) {
      // // get album
      $album = Album::where('_token', $token)->first();

      $zip = new ZipArchive();
      $zipFileName = $album['title'] . '.zip';
      $dir = public_path($zipFileName);

      if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) == TRUE) {
        $files = File::files(public_path('images/' . $token));
        foreach ($files as $key => $value) {
          $relativeName = basename($value);
          $zip->addFile($value, $relativeName);
        }
        $zip->close();
      }

      $headers = array(
        'Content-Type' => 'application/octet-stream',
      );

      if(file_exists($dir)) return response()->download($dir, $zipFileName, $headers)->deleteFileAfterSend(true);
      else return response()->json('File not found.', 404);
    }
}
