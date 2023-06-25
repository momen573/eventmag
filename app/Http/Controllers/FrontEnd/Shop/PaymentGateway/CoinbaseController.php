<?php

namespace App\Http\Controllers\FrontEnd\Shop\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Shakurov\Coinbase\Facades\Coinbase;


class CoinbaseController extends Controller
{
  public function bookingProcess(Request $request)
  {

    $charge = Coinbase::createCharge([

      'name' => 'The NFTDoor Signup Fee',
      'description' => 'The NFTDoor one time Signup Fee',
      'local_price' => [
        'amount' => 0.99,
        'currency' => 'USD',
      ],
      'pricing_type' => 'fixed_price',
    ]);
//    $user = User::findOrFail($id);
//    $user['payment_code'] = $charge['data']['code'];
//    $user->save();
    return response()->json($charge);
  }

  public function notify(Request $request)
  {

  }
}
