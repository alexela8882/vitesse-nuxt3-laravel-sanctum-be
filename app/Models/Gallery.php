<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Gallery extends Model
{
    use HasFactory, HasTags, Searchable;

    public function searchableAs () {
      return 'galleries_index';
    }

    public function toSearchableArray () {
      $array = $this->toArray();
      return $array;
    }

    public function albummaps () {
      return $this->hasMany(GalleryAlbumMap::class);
    }

    public function subdomain () {
      return $this->belongsTo(Subdomain::class);
    }

    public function subgalleries () {
      return $this->hasMany(Gallery::class, 'parent_id', 'id');
    }

    public function parent () {
      return $this->belongsTo(Gallery::class, 'parent_id', 'id');
    }
}
