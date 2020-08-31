<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Building;

class Room extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'image1', 'image2', 'image3', 'room_name', 'building_id','book'
    ];
    public function building($building_id)
    {
        //return $this->hasOne(Building::class,'id');   
       // return $this->belongsTo(Building::class,'id'); 
       //return DB::select('select * from rooms where building_id = ?', $building_id);
       return $this->belongsTo('App\Building');
    }
}
