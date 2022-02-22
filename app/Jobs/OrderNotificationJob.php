<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderEmail;
use Mail;
use Illuminate\Contracts\Mail\Mailer;

class OrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('emails.order_notification',  ['data'=> $this->data], function ($message) {
            $message->from('ibcs_primax_ecommerce@gmail.com', 'IBCS-PRIMAX ECOMMERCE');
            $message->subject('IBCS-PRIMAX ECOMMERCE');
            $message->to('rabiul.fci@gmail.com');
        });
    }
}
