<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Gallery extends Model
{
    use HasFactory, HasTags;

    public function albummaps () {
      return $this->hasMany(GalleryAlbumMap::class);
    }
}
