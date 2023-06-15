<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Place extends Model
{
  protected $table = 'places';
  protected $guarded =
    [
      'id',
      'created_at',
      'updated_at',
    ];

  protected $fillable =
    [
      'title',
      'images',
      'thumbnail',
      'description',
      'x_location',
      'y_location',
      'language_id',
    ];

  protected $casts =
    [
      'images' => 'array',
      'thumbnail' => 'array',
    ];

  public function language(): BelongsTo
  {
    return $this->belongsTo(Language::class, 'language_id');
  }

  public function getThumbnailAttribute(): ?string
  {
    $thumbnail = $this->getAttributes()['thumbnail'] ?? null;
    if ($thumbnail) {
      return '../storage' . '/' . json_decode($thumbnail);
    }

    return null;
  }

  public function getImagesAttribute()
  {
    $images = $this->getAttributes()['images'] ?? null;
    if ($images) {
      $images = json_decode($images);
      foreach ($images as $key => $image) {
        $images[$key] = '../storage' . '/' . $image;
      }

      return $images;
    }

    return [];
  }
}
