<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Gallery;

class SearchController extends BaseController
{
    public function get (Request $request) {
      $key = $request->key;

      $items = Gallery::where('name', $key)
              ->with(['albummaps' => function ($qry) {
                $qry->with(['album' => function ($qry) {
                  $qry->with('photos')
                      ->with('tags')
                      ->with(['gallerymaps' => function ($qry) {
                      $qry->with('gallery');
                  }]);
                }]);
              }])
              ->get();

      return response()->json($items, 200);
    }
}
