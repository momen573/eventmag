<!DOCTYPE html>
<html>

<head lang="{{ $currentLanguageInfo->code }}" @if ($currentLanguageInfo->direction == 1) dir="rtl" @endif>
  {{-- required meta tags --}}
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  {{-- title --}}
  <title>{{ 'Invoice | ' . config('app.name') }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/' . $websiteInfo->favicon) }}">

  {{-- styles --}}
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/invoice.css') }}">
  @php
    $_15px = '15px';
    $_10px = '10px';
    $_12px = '12px';
    $b_color = '565656';
    $w_47 = '47%';

  @endphp
  <style>
      body {
          font-size: {{ $_15px }};
          background-image: url({{ asset('assets/admin/img/event/background/'.$event->background)}});
          background-size: 100%;
          background-repeat: no-repeat;
          color: black;
      }

      #left {
          float: left;
          margin-left: 70px;
          width: 300px;
      }

      #left #name {
          margin-top: 300px;
      }

      #left #type {
          margin-top: 30px;
      }

      #left #price {
          margin-top: 40px;
      }

      #right {
          float: right;
          margin-left: 70px;
          color: #fff;
          width: 300px;
      }

      #right .name {
          margin-top: 180px;
      }

      #right .type {
          margin-top: 20px;
      }

      #right #qr {
          margin-top: 40px;
      }
  </style>
</head>

<body>
@php
$variations = json_decode($bookingInfo->variation, true);
@endphp

@foreach ($variations as $variation)

      @php
        $ticket = App\Models\Event\Ticket::where('id', $variation['ticket_id'])->first();

        $ticketContent = App\Models\Event\TicketContent::where([['ticket_id', $variation['ticket_id'], ['language_id', $currentLanguageInfo->id]]])->first();
      @endphp
      @endforeach
      <div id="left"><h2 id="name">{{ $bookingInfo->fname . ' ' . $bookingInfo->lname }}</h2>
        <h4 id="type">{{ $ticketContent->title}}</h4>
        <h2 id="price">$ {{ $bookingInfo->price + $bookingInfo->tax }}</h2>
      </div>
      <div id="right"><h2 class="name">{{ $bookingInfo->fname . ' ' . $bookingInfo->lname }}</h2>
        <h4 class="type">{{ $ticketContent->title }}</h4>
        <img src="{{ asset('assets/admin/qrcodes/' . $bookingInfo->booking_id . '.svg') }}" alt="">
      </div>
</body>
</html>
