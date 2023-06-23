<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Photo;
use App\Models\Album;
use App\Models\Gallery;
use App\Models\GalleryAlbumMap as GAMap;
use App\Models\GalleryPhotoMap as GPMap;

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
              ->with(['photos' => function ($qry) {
                $qry->with(['gallerymaps' => function ($qry) {
                  $qry->with(['gallery' => function ($qry) {
                    $qry->with('tags');
                  }]);
                }])
                ->with('tags');
              }])
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

      // get paginated photos
      // $pphotos = Photo::where('album_id', $album->id)
      //                   ->with(['gallerymaps' => function ($qry) {
      //                     $qry->with(['gallery' => function ($qry) {
      //                       $qry->with('tags');
      //                     }]);
      //                   }])
      //                   ->with('tags')
      //                   ->paginate(1);

      // $album->photos = $pphotos;

      return $album;
    }

    public function mostRecent () {
      $albums = Album::with('country')
                ->with('photos')
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with('gallery');
                }])
                ->with('tags')
                ->limit(3)
                ->get();

      return response()->json($albums, 200);
    }

    public function get ($token) {
      $album = $this->_get($token);
      return response()->json($album, 200);
    }

    public function public () {
      $albums = Album::where('is_public', true)
                ->with('country')
                ->with('photos')
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with('gallery');
                }])
                ->with('tags')
                ->get();

      return response()->json($albums, 200);
    }

    public function publicGet ($token) {
      $album = Album::where('is_public', true)
                ->where('_token', $token)
                ->with('country')
                ->with('photos')
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with('gallery');
                }])
                ->with('tags')
                ->first();

      return response()->json($album, 200);
    }

    public function store ($token, Request $request) {
      // $req_photos = json_decode($request->photos);
      // $req_images = json_decode($request->images_array);
      $req_country = json_decode($request->country);
      $req_date_range = json_decode($request->date_range);
      $req_subgalleries = json_decode($request->subgalleries);
      $req_subgallerytags = json_decode($request->subgallerytags);
      $req_tags = json_decode($request->tags);

      $rules = [
        'title' => 'required|unique:albums,title',
        'country' => 'required',
        'date_range' => 'required',
        // 'description' => 'required',
        // 'img_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:50000',
      ];
  
      $message = [
        'title.required' => 'This field is required.',
        'title.unique' => 'Ttitle already taken. Please choose another title.',
        'country.required' => 'This field is required.',
        'date_range.required' => 'This field is required.',
        // 'description.required' => 'This field is required.',
        // 'img_path.required' => 'Please upload image.',
        // 'img_path.max' => 'Please upload image with maximum size of 50000.',
        // 'img_path.mimes' => 'Image with jpeg, png, jpg, gif & svg file type is only allowed.',
      ];

      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      $user = auth('sanctum')->user();
      $gallery = Gallery::where('_token', $token)->first();

      $album = new Album;
      $album->user_id = $user->id;
      $album->title = $request->title;
      $album->description = ($request->description && $request->description !== "null") ? $request->description : null;
      $album->country_id = $req_country->id;
      $album->venue = ($request->venue && $request->venue !== "null") ? $request->venue : null;
      $album->event_date = Carbon::parse($req_date_range[0])->addDay()->format('Y-m-d h:i:s');
      $album->date_from = Carbon::parse($req_date_range[0])->addDay()->format('Y-m-d h:i:s');
      $album->date_to = Carbon::parse($req_date_range[1] ? $req_date_range[1] : $req_date_range[0])->addDay()->format('Y-m-d h:i:s');
      $album->img_path = $request->img_path;
      $album->_token = generateRandomString();
      $album->save();

      // save main gallery as tag
      $gamap = new GAMap;
      $gamap->gallery_id = $gallery->id;
      $gamap->album_id = $album->id;
      $gamap->save();

      // also save parent gallery as tag
      if ($gallery->parent_id) {
        $gamap = new GAMap;
        $gamap->gallery_id = $gallery->parent_id;
        $gamap->album_id = $album->id;
        $gamap->save();
      }

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

    public function changeStatus ($token, Request $request) {
      $album = Album::where('_token', $token)->first();
      $album->is_public = $request->is_public;
      $album->update();

      $status = $request->is_public ? 'PUBLIC' : 'PRIVATE';

      $response = [
        'data' => $album,
        'message' => $album->title . ' set to "' . $status . '"'
      ];

      return response()->json($response, 200);
    }

    public function update ($token, Request $request) {
      $req_photo = json_decode($request->photo);
      $req_country = json_decode($request->country);
      $req_subgalleries = json_decode($request->subgalleries);
      $req_subgallerytags = json_decode($request->subgallerytags);
      $req_tags = json_decode($request->tags);
      $req_date_range = json_decode($request->date_range);

      $rules = [
        'title' => 'required|unique:albums,title,'.$request->id,
        'country' => 'required',
        'date_range' => 'required',
        'img_path' => 'required',
      ];
  
      $message = [
        'title.required' => 'This field is required.',
        'title.unique' => 'Ttitle already taken. Please choose another title.',
        'country.required' => 'This field is required.',
        'date_range.required' => 'This field is required.',
        'img_path.required' => 'Please upload image.',
      ];

      $validator = Validator::make($request->all(), $rules, $message);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);

      $user = auth('sanctum')->user();

      $album = Album::where('_token', $token)->first();
      $album->user_id = $user->id;
      $album->title = $request->title;
      $album->description = ($request->description && $request->description !== "null") ? $request->description : null;
      $album->country_id = $req_country->id;
      $album->venue = ($request->venue && $request->venue !== "null") ? $request->venue : null;
      $album->event_date = Carbon::parse($req_date_range[0])->addDay()->format('Y-m-d h:i:s');
      $album->date_from = Carbon::parse($req_date_range[0])->addDay()->format('Y-m-d h:i:s');
      $album->date_to = Carbon::parse($req_date_range[1] ? $req_date_range[1] : $req_date_range[0])->addDay()->format('Y-m-d h:i:s');
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
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:50000'
      ];
  
      $message = [
        'image.required' => 'Please upload an image.',
        'image.max' => 'Please upload image with maximum size of 50000.',
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
      $photo->description = ($request->description && $request->description !== "null") ? $request->description : null;
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

    public function uploadPhotos ($galleryToken, $albumToken, Request $request) {
      $req_photo = json_decode($request->photo);

      $user = auth('sanctum')->user();

      $album = Album::where('_token', $albumToken)->first();

      // get main gallery
      $gallery = Gallery::where('_token', $galleryToken)->first();

      // upload images
      foreach ($request->file('images_array') as $iindex => $req_image) {
        // handle file type
        if (
          $req_image->getClientOriginalExtension() == 'png' ||
          $req_image->getClientOriginalExtension() == 'jpg' ||
          $req_image->getClientOriginalExtension() == 'jpeg' ||
          $req_image->getClientOriginalExtension() == 'gif'
        ) {

          // add photo to database
          $photo = new Photo;
          $photo->user_id = $user->id;
          $photo->album_id = $album->id;
          $photo->file_name = $req_photo->file_name;
          $photo->file_size = $req_photo->file_size;
          $photo->file_type = $req_photo->file_type;
          $photo->description = $req_photo->country_id;
          $photo->country_id = $req_photo->country_id;
          $photo->event_date = Carbon::parse($req_photo->event_date)->addDay()->format('Y-m-d h:i:s');
          $photo->file_extension = $req_image->getClientOriginalExtension();
          $photo->description = ($req_photo->description && $req_photo->description !== "null") ? $req_photo->description : null;
          $photo->_token = generateRandomString();
          $photo->save();

          // delete current maps first
          GPMap::where('photo_id', $photo->id)->delete();

          // save main gallery as tag
          $gpmap = new GPMap;
          $gpmap->gallery_id = $gallery->id;
          $gpmap->photo_id = $photo->id;
          $gpmap->save();

          // collection parent ids
          $parentGalleryIds = [];

          // save sub-galleries as tag
          if (count($req_photo->galleries) > 0) {
            foreach ($req_photo->galleries as $gallery) {
              $gpmap = new GPMap;
              $gpmap->gallery_id = $gallery->id;
              $gpmap->photo_id = $photo->id;
              $gpmap->save();

              // also save parent gallery as tag
              if ($gallery->parent_id) {
                // prevent duplicate ids
                if (!in_array($gallery->parent_id, $parentGalleryIds)) {
                  array_push($parentGalleryIds, $gallery->parent_id);
                }
              }
            }
          }

          // store unique parent ids
          foreach ($parentGalleryIds as $parentgalleryid) {
            $gpmap = new GPMap;
            $gpmap->gallery_id = $parentgalleryid;
            $gpmap->photo_id = $photo->id;
            $gpmap->save();
          }

          // collect all tags from photo tags and gallery tags
          $allTags = [];
          foreach ($req_photo->tags as $tag) array_push($allTags, $tag);
          foreach ($req_photo->gallerytags as $gallerytag) array_push($allTags, $gallerytag);

          // sync tags
          $photo->syncTags([]); // reset first
          foreach ($allTags as $allTag) $photo->attachTag($allTag->name->en, $allTag->type);

          // generate file for uploading
          $file_location = 'images/'.$album->_token;
          $file = $photo->_token . '.' . $photo->file_extension;

          // upload image
          $req_image->move(public_path($file_location), $file);

          // generate thumbnails
          generateThumbnail($album, $photo);
        } else return response()->json('File type not allowed.', 422);
      }

      $response = [
        'data' => $album,
        'message' => 'New photos has been successfully uploaded under album "' . $album->title
      ];

      return response()->json($response, 200);
    }

    // paginated photos
    public function pphotos ($token) {
      // get album
      $album = Album::where('_token', $token)->first();

      $photos = Photo::where('album_id', $album->id)
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with(['gallery' => function ($qry) {
                    $qry->with('tags');
                  }]);
                }])
                ->with('tags')
                ->paginate(6);

      return response()->json($photos, 200);
    }

    public function delete ($token) {
      // get album
      $album = Album::where('_token', $token)->first();

      // store album
      $_album = $album;

      // get photos
      $photos = Photo::where('album_id', $album->id)->get();

      // delete gallery maps
      // album maps
      GAMap::where('album_id', $album->id)->delete();
      // photo maps
      foreach ($photos as $photo) {
        GPMap::where('photo_id', $photo->id)->delete();
        // delete photo
        $photo->delete();
      }

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
