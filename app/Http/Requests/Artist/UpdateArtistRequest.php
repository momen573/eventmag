<?php

namespace App\Http\Requests\Artist;

use App\Models\Artist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArtistRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth('admin')->check() == true;
  }

  public function rules(): array
  {
    return [
      'full_name' => ['required', 'string', 'max:255'],
      'image_media' => ['nullable', 'mimes:jpg,png,jpeg', 'max:2048'],
      'status' => ['required', Rule::in(Artist::$statuses)],
      'description' => ['nullable', 'max:50000'],
    ];
  }
}
