<?php

namespace Pramix\XCommunication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Pramix\XCustomer\Models\CustomerModel;
use Carbon\Carbon;
use Mail;
use App\Mail\CustomEmails;

class EmailModel extends Model {

    protected $table = 'emails';
    protected $primaryKey = 'id';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();

        static::creating(function($model) {
            $userid = 0;
            if (isset(auth()->user()->id)) {
                $userid = auth()->user()->id;
            }

            $model->created_by = $userid;
            $model->updated_by = $userid;
        });

        static::updating(function($model) {

            $userid = 0;
            if (isset(auth()->user()->id)) {
                $userid = auth()->user()->id;
            }
            $model->updated_by = $userid;
        });

        static::deleting(function($model) {

        });
    }

    public static function replaceShortcodes($customer_id, $content) {
        $shortcodes = config('xcommunication.shortcodes');

        $customer = CustomerModel::find($customer_id);

        if ($customer == NULL)
            return $content;

        $resultString = $content;

        foreach ($shortcodes as $key => $value) {

            $resultString = str_replace($key, $customer->$value, $resultString);
        }

        return $resultString;
    }

    public static function SendEmailToAllCustomers($send_time, $template_id) {
        $customers = CustomerModel::all();


        if ($send_time == NULL) {
            $send_time = Carbon::now();
        }
         else
        {
             $send_time =  Carbon::parse($send_time);
        }

       $send_time = $send_time->addSeconds(30);

        foreach ($customers as $customer) {

            if ($customer->email == '' || $customer->email == NULL)
                continue;

            $template = EmailTemplatesModel::find($template_id);
            $message = $template->content;
            $message = static::replaceShortcodes($customer->id, $message);


            $sendemail = new EmailModel;
            $sendemail->recipient_email = $customer->email;
            $sendemail->customer_id = $customer->id;
            $sendemail->send_time = $send_time;
            $sendemail->status = 0;
            $sendemail->template_id = $template_id;
            $sendemail->save();
        }
        static::sendEmails();
    }

    public static function SendEmailToOtherEmail($email_details, $send_time, $template_id) {

        $other_emails = explode(',', $email_details['other_emails']);

        if ($send_time == NULL) {
            $send_time = Carbon::now();
        }

        foreach ($other_emails as $other_email) {
            if ($other_email == '' || $other_email == NULL)
                continue;


            $template = EmailTemplatesModel::find($template_id);
            $message = $template->content;



            $sendemail = new EmailModel;
            $sendemail->recipient_email = $other_email;
            $sendemail->send_time = $send_time;
            $sendemail->status = 0;
            $sendemail->template_id = $template_id;
            $sendemail->save();
        }

        static::sendEmails();
    }

    public static function SendEmailToSelectedCustomers($send_customers, $send_time, $template_id) {



        if ($send_time == NULL) {
            $send_time = Carbon::now();
        }
        else
        {
             $send_time =  Carbon::parse($send_time);
        }

        $send_time = $send_time->addSeconds(30);

        foreach ($send_customers as $customer_id) {


            $customer = CustomerModel::find($customer_id);

            if ($customer == NULL)
                continue;

            if ($customer->email == '' || $customer->email == NULL)
                continue;


            $template = EmailTemplatesModel::find($template_id);
            $message = $template->content;

            $message = static::replaceShortcodes($customer_id, $message);



            $sendemail = new EmailModel;
            $sendemail->recipient_email = $customer->email;
            $sendemail->customer_id = $customer->id;
            $sendemail->send_time = $send_time;
            $sendemail->status = 0;
            $sendemail->template_id = $template_id;
            $sendemail->save();
        }
        static::sendEmails();
    }

    public function customer() {
        return $this->hasOne('Pramix\XCustomer\Models\CustomerModel', 'id', 'customer_id');
    }

    public function send_by() {
        return $this->hasOne('Pramix\XUser\Models\User', 'id', 'created_by');
    }

    public static function sendEmails() {

        $pending_emails = EmailModel::where('status', 0)->where('send_time', '<=', Carbon::now())->get();

        foreach ($pending_emails as $email) {

            Mail::send(new CustomEmails($email));



            $email->status = 1;
            $email->save();
        }





        if (Mail::failures()) {
            return response()->json(['status' => 'error', 'msg' => 'Error']);
        } else {
            return response()->json(['status' => 'success', 'msg' => 'Email Sent']);
        }
    }

}
