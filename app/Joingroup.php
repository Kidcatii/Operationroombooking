<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Notifications\Notifiable;
use App\User;
use App\Booking;

class Joingroup extends Model
{
    //use Notifiable;
    protected $primaryKey = ['booking_id' ,'user_id'];
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timein'
    ];
    /*public function booking($booking_id)
    {
        //return $this->hasOne(Building::class,'id');   
       // return $this->belongsTo(Building::class,'id'); 
       //return DB::select('select * from rooms where building_id = ?', $building_id);
       return $this->belongsTo('App\Booking');
    }
    public function User($user_id)
    {
        //return $this->hasOne(Building::class,'id');   
       // return $this->belongsTo(Building::class,'id'); 
       //return DB::select('select * from rooms where building_id = ?', $building_id);
       return $this->belongsTo('App\User');
    }*/
}
 