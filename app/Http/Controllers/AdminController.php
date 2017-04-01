<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Session;
use Input;
use DNS2D;
use App;
ini_set('max_execution_time', 180);

class AdminController extends Controller
{
	public function kyc()
	{
        $members = Curl::to('http://52.74.115.167:703/index.php')
            ->withData([ 'mtmaccess_api' => 'true',
                          'transaction' => '20020' ])
            ->asJson()
            ->get();

        if($members->success)
            return view('kyc.list', ['members'=>$members]);
        else
            return "Unauthorized Page!";
    }


    public function print($profileId)
    {
        $user = Curl::to('http://52.74.115.167:703/index.php')
            ->withData([ 'mtmaccess_api' => 'true',
                          'transaction' => '20006',
                          'profileId'   => $profileId ])
            ->asJson()
            ->get();

        if($user->success) {
            $data = [
                'picture'=>($user->result->p_picture) ? "http://52.74.115.167:703/" . $user->result->p_picture : public_path() . "/images/temppicture.jpg",
                'firstname'=>$user->result->firstname,
                'lastname'=>$user->result->lastname,
                'qrcode'=>DNS2D::getBarcodePNG($user->result->vdmfa_id, "QRCODE")
            ];

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('layouts.badge', ['user'=>$data])->setPaper('a4', 'landscape');
            return $pdf->stream();
            // return view('layouts.badge', ['user'=>$data]);
        } else
            return "Unauthorized Page!";
    }

    public function events()
    {
    	$events = Curl::to('http://52.74.115.167:703/index.php')
	        ->withData([ 'mtmaccess_api' => 'true',
	                      'transaction' => '20030',
	                      'branchId'    => Session::get('branchId') ])
	        ->asJson()
	        ->get();

        if($events->success) {
            if(count($events->result) == 1) $events->result = [$events->result];
            // if (count($events->result) == count($events->result, COUNT_RECURSIVE)) $events->result = [$events->result];
             
	        return view('event.list', ['events'=>$events->result]);
	    } else {
            return view('event.list', ['events'=>[]]);
        }
    }

    public function event($eventId) {
    	return view('event.view');
    }

    public function eventAttendees($eventId)
    {
    	$attendees = Curl::to('http://52.74.115.167:703/index.php')
	        ->withData([ 'mtmaccess_api' => 'true',
	                      'transaction' => '24012',
	                      'iiId'    => '50' ])
	        ->asJson()
	        ->get();

	    if($attendees->success) {
	        // if (count($attendees->result) == count($attendees->result, COUNT_RECURSIVE)) $attendees->result = [$attendees->result];
	        return view('event.attendees', ['attendees'=>$attendees->result]);
	    }
    }

    
}







