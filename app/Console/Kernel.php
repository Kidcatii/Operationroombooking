<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //Commands\SendEmails::class, //แก้ไขบรรนี้เพื่อ class command ที่เราได้สร้างขึ้น, กรณีเรามีการสร้าง command หลายๆ command เราก็มาใส่เรียงต่อๆ
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       // $schedule->command('cron:sendemail')->everyMinute(); //แก้ไขบรรนี้เพื่อ เพื่อกำหนดความถี่ ในการทำงานของ command
        //$schedule->command('cron:sendemail')->everyMinute();
        $schedule->command('ch:status')->everyMinute();
        $schedule->command('ch:cancel')->everyMinute();
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
