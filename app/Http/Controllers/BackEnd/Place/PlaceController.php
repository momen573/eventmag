<?php

namespace App\Http\Controllers\BackEnd\Place;

use App\Http\Requests\Place\StorePlaceRequest;
use App\Http\Requests\Place\UpdatePlaceRequest;
use App\Models\Language;
use App\Models\Place;
use App\Traits\UploadMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PlaceController extends Controller
{
  use UploadMedia;

  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['places'] = Place::where('language_id', $language->id)
      ->orderByDesc('id')
      ->get();

    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.place.index', $information);
  }

  public function store(StorePlaceRequest $request)
  {
    if ($request->hasFile('images_media')) {
      $images = $request->file('images_media');

      $request->merge([
        'images' => $this->multipleUpload($images, 'places/images'),
      ]);
    }

    if ($request->hasFile('thumbnail_media')) {
      $thumbnail = $request->file('thumbnail_media');

      $request->merge([
        'thumbnail' => $this->singleUpload($thumbnail, 'places/thumbnails'),
      ]);
    }

    Place::query()
      ->create(filterNullData($request->validationData()));

    Session::flash('success', 'New Place added successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(Place $place, UpdatePlaceRequest $request)
  {
    $current_images = json_decode($place->getRawOriginal('images'));
    $current_thumbnail = json_decode($place->getRawOriginal('thumbnail'));

    if ($request->hasFile('images_media')) {
      $images = $request->file('images_media');

      foreach ($current_images as $current_image) {
        $this->deleteFile($current_image);
      }

      $images_media_array = $this->multipleUpload($images, 'places/images');

      $request->merge([
        'images' => $images_media_array,
      ]);
    }

    if ($request->hasFile('thumbnail_media')) {
      $thumbnail = $request->file('thumbnail_media');

      if (!empty($current_thumbnail)) {
        $this->deleteFile($current_thumbnail);
      }

      $request->merge([
        'thumbnail' => $this->singleUpload($thumbnail, 'places/thumbnails'),
      ]);
    }

    $place->update(filterNullData($request->validationData()));

    Session::flash('success', 'Place updated successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy(Place $place)
  {
    $current_images = json_decode($place->getRawOriginal('images'));
    $current_thumbnail = json_decode($place->getRawOriginal('thumbnail'));

    foreach ($current_images as $current_image) {
      $this->deleteFile($current_image);
    }

    $this->deleteFile($current_thumbnail);

    $place->delete();

    return redirect()->back()->with('success', 'Place deleted successfully');
  }

  public function destroy_groups(Request $request)
  {
    $ids = $request->input('ids');

    foreach ($ids as $id) {
      $place = Place::query()
        ->findOrFail($id);

      $current_images = json_decode($place->getRawOriginal('images'));
      $current_thumbnail = json_decode($place->getRawOriginal('thumbnail'));

      foreach ($current_images as $current_image) {
        $this->deleteFile($current_image);
      }

      $this->deleteFile($current_thumbnail);

      $place->delete();
    }

    Session::flash('success', 'Places deleted successfully');

    return response()->json(['status' => 'success'], 200);
  }
}
