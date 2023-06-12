<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Event;
use App\Models\Event\EventCategory;
use App\Models\Event\EventContent;
use App\Models\Organizer;
use App\Models\OrganizerInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;

class OrganizerController extends Controller
{
  //show
  public function index(Request $request)
  {
    $language = $this->getLanguage();

    $organizer_name = $username = $location = null;

    $organizerIds = [];
    if ($request->filled('organizer')) {
      $organizer_name = $request->organizer;

      $organizer_infos = OrganizerInfo::where('name', 'like', '%' . $organizer_name . '%')
        ->where('language_id', $language->id)
        ->get();
      foreach ($organizer_infos as $info) {
        if (!in_array($info->organizer_id, $organizerIds)) {
          array_push($organizerIds, $info->organizer_id);
        }
      }
    }
    if ($request->filled('username')) {
      $username = $request->username;
    }
    $locationIds = [];
    if ($request->filled('location')) {
      $location = $request->location;
      $organizer_infos = OrganizerInfo::where('city', 'like', '%' . $location . '%')
        ->orWhere('state', 'like', '%' . $location . '%')
        ->orWhere('country', 'like', '%' . $location . '%')
        ->orWhere('address', 'like', '%' . $location . '%')
        ->where('language_id', $language->id)
        ->get();
      foreach ($organizer_infos as $info) {
        if (!in_array($info->organizer_id, $locationIds)) {
          array_push($locationIds, $info->organizer_id);
        }
      }
    }

    $collection = Organizer::with(['organizer_info' => function ($query) use ($language) {
      return $query->where('language_id', $language->id);
    }])->when($username, function ($query) use ($username) {
      return $query->where('username', 'like', '%' . $username . '%');
    })
      ->when($location, function ($query) use ($locationIds) {
        return $query->whereIn('id', $locationIds);
      })
      ->when($organizer_name, function ($query) use ($organizerIds) {
        return $query->whereIn('id', $organizerIds);
      })
      ->paginate(20);

    return view('frontend.organizer.index', compact('collection'));
  }

  public function details(Request $request, $id, $name)
  {
    try {
      $language = $this->getLanguage();
      $information = [];
      if (filled($request->admin)) {
        $admin = Admin::first();
        $information['organizer'] = $admin;
        $information['admin'] = true;

        $events = Event::with(['tickets', 'information' => function ($query) use ($language) {
          return $query->where('language_id', $language->id);
        }])->where('organizer_id', NULL)->get();
        $information['organizer_info'] = [];
      } else {
        $organizer = Organizer::where('id', $id)->first();

        $information['organizer_info'] = OrganizerInfo::where('organizer_id', $id)->where('language_id', $language->id)->first();

        $information['organizer'] = $organizer;
        $information['admin'] = false;

        $events = Event::with(['tickets', 'information' => function ($query) use ($language) {
          return $query->where('language_id', $language->id);
        }])->where('organizer_id', $organizer->id)->get();
      }

      $categories = EventCategory::where('status', 1)
        ->where('language_id', $language->id)
        ->orderBy('serial_number', 'asc')->get();
      $information['categories'] = $categories;


      $information['events'] = $events;
      return view('frontend.organizer.details', $information); //code...
    } catch (\Exception $th) {
      return view('errors.404');
    }
  }


  public function sendMail(Request $request)
  {

    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'email_address')
      ->first();

    $rules = [
      'name' => 'required',
      'email' => 'required|email:rfc,dns',
      'subject' => 'required',
      'message' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }
    $organizer = Organizer::where('id', $request->id)->first();


    $name = $request->name;
    $subject = $request->subject;

    $message = '<p>Message : ' . $request->message . '</p> <p><strong>Enquirer Name: </strong>' . $name . '<br/><strong>Enquirer Mail: </strong>' . $request->email . '</p>';

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    if ($info->smtp_status == 1) {

      $mail->isSMTP();
      $mail->Host       = $info->smtp_host;
      $mail->SMTPAuth   = true;
      $mail->Username   = $info->smtp_username;
      $mail->Password   = $info->smtp_password;

      if ($info->encryption == 'TLS') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      }

      $mail->Port       = $info->smtp_port;
    }

    try {
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($organizer->email);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $message;

      $mail->send();

      $notification = array('message' => 'Your contact request send to organizer successfully..!', 'alert-type' => 'info');
    } catch (\Exception $e) {
      $notification = array('message' => 'Something went wrong', 'alert-type' => 'error');
    }

    return redirect()->back()->with($notification);
  }
}
