<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Gallery;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Country;

class SearchController extends BaseController
{
    public function get (Request $request) {
      $key = $request->key;

      $albums = [];
      $photos = [];

      // search by gallery tags
      $galleries = Gallery::where('name', $key)
              ->with(['albummaps' => function ($qry) {
                $qry->with(['album' => function ($qry) {
                  $qry->with('photos')
                      ->with('country')
                      ->with('tags')
                      ->with(['gallerymaps' => function ($qry) {
                          $qry->with('gallery');
                      }]);
                }]);
              }])
              ->with(['photomaps' => function ($qry) {
                $qry->with(['photo' => function ($qry) {
                  $qry->with('album')
                      ->with('country')
                      ->with('tags')
                      ->with(['gallerymaps' => function ($qry) {
                        $qry->with('gallery');
                      }]);
                }]);
              }])
              ->get();

      // search by tags
      $albumsByTag = Album::withAnyTagsOfAnyType([$key])
                      ->with('photos')
                      ->with('country')
                      ->with('tags')
                      ->with(['gallerymaps' => function ($qry) {
                          $qry->with('gallery');
                      }])
                      ->get();

      $photosByTag = Photo::withAnyTagsOfAnyType([$key])
                      ->with('album')
                      ->with('country')
                      ->with('tags')
                      ->with(['gallerymaps' => function ($qry) {
                          $qry->with('gallery');
                      }])
                      ->get();

      // search by title and file name
      $albumsByTitle = Album::where('title', $key)
                      ->with('photos')
                      ->with('country')
                      ->with('tags')
                      ->with(['gallerymaps' => function ($qry) {
                          $qry->with('gallery');
                      }])
                      ->get();

      $photosByName = Photo::where('file_name', $key)
                      ->with('album')
                      ->with('country')
                      ->with('tags')
                      ->with(['gallerymaps' => function ($qry) {
                          $qry->with('gallery');
                      }])
                      ->get();

      // search by country
      $country = Country::where('name', 'like', '%' . $key . '%')->first();
      if ($country) {
        $albumsByCountry = Album::where('country_id', $country->id)
                          ->with('photos')
                          ->with('country')
                          ->with('tags')
                          ->with(['gallerymaps' => function ($qry) {
                              $qry->with('gallery');
                          }])
                          ->get();

        $photosByCountry = Photo::where('country_id', $country->id)
                          ->with('album')
                          ->with('country')
                          ->with('tags')
                          ->with(['gallerymaps' => function ($qry) {
                              $qry->with('gallery');
                          }])
                          ->get();

        foreach ($albumsByCountry as $albumByCountry) {
          array_push($albums, $albumByCountry);
        }

        foreach ($photosByCountry as $photoByCountry) {
          array_push($photos, $photoByCountry);
        }
      }

      $status = null;
      if (strtolower($key) === 'public') $status = 1;
      else if (strtolower($key) === 'private') $status = 0;

      // search by album status
      $albumsByStatus = Album::where('is_public', $status)
                      ->with('photos')
                      ->with('country')
                      ->with('tags')
                      ->with(['gallerymaps' => function ($qry) {
                          $qry->with('gallery');
                      }])
                      ->get();

      // retrieve
      foreach ($galleries as $gallery) {
        foreach ($gallery->albummaps as $amap) { // get albums
          array_push($albums, $amap->album);
        }

        foreach ($gallery->photomaps as $pmap) { // get photos
          if ($pmap->photo) array_push($photos, $pmap->photo);
        }
      }

      foreach ($albumsByTag as $albumByTag) {
        array_push($albums, $albumByTag);
      }

      foreach ($photosByTag as $photoByTag) {
        array_push($photos, $photoByTag);
      }

      foreach ($albumsByTitle as $albumByTitle) {
        array_push($albums, $albumByTitle);
      }

      foreach ($photosByName as $photoByName) {
        array_push($photos, $photoByName);
      }

      foreach ($albumsByStatus as $albumByStatus) {
        array_push($albums, $albumByStatus);
      }

      $response = [
        'albums' => $albums,
        'photos' => $photos
      ];

      return response()->json($response, 200);
    }
}
