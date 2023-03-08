<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    public function country () {
      return $this->belongsTo(Country::class);
    }

    public function gallerymaps () {
      return $this->hasMany(GalleryAlbumMap::class);
    }
}
