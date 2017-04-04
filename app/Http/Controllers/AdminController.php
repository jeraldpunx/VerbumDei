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

    public function newkyc()
    {
        return view('kyc.new');
    }

    public function postNewKYC()
    {
        $inputs = Input::all();
        $beneficiaries = [];
        foreach ($inputs['name'] as $key => $value) {
                array_push($beneficiaries, [
                    'name'=>$value, 
                    'birthDate'=>$inputs['birthDate'][$key],
                    'relationship'=>$inputs['relationship'][$key]
                ]);
            }

        unset($inputs['name']);
        unset($inputs['birthDate']);
        unset($inputs['relationship']);
        unset($inputs['_token']);
        $inputs['beneficiaries'] = json_encode($beneficiaries);

        $user = Curl::to('http://52.74.115.167:703/index.php')
            ->withData(array_merge([ 'mtmaccess_api' => 'true',
                          'transaction' => '20001' ], $inputs))
            ->asJson()
            ->get();

        return redirect()->back()->withInput()->with('response',$user);

        // if($user->success) {
        //     return view('kyc.list', ['user'=>$user]);
        // }
        // else
        //     return "Unauthorized Page!";
    }


    public function printUserId($profileId)
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

    public function printUserQr($profileId)
    {
        $users = Curl::to('http://52.74.115.167:703/index.php')
            ->withData([ 'mtmaccess_api' => 'true',
                          'transaction' => '20007',
                          'profileIds'   => [$profileId] ])
            ->asJson()
            ->get();
            
        if($users->success) {
            $data = [];
            if(count($users->result) == 1) $users->result = [$users->result];
            foreach ($users->result as $user) {
                array_push($data, [
                        'name'=>$user->lastname . ', ' . $user->firstname,
                        'qrcode'=>DNS2D::getBarcodePNG($user->vdmfa_id, "QRCODE")
                    ]);
            }

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('layouts.qr', ['users'=>$data])->setPaper('a4', 'landscape');
            return $pdf->stream();
            // return view('layouts.qr', ['users'=>$data]);
        } else
            return "Unauthorized Page!";
    }

    public function printAllUserQr()
    {
        $users = Curl::to('http://52.74.115.167:703/index.php')
            ->withData([ 'mtmaccess_api' => 'true',
                          'transaction' => '20007',
                          'profileIds'   => Input::get('id') ])
            ->asJson()
            ->get();

        if($users->success) {
            $data = [];
            if(count($users->result) == 1) $users->result = [$users->result];
            foreach ($users->result as $user) {
                array_push($data, [
                        'name'=>$user->lastname . ', ' . $user->firstname,
                        'qrcode'=>DNS2D::getBarcodePNG($user->vdmfa_id, "QRCODE")
                    ]);
            }

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('layouts.qr', ['users'=>$data])->setPaper('a4', 'landscape');
            return $pdf->stream();
            // return view('layouts.qr', ['users'=>$data]);
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







