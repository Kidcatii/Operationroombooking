<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Cancelmail;
use App\User;
use App\Booking;
use App\Room;
class MailCancel extends Controller
{
    /**
     * Send mail function
     *
     */
    public function cancel($id)
    {
        
        $book = Booking::find($id);
        
        $obj = new \stdClass();
        $username = User::select('name')->where('id', $book->user_id)->get()->pluck('name');
        //$usernameone = explode(" ",$username, 2);
        $obj->id = $book->id;
        $obj->users = $username;
        
        $obj->room = Room::select('room_name')->where('id', $book->room_id)->get()->pluck('room_name');
        $obj->day = date('d-m-Y', strtotime($book->start));
        $obj->start =  date('H:i', strtotime($book->start));
        $obj->end = date('H:i', strtotime($book->end));
        
        //$obj->
        $users = User::select('email')->where('id', $book->user_id)->get();
        //$subset = $users->map->only(['email']);
    	Mail::to($users)
    		//->cc('cc-example@gmail.com')
    		//->bcc('bcc-example@gmail.com')
    		->send(new Cancelmail($obj));
	    if (Mail::failures()) {
	        //return with failed message
	    }
	    // return with success message
    }

}

