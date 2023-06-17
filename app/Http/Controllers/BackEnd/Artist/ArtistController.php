<?php

namespace App\Http\Controllers\BackEnd\Artist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Artist\StoreArtistRequest;
use App\Http\Requests\Artist\UpdateArtistRequest;
use App\Models\Artist;
use App\Models\Language;
use App\Traits\UploadMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ArtistController extends Controller
{
  use UploadMedia;

  public function index(Request $request)
  {
    $language = Language::query()
      ->where('code', '=', $request->input('language'))
      ->firstOrFail();

    $information['language'] = $language;

    $information['artists'] = Artist::query()
      ->where('language_id', '=', $language->id)
      ->orderByDesc('id')
      ->get();

    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')
      ->select('theme_version')
      ->first();

    return view('backend.artist.index', $information);
  }

  public function store(StoreArtistRequest $request)
  {
    if ($request->hasFile('image_media')) {
      $request->merge([
        'image' => $this->singleUpload($request->file('image_media'),
          'artists/images'),
      ]);
    }

    Artist::query()
      ->create(filterNullData($request->validationData()));

    Session::flash('success', 'New Artist added successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(Artist $artist, UpdateArtistRequest $request)
  {
    $current_image = json_decode($artist->getRawOriginal('image'));

    if ($request->hasFile('image_media')) {
      if (!empty($current_image)) {
        $this->deleteFile($current_image);
      }

      $request->merge([
        'image' => $this->singleUpload($request->file('image_media'),
          'artists/images'),
      ]);
    }

    $artist->update(filterNullData($request->validationData()));

    Session::flash('success', 'Artist updated successfully');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy(Artist $artist)
  {
    $current_image = json_decode($artist->getRawOriginal('image'));

    $this->deleteFile($current_image);

    $artist->delete();

    return redirect()->back()->with('success', 'Artist deleted successfully');
  }

  public function destroy_groups(Request $request)
  {
    $ids = $request->input('ids');

    foreach ($ids as $id) {
      $artist = Artist::query()
        ->findOrFail($id);

      $current_image = json_decode($artist->getRawOriginal('image'));

      $this->deleteFile($current_image);

      $artist->delete();
    }

    Session::flash('success', 'Artist deleted successfully');

    return response()->json(['status' => 'success'], 200);
  }
}
