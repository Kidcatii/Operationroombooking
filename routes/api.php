<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});

Route::get('/buildings', 'Api\BuildingController@index');
Route::get('/buildings/{id}','Api\BuildingController@show');
Route::post('/buildings','Api\BuildingController@store');
Route::delete('/buildings/{id}','Api\BuildingController@destroy');

Route::get('/rooms', 'Api\RoomController@index');
Route::get('/rooms/{id}', 'Api\RoomController@show');
Route::post('/rooms', 'Api\RoomController@store');
Route::put('/rooms/{id}', 'Api\RoomController@update');
Route::put('/rooms/book/{id}', 'Api\RoomController@click');
Route::delete('/rooms/{id}','Api\RoomController@destroy');

Route::get('/bookings', 'Api\BookingController@index');
Route::get('/bookings/{id}', 'Api\BookingController@show');
Route::post('/bookings', 'Api\BookingController@store');
Route::put('/bookings/{id}', 'Api\BookingController@confirmation');



Route::post('/buildings', 'Api\BuildingController@store');
Route::put('/buildings/{id}', 'Api\BuildingController@update');



Route::get('/subject', 'Api\SubjectController@index');
Route::get('/subject/{id}', 'Api\SubjectController@show');
Route::post('/subject', 'Api\SubjectController@store');
Route::put('/subject/{id}', 'Api\SubjectController@update');
Route::delete('/subject/{id}','Api\SubjectController@destroy');

Route::get('/joingroups', 'Api\JoingroupController@index');
Route::get('/joingroups/{id}', 'Api\JoingroupController@callgroup');
Route::post('/joingroups', 'Api\JoingroupController@store');


Route::get('/users', 'Api\BookingController@user');

Route::get('send-mail/{id}', 'MailController@sendMail');
Route::get('cancel-mail/{id}', 'MailCancel@cancel');