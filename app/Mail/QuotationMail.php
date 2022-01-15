<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {

       $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if (isset($this->data['path']))
            return $this->from('sonickolla94@gmail.com')->subject('New Testing mail')->view('xinvoice::dynamic_email_template')->attach($this->data['path'])->with('data', $this->data);
        else
            return $this->from('sonickolla94@gmail.com')->subject('New Testing mail')->view('xinvoice::dynamic_email_template')->with('data', $this->data);
  }
}
