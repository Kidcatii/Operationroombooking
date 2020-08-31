<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
class Cancelmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $demo;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo)
    {
        $this->demo = $demo;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
                ->from('btest5055@gmail.com') // Sender mail
                ->subject('ไม่อนุมัติ') // Mail subject
                ->view('mail.cancel') // View file resource/views/mail/index
                ->with(
                      [
                            'testVarOne' => '1',
                            'testVarTwo' => '2',
                      ])
                     /* ->attach(public_path('/images').'/demo.jpg', [
                              'as' => 'demo.jpg',
                              'mime' => 'image/jpeg',
                      ]);*/
    
                ; 
    }
}