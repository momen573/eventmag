<?php

namespace App\Http\Controllers\BackEnd\Artist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Artist\StoreArtistRequest;
use App\Http\Requests\Artist\UpdateArtistRequest;
use App\Models\Artist;
use App\Traits\UploadMedia;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
  use UploadMedia;

  public function index(Request $request)
  {

  }

  public function store(StoreArtistRequest $request)
  {

  }

  public function update(Artist $artist, UpdateArtistRequest $request)
  {

  }

  public function destroy(Artist $artist)
  {

  }

  public function destroy_groups(Request $request)
  {

  }
}
