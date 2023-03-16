<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Photo extends Model
{
    use HasFactory, HasTags;

    public function gallerymaps () {
      return $this->hasMany(GalleryPhotoMap::class);
    }
}
