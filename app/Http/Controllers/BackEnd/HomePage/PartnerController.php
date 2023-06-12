<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use Illuminate\Http\Request;
use App\Http\Requests\PartnerRequest;
use App\Http\Requests\PartnerUpdateRequest;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\HomePage\Partner;
use App\Models\HomePage\PartnerSection;
use Illuminate\Support\Facades\Session;

class PartnerController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = PartnerSection::where('language_id', $language->id)->firstOrFail();

    $information['partners'] = Partner::orderByDesc('id')->get();

    $information['langs'] = Language::all();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.home-page.partner.index', $information);
  }

  public function update(Request $request)
  {
    $datas = [];
    $rules = [];

    $rules['title'] = 'required';

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $language = Language::where('code', $request->language_code)->first();
    $data = PartnerSection::where('language_id', $language->id)->first();

    $datas['language_id'] = $language->id;
    $datas['title'] = $request->title;
    $datas['text'] = $request->text;
    if (empty($data)) {
      PartnerSection::create($datas);
    } else {
      $data->update($datas);
    }
    Session::flash('success', 'Update Partner Section successfully!');

    return redirect()->back();
  }
  public function store(PartnerRequest $request)
  {
    $imageName = UploadFile::store(public_path('assets/admin/img/partner/'), $request->file('image'));

    Partner::create($request->except('image') + [
      'image' => $imageName,
      'url' => $request->url,
      'serial_number' => $request->serial_number,
    ]);

    Session::flash('success', 'New Partner added successfully!');

    return response()->json(['status' => 'success'], 200);
  }
  public function update_partner(PartnerUpdateRequest $request)
  {
    $partner = Partner::find($request->id);
    $in = $request->all();

    if ($request->hasFile('image')) {
      $imageName = UploadFile::update(public_path('assets/admin/img/partner/'), $request->file('image'), $partner->logo);
      $in['image'] = $imageName;
    }
    $partner->update($in);

    Session::flash('success', 'Partner updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }
  //delete
  public function delete($id)
  {
    $partner = Partner::find($id);

    // delete client picture
    @unlink(public_path('assets/admin/img/partner/') . $partner->image);

    $partner->delete();

    return redirect()->back()->with('success', 'Partner deleted successfully!');
  }
  public function bulk_delete(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $partner = Partner::find($id);

      // delete client picture
      @unlink(public_path('assets/admin/img/partner/') . $partner->image);

      $partner->delete();
    }

    Session::flash('success', 'Partner deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
