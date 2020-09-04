<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Joingroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joingroups', function (Blueprint $table) {
            $table->integer('booking_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->dateTime('timein');
            
            $table->primary(['booking_id', 'user_id']);
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        /*Schema::table('joingroups', function (Blueprint $table) {
            
        });*/
    }

    
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
