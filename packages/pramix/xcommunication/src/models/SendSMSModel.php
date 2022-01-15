<?php

namespace Pramix\XCommunication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pramix\XCandidate\Models\CandidateModel;
use \Pramix\XCustomer\Models\CustomerModel;
use Carbon\Carbon;
use Pramix\XUser\Models\User;
use Illuminate\Support\Facades\Log;

class SendSMSModel extends Model
{

    protected $table = 'send_sms';
    protected $primaryKey = 'id';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $userid = 0;
            if (isset(auth()->user()->id)) {
                $userid = auth()->user()->id;
            }

            $model->created_by = $userid;
            $model->updated_by = $userid;
        });

        static::updating(function ($model) {
            $userid = 0;
            if (isset(auth()->user()->id)) {
                $userid = auth()->user()->id;
            }

            $model->updated_by = $userid;
        });

        static::deleting(function ($model) {

        });
    }

    public static function replaceShortcodes($candidate_id, $content)
    {
        $shortcodes = config('xcommunication.shortcodes');

        $candidates = CandidateModel::find($candidate_id);

        if ($candidates == NULL)
            return $content;

        $resultString = $content;

        foreach ($shortcodes as $key => $value) {

            $resultString = str_replace($key, $candidates->$value, $resultString);
        }

        return $resultString;
    }

    public static function SendSMSToAllCustomers($sms_details, $send_time)
    {
        $customers = CustomerModel::all();


        if ($send_time == NULL) {
            $send_time = Carbon::now();
        }

        foreach ($customers as $customer) {

            if ($customer->mobile == '' || $customer->mobile == NULL)
                continue;

            $message = $sms_details['message'];
            $message = static::replaceShortcodes($customer->id, $message);


            $sendsms = new SendSMSModel;
            $sendsms->recipient_phone_no = $customer->mobile;
            $sendsms->candidate_id = $customer->id;
            $sendsms->send_time = $send_time;
            $sendsms->message = $message;
            $sendsms->status = 0;
            $sendsms->type_id = 0;
            $sendsms->save();
        }

        static::sendSMS();
    }

    public static function SendSMSToOtherNumber($sms_details, $send_time)
    {

        $other_numbers = explode(',', $sms_details['other_numbers']);

        if ($send_time == 'now') {
            $send_time = Carbon::now();
        }

        foreach ($other_numbers as $other_number) {
            if ($other_number == '' || $other_number == NULL)
                continue;


            $message = $sms_details['message'];


            $sendsms = new SendSMSModel;
            $sendsms->recipient_phone_no = $other_number;
            $sendsms->send_time = $send_time;
            $sendsms->message = $message;
            $sendsms->status = 0;
            $sendsms->type_id = 0;
            $sendsms->save();
        }

        static::sendSMS();
    }

    public static function SendSMSToSelectedCustomers($sms_details, $send_candidates, $send_time)
    {


        if ($send_time == 'now') {
            $send_time = Carbon::now();
        }

        foreach ($send_candidates as $candidate_id) {

            $candidate = CandidateModel::find($candidate_id);

            if ($candidate == NULL)
                continue;

            if ($candidate->mobile_no1 == '' || $candidate->mobile_no1 == NULL)
                continue;


            $message = $sms_details['message'];
            $message = static::replaceShortcodes($candidate_id, $message);


            $sendsms = new SendSMSModel;
            $sendsms->recipient_phone_no = $candidate->mobile_no1;
            $sendsms->candidate_id = $candidate->id;
            $sendsms->send_time = $send_time;
            $sendsms->message = $message;
            $sendsms->status = 0;
            $sendsms->type_id = 0;
            $sendsms->save();
        }

        static::sendSMS();
    }

    public function customer()
    {
        return $this->hasOne('Pramix\XCustomer\Models\CustomerModel', 'id', 'customer_id');
    }

    public function send_by()
    {
        return $this->hasOne('Pramix\XUser\Models\User', 'id', 'created_by');
    }

    public static function sendSystemSMS($template_name = NULL, $candidate_id, $msg = NULL)
    {

        $template = NULL;

        if ($template_name != NULL)
            $template = TemplateModel::where('template_name', $template_name)->first();

        if ($template == NULL && $msg == NULL)
            return false;

        $candidate = CandidateModel::find($candidate_id);

        if ($candidate == NULL)
            return false;

        if ($candidate->mobile_no1 == '' || $candidate->mobile_no1 == NULL)
            return false;

        if ($template != NULL)
            $message = static::replaceShortcodes($candidate_id, $template->content);
        elseif ($msg != NULL)
            $message = static::replaceShortcodes($candidate_id, $msg);

        $sendsms = new SendSMSModel;
        $sendsms->recipient_phone_no = $candidate->mobile_no1;
        $sendsms->candidate_id = $candidate->id;
        $sendsms->send_time = Carbon::now();
        $sendsms->message = $message;
        $sendsms->status = 0;
        $sendsms->type_id = 0;
        $sendsms->save();

        static::sendSMS();
    }


    public static function SendSMSToStaff($template_name, $user_id)
    {

        $template = NULL;

        if ($template_name != NULL)
            $template = TemplateModel::where('template_name', $template_name)->first();

        if ($template == NULL)
            return false;

        $user = User::find($user_id);

        if ($user == NULL)
            return false;

        if ($user->telephone == '' || $user->telephone == NULL)
            return false;

        $sendsms = new SendSMSModel;
        $sendsms->recipient_phone_no = $user->telephone;
        $sendsms->send_time = Carbon::now();
        $sendsms->message = $template->content;
        $sendsms->status = 0;
        $sendsms->type_id = 0;
        $sendsms->save();


        static::sendSMS();
    }


    public static function sendSMS()
    {
        $pending_sms = static::where('status', 0)->where('send_time', '<=', Carbon::now())->get();
        foreach ($pending_sms as $sms) {

            $sms->message = static::replaceShortcodes($sms->candidate_id, $sms->message);

            if ($sms->recipient_phone_no == '' || $sms->recipient_phone_no == NULL)
                continue;


            $response = SMSAPIModel::sendSMS($sms->recipient_phone_no, $sms->message);
            Log::info($response);

            if ($response == 'true') {
                $sms->status = 1;
                $sms->save();
            }
        }
        SMSAPIModel::forceToSendSMSImmediately();
    }








}
