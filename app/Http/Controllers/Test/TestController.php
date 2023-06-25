<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Event\Booking;
use App\Models\Event\Ticket;

class TestController extends Controller
{
  public function __invoke()
  {
    $event_id = 108;
    $names = [];
//    $name = 'STAR 5';
//    $sales_start = 11;
//    $sales_end = 20;

    $variations = Ticket::query()
      ->where('event_id', '=', $event_id)
//      ->whereJsonContains('variations', [
//        'name' => $name,
//      ])
      ->get()
      ->pluck('variations')
      ->flatten(1);

    foreach ($variations as $variation) {
      $names[] = $variation['name'];
    }

    foreach ($names as $name) {
      $sales_count = Booking::query()
        ->where('event_id', '=', $event_id)
        ->where('paymentStatus', '=', 'completed')
        ->whereJsonContains('variation', ['name' => $name])
        ->get()
        ->sum(function ($booking) {
          $variation = json_decode($booking->variation, true);
          return $variation[0]['qty'];
        });

      $values[] = [
        'name' => $name,
        'sales_count' => $sales_count
      ];
    }

//    return $values;
//    dd($values);


    foreach ($values as $value) {
      $name = $value['name'];
      $sales_count = $value['sales_count'];

      $price = collect($variations)
        ->where('name', $name)
        ->pluck('prices')
        ->flatten(1)
        ->filter(function ($price) use ($sales_count) {
          if ($sales_count==0){
            return true;
          }else{
            return $price['sales_start'] <= $sales_count && $price['sales_end'] >= $sales_count;
          }
        })
        ->pluck('price')
        ->first();

      $data[] = [
        'name' => $name,
        'price' => $price
      ];
    }

    return array_values(array_filter($data, function ($variation) {
      return $variation['price'] !== null;
    }));
  }
}
