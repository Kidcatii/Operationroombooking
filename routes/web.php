<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');

/*Route::get('send-mail', function () {
   
    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];
   
    \Mail::to('btest5055@gmail.com')->send(new \App\Mail\MyTestMail($details));
   
    dd("Email is Sent.");
});*/

//Route::get('send-mail', 'MailController@sendMail');
Route::get('send-mail/{id}', 'MailController@sendMail');