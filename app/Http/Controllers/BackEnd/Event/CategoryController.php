<?php

namespace App\Http\Controllers\Backend\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventCategoryRequest;
use App\Http\Requests\Event\UpdateCategoryRequest;
use Illuminate\Http\Request;
use App\Models\Event\EventCategory;
use App\Models\Language;
use App\Models\Event\EventContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['categories'] = EventCategory::where('language_id', $language->id)
      ->orderByDesc('id')
      ->get();

    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.event.category.index', $information);
  }

  public function store(EventCategoryRequest $request)
  {
    $file = $request->file('image');
    $extension = $file->getClientOriginalExtension();
    $directory = public_path('assets/admin/img/event-category/');
    $fileName = uniqid() . '.' . $extension;
    @mkdir($directory, 0775, true);
    $file->move($directory, $fileName);


    $ins = $request->all();
    $ins['slug'] = createSlug($request->name);
    $ins['image'] = $fileName;
    EventCategory::create($ins);
    Session::flash('success', 'New Event category added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function updateFeatured(Request $request, $id)
  {
    $category = EventCategory::find($id);

    if ($request['is_featured'] == 'yes') {
      $category->update(['is_featured' => 'yes']);

      Session::flash('success', 'Category featured successfully!');
    } else {
      $category->update(['is_featured' => 'no']);

      Session::flash('success', 'Category unfeatured successfully!');
    }

    return redirect()->back();
  }

  public function update(UpdateCategoryRequest $request)
  {
    $ins = $request->all();
    $ins['slug'] = createSlug($request->name);

    $file = $request->file('image');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/admin/img/event-category/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);

      $find = EventCategory::where('id', $request->id)->first();
      @unlink(public_path('assets/admin/img/event-category/') . $find->image);
      $ins['image'] = $fileName;
    }
    EventCategory::find($request->id)->update($ins);

    Session::flash('success', 'Event category updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $category = EventCategory::where('id', $id)->first();
    @unlink(public_path('assets/admin/img/event-category/') . $category->image);

    //events
    $event_contents = EventContent::where('event_category_id', $category->id)->get();
    foreach ($event_contents as $event_content) {
      $event_content->delete();
    }

    $category->delete();
    return redirect()->back()->with('success', 'Event category deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $category = EventCategory::where('id', $id)->first();
      @unlink(public_path('assets/admin/img/event-category/') . $category->image);

      //events
      $event_contents = EventContent::where('event_category_id', $category->id)->get();
      foreach ($event_contents as $event_content) {
        $event_content->delete();
      }

      $category->delete();
    }
    Session::flash('success', 'Event categories deleted successfully!');
    return response()->json(['status' => 'success'], 200);
  }
}
