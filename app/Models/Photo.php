<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

use Carbon\Carbon;

class Photo extends Model
{
    use HasFactory, HasTags;

    public function getEventDateAttribute($value) {
      if ($value) {
        $carbon = new Carbon($value);
        return $carbon->format('Y-m-d');
      } else return $this->value;
    }

    public function gallerymaps () {
      return $this->hasMany(GalleryPhotoMap::class);
    }

    public function country () {
      return $this->belongsTo(Country::class);
    }

    public function album () {
      return $this->belongsTo(Album::class);
    }
}
