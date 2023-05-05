<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

use Carbon\Carbon;

class Album extends Model
{
    use HasFactory, HasTags;

    public function getEventDateAttribute($value) {
      $carbon = new Carbon($value);
      return $carbon->format('F d, Y');
    }

    public function getDateFromAttribute($value) {
      $carbon = new Carbon($value);
      return $carbon->format('F d, Y');
    }

    public function getDateToAttribute($value) {
      $carbon = new Carbon($value);
      return $carbon->format('F d, Y');
    }

    public function country () {
      return $this->belongsTo(Country::class);
    }

    public function gallerymaps () {
      return $this->hasMany(GalleryAlbumMap::class);
    }

    public function photos () {
      return $this->hasMany(Photo::class);
    }
}
