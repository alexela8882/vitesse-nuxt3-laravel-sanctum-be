<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Region;
use App\Models\Gallery;
use App\Models\GalleryAlbumMap as GAMap;
use App\Models\GalleryAccessMap as GUAMap;
use App\Models\GalleryPhotoMap as GPMap;

use Spatie\Tags\Tag;

use Validator;
use Carbon\Carbon;

class GalleryController extends BaseController
{

    public function puall () {
      $galleries = Gallery::all();

      return response()->json($galleries, 200);
    }

    public function uall () {
      $gallery_ids = [];
      $user = auth('sanctum')->user();

      if ($user) $galleries = getUserGalleries();
      else $galleries = Gallery::all();

      foreach ($galleries as $ugallery) {
        array_push($gallery_ids, $ugallery->id);
      }

      $galleries = Gallery::whereIn('id', $gallery_ids)
                  ->with(['albummaps' => function ($qry) {
                    $qry->orderBy('album_id', 'desc')
                        ->with(['album' => function ($qry) {
                          $qry->with('country')
                              ->with('tags')
                              ->with('photos');
                        }]);
                  }])
                  ->get();

      // return response()->json($galleries, 200);
      // $galleries = Gallery::with('tags')->get();

      return response()->json($galleries, 200);
    }

    // based on user
    public function buall () {
      $user = auth('sanctum')->user();

      if ($user) $galleries = getUserGalleries();
      else $galleries = Gallery::with('subdomain')->get();

      return response()->json($galleries, 200);
    }

    public function all () {
      $galleries = Gallery::paginate(10);

      return $galleries;
    }

    public function ball () {
      $gallery_ids = [];
      $user = auth('sanctum')->user();

      if ($user) $galleries = getUserGalleries();
      else $galleries = Gallery::all();

      foreach ($galleries as $ugallery) {
        array_push($gallery_ids, $ugallery->id);
      }

      $galleries = Gallery::whereIn('id', $gallery_ids)->with('subdomain')->paginate(10);

      return response()->json($galleries, 200);
    }

    public function listsE ($token) {
      $galleries = Gallery::where('_token', '!=', $token)->get();

      return response()->json($galleries, 200);
    }

    public function parents () {
      $gallery_ids = [];
      $user = auth('sanctum')->user();

      if ($user) $galleries = getUserGalleries();
      else $galleries = Gallery::where('parent_id', null)->get();

      foreach ($galleries as $ugallery) {
        array_push($gallery_ids, $ugallery->id);
      }

      $galleries = Gallery::whereIn('id', $gallery_ids)
                  ->where('parent_id', null)
                  ->with(['albummaps' => function ($qry) {
                    $qry->orderBy('album_id', 'desc')
                        ->with(['album' => function ($qry) {
                          $qry->with('country')
                              ->with('tags')
                              ->with('photos');
                        }]);
                  }])
                  ->get();

      return response()->json($galleries, 200);
    }

    public function allParents ($token) {
      $galleries = Gallery::where('_token', '!=', $token)->where('parent_id', null)->get();

      return response()->json($galleries, 200);
    }

    public function generalGet ($token) {
      $gallery = Gallery::where('_token', $token)
              ->select('id', 'parent_id', '_token', 'name')
              ->with('tags')
              ->first();

      $subgalleries = Gallery::where('parent_id', $gallery->id)->with('tags')->get();
      $gallery->subgalleries = $subgalleries;

      return $gallery;
    }

    public function get ($token) {
      // check user access first
      $check = checkUserGalleryAccess($token);
      if ($check == 0) return response()->json('unauthorized', 403);

      $gallery = $this->generalGet($token);
  
      return response()->json($gallery);
    }

    public function store (Request $request) {
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:galleries,name',
      ]);
  
      if($validator->fails()) return response()->json($validator->errors(), 422);
  
      $gallery = new Gallery;
      $gallery->name = $request->name;
      $gallery->subdomain_id = $request->subdomain_id;
      $gallery->color = $request->color == '#000000' || $request->color == '#ffffff' ? null : $request->color;
      $gallery->second_color = $request->second_color == '#000000' || $request->second_color == '#ffffff' ? null : $request->second_color;
      $gallery->_token = generateRandomString();
      $gallery->save();
  
      $response = [
        'data' => $gallery,
        'message' => '"' . $gallery->name . '" has been successfully added.'
      ];

      // assign this gallery in user gallery access
      $_user = auth('sanctum')->user();
      $guamap = new GUAMap;
      $guamap->gallery_id = $gallery->id;
      $guamap->user_id = $_user->id;
      $guamap->save();
  
      return response()->json($response);
    }

    public function update ($token, Request $request) {
      // check user access first
      $check = checkUserGalleryAccess($token);
      if ($check == 0) return response()->json('unauthorized', 403);

      // fetch data
      $gallery = Gallery::where('_token', $token)->first();
  
      // run validation
      $validator = Validator::make($request->all(), [
        'name' => 'required|unique:galleries,name,'.$gallery->id,
      ]);
      if($validator->fails()) return response()->json($validator->errors(), 422);
  
      // then update
      $gallery->name = $request->name;
      $gallery->subdomain_id = $request->subdomain_id;
      $gallery->color = $request->color == '#000000' || $request->color == '#ffffff' ? null : $request->color;
      $gallery->second_color = $request->second_color == '#000000' || $request->second_color == '#ffffff' ? null : $request->second_color;
      $gallery->update();
  
      $response = [
        'data' => $gallery,
        'message' => 'Gallery "' . $request->name . '" has been successfully updated.'
      ];
  
      return response()->json($response);
    }

    public function sync ($token, Request $request) {
      // check user access first
      $check = checkUserGalleryAccess($token);
      if ($check == 0) return response()->json('unauthorized', 403);

      if ($request->gallery['parent_id']) {
        // update this gallery
        $gallery = Gallery::where('_token', $token)->first();
        $gallery->parent_id = $request->gallery['parent_id'];
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

      // sync tags
      $gallery->syncTags([]); // reset first
      foreach ($request->tags as $tag) $gallery->attachTag($tag['name']['en'], $tag['type']);

      $_gallery = $this->generalGet($gallery->_token);

      $response = [
        '_gallery' => $_gallery,
        'data' => $gallery,
        'message' => '"' . $gallery->name . '" gallery has been successfully updated.'
      ];
      return response()->json($response, 200);
    }

    public function delete ($token) {
      // check user access first
      $check = checkUserGalleryAccess($token);
      if ($check == 0) return response()->json('unauthorized', 403);

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

      // get gallery album ids from map
      $gallery_album_ids = GAMap::where('gallery_id', $gallery->id)->pluck('album_id');

      // get final album ids based on gallery
      $album_ids = GAMap::whereIn('album_id', $gallery_album_ids)
                    ->where(function($qry) use ($galleryArr) {
                      if(count($galleryArr)) {
                        $qry->whereIn('gallery_id', $galleryArr);
                      }
                    })->pluck('album_id');

      // get albums
      $albums = Album::whereIn('id', $album_ids)
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with('gallery');
                }])
                ->with('country')
                ->with('tags')
                ->with('photos')
                ->get()->values();

      // get gallery photo ids from map based on main gallery
      $gallery_photo_ids = GPMap::where('gallery_id', $gallery->id)->pluck('photo_id');

      // get final gallery photo ids based on gallery from maps
      $photo_ids = GPMap::whereIn('photo_id', $gallery_photo_ids)
                    ->where(function($qry) use ($galleryArr) {
                      if(count($galleryArr)) {
                        $qry->whereIn('gallery_id', $galleryArr);
                      }
                    })
                    ->pluck('photo_id');
      $photos = Photo::select('id',
                              'user_id',
                              'album_id',
                              'file_name',
                              'file_size',
                              'file_type',
                              'file_extension',
                              'event_date',
                              \DB::raw("DATE_FORMAT(event_date, '%M %d, %Y') as event_date2"),
                              'country_id',
                              '_token',
                              'created_at',
                              'updated_at')
                ->whereIn('id', $photo_ids)
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with('gallery');
                }])
                ->with('country')
                ->with('album')
                ->with('tags')
                ->get()->values();

      // append method, albums & photos count
      $custom = collect(['method' => 'GET', 'album_count' => $albums->count(), 'photo_count' => $photos->count()]);

      $data = $albums->toBase()->merge($photos);
      $collection = (new Collection($data))->sortByDate('created_at', true)->paginate(5);

      // fix for data returning object on other pages
      $items = $collection->items();
      $decodeFix = json_decode($collection->toJson());
      $decodeFix->data = array_values($items);

      $decodeFix = $custom->merge($decodeFix);

      return response()->json($decodeFix, 200);
    }

    public function filteredAlbums ($token, Request $request) {
      $galleryArr = [];
      $regionArr = [];
      $countryArr = [];
      $tagArr = [];

      // push filtered galleries
      if ($request->filter['galleries']) {
        foreach ($request->filter['galleries'] as $fgallery) {
          array_push($galleryArr, $fgallery['id']);
        }
      }

      // push filtered regions
      if ($request->filter['regions']) {
        foreach ($request->filter['regions'] as $fregion) {
          array_push($regionArr, $fregion['id']);
        }
      }
      // get region countries and push into $countryArr
      $regions = Region::whereIn('id', $regionArr)->with('countries')->get();
      foreach ($regions as $region) {
        foreach ($region->countries as $country) {
          array_push($countryArr, $country['id']);
        }
      }

      // push filtered countries
      if ($request->filter['countries']) {
        foreach ($request->filter['countries'] as $fcountry) {
          array_push($countryArr, $fcountry['id']);
        }
      }

      // push filtered tags
      if ($request->filter['tags']) {
        foreach ($request->filter['tags'] as $ftag) {
          array_push($tagArr, $ftag['name']['en']);
        }
      }

      // date range filter
      $dateFrom = $request->filter['date_range']['from'];
      $dateTo = $request->filter['date_range']['to'] ? $request->filter['date_range']['to'] : $request->filter['date_range']['from'];

      // get main gallery
      $gallery = Gallery::where('_token', $token)->first();

      // get gallery album ids from map based on main gallery
      $gallery_album_ids = GAMap::where('gallery_id', $gallery->id)->pluck('album_id');

      // get tagged album ids
      if (count($tagArr)) {
        $tagged_album_ids = Album::withAllTagsOfAnyType($tagArr)
                            ->where(function($qry) use ($tagArr, $gallery_album_ids) {
                              if(count($tagArr)) {
                                $qry->whereIn('id', $gallery_album_ids);
                              }
                            })
                            ->pluck('id');
      } else $tagged_album_ids = $gallery_album_ids;

      // get final gallery album ids based on gallery from maps
      $album_ids = GAMap::whereIn('album_id', $tagged_album_ids)
                    ->where(function($qry) use ($galleryArr) {
                      if(count($galleryArr)) {
                        $qry->whereIn('gallery_id', $galleryArr);
                      }
                    })
                    ->pluck('album_id');

      // get albums
      $albums = Album::whereIn('id', $album_ids)
                ->where(function ($qry) use ($request, $dateFrom, $dateTo) {
                  // date filter
                  if ($request->filter['year']) {
                    $qry->whereYear('date_from', $request->filter['year'])
                        ->orWhereYear('date_to', $request->filter['year']);
                  } else if(count($request->filter['dates'])) {
                    // $qry->whereIn(\DB::raw("DATE(event_date)"), $request->filter['dates']);
                    // foreach ($request->filter['dates'] as $date) {
                    //   $qry->where('date_from', '<=', $date)
                    //       ->where('date_to', '>=', $date);
                    // }
                    foreach ($request->filter['dates'] as $date) {
                      $qry->orWhere(function ($qry) use ($date) {
                        $qry->where('date_from', '<=', $date)
                            ->where('date_to', '>=', $date);
                      });
                    }
                  } else if ($dateFrom) {
                    $dateFrom = Carbon::parse($dateFrom)->format('Y-m-d');
                    $dateTo = Carbon::parse($dateTo)->addDay()->format('Y-m-d');

                    $qry->whereBetween('date_from', [$dateFrom, $dateTo])
                        ->orWhereBetween('date_to', [$dateFrom, $dateTo]);

                    // $qry->whereBetween('event_date', [$dateFrom, $dateTo])->get();
                  }

                  // search filter
                  if ($request->filter['search']) {
                    $qry->where('venue', 'like', '%'.$request->filter['search'].'%')
                        ->orWhere('title', 'like', '%'.$request->filter['search'].'%');
                  }
                })
                ->where(function ($qry) use ($countryArr) {
                  if(count($countryArr)) {
                    $qry->whereIn('country_id', $countryArr);
                  }
                })
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with('gallery');
                }])
                ->with('country')
                ->with('tags')
                ->with('photos')
                ->get();

      // get gallery photo ids from map based on main gallery
      $gallery_photo_ids = GPMap::where('gallery_id', $gallery->id)->pluck('photo_id');

      // get tagged photo ids
      if (count($tagArr)) {
        $tagged_photo_ids = Photo::withAllTagsOfAnyType($tagArr)
                          ->where(function($qry) use ($tagArr, $gallery_photo_ids) {
                            if(count($tagArr)) {
                              $qry->whereIn('id', $gallery_photo_ids);
                            }
                          })
                          ->pluck('id');
      } else $tagged_photo_ids = $gallery_photo_ids;

      // get final gallery photo ids based on gallery from maps
      $photo_ids = GPMap::whereIn('photo_id', $tagged_photo_ids)
                    ->where(function($qry) use ($galleryArr) {
                      if(count($galleryArr)) {
                        $qry->whereIn('gallery_id', $galleryArr);
                      }
                    })
                    ->pluck('photo_id');
      $photos = Photo::select('id',
                              'user_id',
                              'album_id',
                              'file_name',
                              'file_size',
                              'file_type',
                              'file_extension',
                              'event_date',
                              \DB::raw("DATE_FORMAT(event_date, '%M %d, %Y') as event_date2"),
                              'country_id',
                              '_token',
                              'created_at',
                              'updated_at')
                ->whereIn('id', $photo_ids)
                ->with(['gallerymaps' => function ($qry) {
                  $qry->with('gallery');
                }])
                ->where(function ($qry) use ($request, $dateFrom, $dateTo) {
                  // date filter
                  if ($request->filter['year']) {
                    $qry->whereYear('event_date', $request->filter['year']);
                  } else if(count($request->filter['dates'])) {
                      $qry->whereIn(\DB::raw("DATE(event_date)"), $request->filter['dates']);
                  } else if ($dateFrom) {
                    $dateFrom = Carbon::parse($dateFrom)->format('Y-m-d');
                    $dateTo = Carbon::parse($dateTo)->format('Y-m-d');

                    $qry->whereBetween('event_date', [$dateFrom, $dateTo])->get();
                  }

                  // search filter
                  if ($request->filter['search']) {
                    $qry->where('file_name', 'like', '%'.$request->filter['search'].'%');
                  }
                })
                ->where(function ($qry) use ($countryArr) {
                  if(count($countryArr)) {
                    $qry->whereIn('country_id', $countryArr);
                  }
                })
                ->with('country')
                ->with('album')
                ->with('tags')
                ->get();

      // append method, albums & photos count
      $custom = collect(['method' => 'POST', 'album_count' => $albums->count(), 'photo_count' => $photos->count()]);

      $data = $albums->toBase()->merge($photos);

      if ($request->filter['sort'] == 'asc') $collection = (new Collection($data))->sortByDate('created_at', true)->paginate(5);
      else $collection = (new Collection($data))->sortByDate('created_at', false)->paginate(5);

      // fix for data returning object on other pages
      $items = $collection->items();
      $decodeFix = json_decode($collection->toJson());
      $decodeFix->data = array_values($items);

      $decodeFix = $custom->merge($decodeFix);

      return response()->json($decodeFix, 200);
    }
}
