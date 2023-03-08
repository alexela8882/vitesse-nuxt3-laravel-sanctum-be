<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbumMap extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function gallery () {
      return $this->belongsTo(Gallery::class);
    }
}
