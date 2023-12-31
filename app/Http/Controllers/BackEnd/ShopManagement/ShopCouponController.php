<?php

namespace App\Http\Controllers\BackEnd\ShopManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopManagement\ShopCouponRequest;
use App\Http\Requests\ShopManagement\UpdateShopCounponRequest;
use App\Models\ShopManagement\ShopCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ShopCouponController extends Controller
{
  public function index()
  {
    $data['collection'] = ShopCoupon::orderBy('id', 'DESC')->get();
    return view('backend.product.coupon.index', $data);
  }
  //store
  public function store(ShopCouponRequest $request)
  {

    ShopCoupon::create($request->all());
    Session::flash('success', 'Coupon added successfully!');
    return response()->json(['status' => 'success'], 200);
  }
  //update
  public function update(UpdateShopCounponRequest $request)
  {

    $in = $request->all();
    $update = ShopCoupon::where('id', $request->id)->first();
    $update->update($in);
    Session::flash('success', 'Coupon Update successfully!');
    return response()->json(['status' => 'success'], 200);
  }
  //destroy
  public function destroy(Request $request)
  {
    $delete = ShopCoupon::where('id', $request->id)->first();
    $delete->delete();
    Session::flash('warning', 'Coupon Deleted successfully!');
    return back();
  }
  //bulk_destroy
  public function bulk_destroy(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $delete = ShopCoupon::where('id', $id)->first();
      $delete->delete();
    }
    Session::flash('warning', 'Coupons are deleted successfully!');
    return response()->json(['status' => 'success'], 200);
  }
}
