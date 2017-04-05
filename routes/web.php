<?php
// use Ixudra\Curl\Facades\Curl;
// // use Illuminate\Http\Request;
// // ini_set('max_execution_time', 180);


Route::get('/', function () {
	if(!Session::has('username')) {
		$events = Curl::to('http://52.74.115.167:703/index.php')
		->withData([ 'mtmaccess_api' => 'true',
						'transaction' => '20030'
		 ])
		->asJson()
		->get();

		if($events->success) {
            if(count($events->result) == 1) $events->result = [$events->result];
            // if (count($events->result) == count($events->result, COUNT_RECURSIVE)) $events->result = [$events->result];
             
	        return view('welcome', ['events'=>$events->result]);
	    } else {
            return view('welcome', ['events'=>[]]);
        }
	} else {
		if(Session::get('usertype') == 'MERCHANT')
			return redirect('admin/event');
		else if(Session::get('usertype') == 'CLIENT') {
			return redirect('event');
		}
	}
})->name('home');

Route::get('login', 'BasicAuthController@getLogin');
Route::post('login', 'BasicAuthController@postLogin');
Route::get('register', 'BasicAuthController@getRegister');
Route::post('register', 'BasicAuthController@postRegister');
Route::get('logout', 'BasicAuthController@logout');

Route::group(['middleware' => 'MerchantMiddleware'], function()
{
	Route::get('admin/kyc', 'AdminController@kyc');
	Route::get('admin/kyc/new', 'AdminController@newkyc');
	Route::post('admin/kyc/new', 'AdminController@postNewKYC');
	Route::get('admin/{profileId}/printId', 'AdminController@printUserId')->name('printUserId');
	Route::get('admin/{profileId}/printQr', 'AdminController@printUserQr')->name('printUserQr');
	Route::get('admin/printAllUserQr', 'AdminController@printAllUserQr')->name('printAllUserQr');
	Route::get('admin/event', 'AdminController@events');
	Route::get('admin/event/{eventId}', 'AdminController@event');
	Route::get('admin/event/{eventId}/attendees', 'AdminController@eventAttendees')->name('viewEventAttendees');
	Route::get('admin/event/{eventId}/attendeess', 'AdminController@registerMember')->name('registerMember');
});


	Route::get('event', 'ClientController@events');
	Route::get('event/{eventId}', 'ClientController@event')->name('viewEvent');

Route::group(['middleware' => 'ClientMiddleware'], function()
{
	Route::get('profile', 'ClientController@getProfile')->name('profile');

	Route::post('profile/update/{profileId}/personal', 'ClientController@postProfilePersonal')->name('profile/update/personal');
	Route::post('profile/update/{profileId}/contact', 'ClientController@postProfileContact')->name('profile/update/contact');
	Route::post('profile/update/{profileId}/beneficiary', 'ClientController@postProfileBeneficiary')->name('profile/update/beneficiary');

	Route::post('event/{eventId}/join', 'ClientController@joinEvent')->name('joinEvent');
});
