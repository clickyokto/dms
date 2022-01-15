<?php

namespace Pramix\XEmailSender\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailSupport extends Mailable
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
        $sendemail = $this->view('xemail_sender::dynamic_email_template')->with('data', $this->data);

        $sendemail->to($this->data->email);
        $sendemail->from('sonickolla94@gmail.com', getConfigArrayValueByKey('COMPANY_DETAILS', 'company_name'));
        $sendemail->subject($this->data->subject);


        if ($this->data['attachments'] != NULL) {
            foreach ($this->data['attachments'] as $attachment) {
                $sendemail->attach($attachment);
            }
        }
        return $sendemail;
    }
}
