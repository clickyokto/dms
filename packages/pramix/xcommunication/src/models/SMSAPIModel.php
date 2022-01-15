<?php

namespace Pramix\XCommunication\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Pramix\XConfig\Models\XConfig;
use Pramix\XOptions\Models\OptionsModel;

class SMSAPIModel extends Model {




    public static function sendSMS($phone_no, $msg) {


      //  if (OptionsModel::get_option(config('options.option_names.appointment_sms'))) {

            $sendstring = base64_encode("api_key=" . base64_encode(base64_encode(XConfig::get('SMS_CONFIG')->sms_api_key)) . "&api_secret=" .XConfig::get('SMS_CONFIG')->sms_api_secret . "&phone_no=" . base64_encode($phone_no) . "&template=0&message=" . base64_encode($msg));
            $sendurl = config('xcommunication.sms_api_url')."/smsgateway?data=" . $sendstring;

            $response = @file_get_contents($sendurl);

          return $response;

        //}
    }

    public static function forceToSendSMSImmediately() {



        $sendstring = base64_encode("api_key=" . base64_encode(base64_encode(XConfig::get('SMS_CONFIG')->sms_api_key)) . "&api_secret=" .XConfig::get('SMS_CONFIG')->sms_api_secret );
        $sendurl = config('xcommunication.sms_api_url')."/smsgatewaysendsms?data=" . $sendstring;
        $response = @file_get_contents($sendurl);

        return $response;

        //}
    }

    public static function smsGatewayGetCreditCount() {

            $sendstring = base64_encode("api_key=" . base64_encode(XConfig::get('SMS_CONFIG')->sms_api_key) . "&api_secret=" . XConfig::get('SMS_CONFIG')->sms_api_secret);
            $sendurl = config('xcommunication.sms_api_url') . "/smsgatewaygetcreditcount?data=" . $sendstring;


            $response = @file_get_contents($sendurl);

            if ($response == false || !is_numeric($response))
                return $no_of_sms = 0;
            else
                return $response;

        return 0;
    }

    public static function validateAPIKeySecret($api_key, $api_secret) {
        $sendstring = base64_encode("api_key=" .base64_encode($api_key) . "&api_secret=" . base64_encode($api_secret));
            $sendurl = config('xcommunication.sms_api_url') . "/smsapisecretvalidate?data=" . $sendstring;

        $response = @file_get_contents($sendurl);

            if ($response == 'TRUE' )
                return TRUE;
            else
                return FALSE;
    }

}
