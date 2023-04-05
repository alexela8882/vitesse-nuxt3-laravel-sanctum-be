<?php

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;

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
