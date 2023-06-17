<?php

namespace App\Http\Requests\Place;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlaceRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth('admin')->check() == true;
  }

  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'max:255'],
      'images_media' => ['nullable', 'array', 'distinct', 'max:10'],
      'images_media.*' => ['required', 'mimes:jpg,png,jpeg', 'max:2048'],
      'thumbnail_media' => ['nullable', 'mimes:jpg,png,jpeg', 'max:2048'],
      'description' => ['nullable', 'max:50000'],
      'x_location' => ['nullable', 'string', 'max:255'],
      'y_location' => ['nullable', 'string', 'max:255'],
    ];
  }
}
