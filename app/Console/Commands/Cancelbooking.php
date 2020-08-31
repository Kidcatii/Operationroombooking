<?php

namespace App\Console\Commands;

use App\User;
use App\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
class Cancelbooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ch:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel Booking';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        //$booking = Booking::where('status', '0')->get();

        //$booking = Booking::where('status','0')->get();
        $now = Carbon::now()->subMinute(10)->format('Y-m-d H:i:s');
      //  $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $now);
       // $timestamp->setTimezone('+7:00');
        $booking = Booking::where('status','=','0','and','created_at','<',$now)->get();
                //->update(['status' => '2']);

        //$booking = Booking::where('status','0')->get();
        //$time = $booking->created_at;
          // $booking->status = '2';
       // $booking->save();
        //$booking = Booking::where('status', '0')->get();
        //$booking = 'create_at';
        //$create = Booking::select('created_at')->where('status','0')->get();
        //$timestampcreate = Carbon::createFromFormat('Y-m-d H:i:s', $create);
        //$timestamp = Carbon::now();
        //$date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp);
        //$date->setTimezone('+7:00');
        /*if($booking->create_at < Carbon::now()->subMinute(1)){
            $booking->status = '2';
            $booking->save();
        }*/
       // $booking->status = '2';
       // $booking->save();

        $obj = new \stdClass();
        dd($booking);
        
    }
}
