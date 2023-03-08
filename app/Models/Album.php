<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Album extends Model
{
    use HasFactory;

    public function getEventDateAttribute($value) {
        $carbon = new Carbon($value);
        return $carbon->format('F d, Y');
    }

    public function country () {
      return $this->belongsTo(Country::class);
    }

    public function gallerymaps () {
      return $this->hasMany(GalleryAlbumMap::class);
    }
}
