<?php

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;

use App\Models\User;
use App\Models\Gallery;

if (! function_exists('generateRandomString')) {
  function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}

if (! function_exists('generateThumbnail')) {
  function generateThumbnail($album, $photo) {
    // create thumbnail path inside album directory
    $thumnailPath = public_path('images/'.$album->_token.'/thumbnails/');
    if(!\File::isDirectory($thumnailPath)) \File::makeDirectory($thumnailPath, 0711, true, true);

    // open image file to resize for thumbnail
    $img = Image::make('images/'.$album->_token.'/'.$photo->_token.'.'.$photo->file_extension);

    // resize the image to a width of 300 and constrain aspect ratio (auto height)
    $img->resize(300, null, function ($constraint) {
      $constraint->aspectRatio();
    });

    // finally we save the image as a new file
    $savePath = 'images/'.$album->_token.'/thumbnails/'.$photo->_token.'-thumbnail.'.$photo->file_extension;
    $img->save($savePath);

    // destroy resource
    $img->destroy();
  }
}

if (! function_exists('destroyAlbum')) {
  function destroyAlbum($album) {
    // get folder path
    $folderPath = 'images/'.$album->_token;

    // delete folder and images
    if (file_exists($folderPath)) File::deleteDirectory(public_path($folderPath));
  }
}

if (! function_exists('destroyPhoto')) {
  function destroyPhoto($album, $photo) {
    // get path
    $imgPath = 'images/'.$album->_token.'/'.$photo->_token.'.'.$photo->file_extension;
    $imgPathThumbnail = 'images/'.$album->_token.'/thumbnails/'.$photo->_token.'-thumbnail.'.$photo->file_extension;

    // delete images
    if (file_exists($imgPath)) File::delete($imgPath);
    if (file_exists($imgPathThumbnail)) File::delete($imgPathThumbnail);
  }
}

if (! function_exists('emptyAlbum')) {
  function emptyAlbum($album, $photos) {
    // loop though photos
    foreach ($photos as $photo) {
      // get photo path
      $imgPath = 'images/'.$album->_token.'/'.$photo->_token.'.'.$photo->file_extension;
      $imgPathThumbnail = 'images/'.$album->_token.'/thumbnails/'.$photo->_token.'-thumbnail.'.$photo->file_extension;

      // delete photos
      if (file_exists($imgPath)) File::delete($imgPath);
      if (file_exists($imgPathThumbnail)) File::delete($imgPathThumbnail);
    }
  }
}

if (! function_exists('checkUserGalleryAccess')) {
  function checkUserGalleryAccess($galleryToken) {
    $_user = auth('sanctum')->user();

    // if guest return true
    if (!$_user) return 1;

    $user = User::where('id', $_user->id)
            ->with(['galleryaccessmaps' => function ($qry) {
              $qry->with('gallery');
            }])
            ->first();

    $gallery_tokens = [];
    foreach ($user->galleryaccessmaps as $map) {
      array_push($gallery_tokens, $map->gallery->_token);

      // push parent token for subgallery access
      if (!in_array($map->gallery->parent['_token'], $gallery_tokens)) {
        array_push($gallery_tokens, $map->gallery->parent['_token']);
      }
    }

    // check if user have access
    if ($_user->id == 1) {
      return 1;
    } else {
      if (!in_array($galleryToken, $gallery_tokens)) return 0;
      else return 1;
    }
  }
}

if (! function_exists('checkUserGalleryAlbumAccess')) {
  function checkUserGalleryAlbumAccess($galleryToken) {
    $_user = auth('sanctum')->user();

    // if guest return true
    if (!$_user) return 1;

    $user = User::where('id', $_user->id)
            ->with(['galleryaccessmaps' => function ($qry) {
              $qry->with('gallery');
            }])
            ->first();

    $gallery_tokens = [];
    foreach ($user->galleryaccessmaps as $map) {
      array_push($gallery_tokens, $map->gallery->_token);
    }

    // check if user have access
    if ($_user->id == 1) {
      return 1;
    } else {
      if (!in_array($galleryToken, $gallery_tokens)) return 0;
      else return 1;
    }
  }
}

if (! function_exists('getUserGalleries')) {
  function getUserGalleries() {
    $_user = auth('sanctum')->user();

    $user = User::where('id', $_user->id)
            ->with(['galleryaccessmaps' => function ($qry) {
              $qry->with(['gallery' => function ($qry) {
                $qry->with('subdomain');
              }]);
            }])
            ->first();

    $galleries = [];
    foreach ($user->galleryaccessmaps as $map) {
      array_push($galleries, $map->gallery);

      // push parent token for subgallery access
      // if ($map->gallery->parent) {
      //   if (!in_array($map->gallery->parent, $galleries)) {
      //     array_push($galleries, $map->gallery->parent);
      //   }
      // }
    }

    $data = null;

    if ($_user->id == 1) {
      $data = Gallery::with('subdomain')->get();
    } else {
      $data = $galleries;
    }

    return $data;
  }
}

if (! function_exists('getSubdomain')) {
  function getSubdomain() {
    $url = request()->getHost(); // get current backend url
    // $url request()->headers->get('referer'); // get referer url
    $arrUrl = explode('.', $url);
    $subdomain = count($arrUrl) > 1 ? explode('//', $arrUrl[0])[1] : null;
    return $subdomain;
  }
}

if (! function_exists('getRefererSubdomain')) {
  function getRefererSubdomain() {
    $url = request()->headers->get('referer');
    $arrUrl = explode('.', $url);
    $subdomain = count($arrUrl) > 1 ? explode('//', $arrUrl[0])[1] : null;
    return $subdomain;
  }
}
