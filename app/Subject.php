<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'subject', 'day', 'start','end','room_id','timestart','timeend'
    ];
}
