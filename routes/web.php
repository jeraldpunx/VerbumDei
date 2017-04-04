<?php
// use Ixudra\Curl\Facades\Curl;
// // use Illuminate\Http\Request;
// // ini_set('max_execution_time', 180);


Route::get('/', function () {
	if(!Session::has('username'))
	    return view('welcome');
	else {
		if(Session::get('usertype') == 'MERCHANT')
			return redirect('admin/event');
		else if(Session::get('usertype') == 'CLIENT') {
			return redirect('event');
		}
	}
});

Route::get('login', 'BasicAuthController@getLogin');
Route::post('login', 'BasicAuthController@postLogin');
Route::get('register', 'BasicAuthController@getRegister');
Route::post('register', 'BasicAuthController@postRegister');
Route::get('logout', 'BasicAuthController@logout');

Route::get('admin/kyc', 'AdminController@kyc');
Route::get('admin/kyc/new', 'AdminController@newkyc');
Route::post('admin/kyc/new', 'AdminController@postNewKYC');
Route::get('admin/{profileId}/printId', 'AdminController@printUserId')->name('printUserId');
Route::get('admin/{profileId}/printQr', 'AdminController@printUserQr')->name('printUserQr');
Route::get('admin/printAllUserQr', 'AdminController@printAllUserQr')->name('printAllUserQr');
Route::get('admin/event', 'AdminController@events');
Route::get('admin/event/{eventId}', 'AdminController@event');
Route::get('admin/event/{eventId}/attendees', 'AdminController@eventAttendees')->name('viewEventAttendees');




Route::get('profile', 'ClientController@getProfile');
Route::get('event', 'ClientController@events');
Route::get('event/{eventId}', 'ClientController@event')->name('viewEvent');

Route::post('profile/update/{profileId}/personal', 'ClientController@postProfilePersonal')->name('profile/update/personal');
Route::post('profile/update/{profileId}/contact', 'ClientController@postProfileContact')->name('profile/update/contact');
Route::post('profile/update/{profileId}/beneficiary', 'ClientController@postProfileBeneficiary')->name('profile/update/beneficiary');

Route::get('test', function(){
	return Session::get('username');
});