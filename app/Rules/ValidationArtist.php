<?php

namespace App\Rules;

use App\Models\Artist;
use Illuminate\Contracts\Validation\Rule;

class ValidationArtist implements Rule
{
  public function passes($attribute, $value): bool
  {
    $artist = Artist::query()
      ->findOrFail($value);

    if ($artist->status == Artist::ACTIVE) {
      return true;
    } else {
      return false;
    }
  }

  public function message(): string
  {
    return 'the artist is not active';
  }
}
