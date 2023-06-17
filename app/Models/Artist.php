<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Artist extends Model
{
  protected $table = 'artists';

  protected $guarded =
    [
      'id',
      'created_at',
      'updated_at',
    ];

  protected $fillable =
    [
      'full_name',
      'image',
      'status',
      'description',
      'language_id',
    ];

  const INACTIVE = 'inactive';
  const ACTIVE = 'active';
  static array $statuses =
    [
      self::ACTIVE,
      self::INACTIVE,
    ];

  protected $casts =
    [
      'image' => 'array',
    ];

  public function events(): BelongsToMany
  {
    return $this->belongsToMany(Event::class, 'artist_events');
  }

  public function language(): BelongsTo
  {
    return $this->belongsTo(Language::class, 'language_id');
  }

  public function getImageAttribute(): ?string
  {
    $image = $this->getAttributes()['image'] ?? null;
    if ($image) {
      return '../storage' . '/' . json_decode($image);
    }

    return null;
  }
}
