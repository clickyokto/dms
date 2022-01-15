<?php

return[
    "shortcodes" => array(
        "[[REG_NO]]" => "candidate_code",
        "[[FAMILY_NAME]]" => "family_name",
        "[[GIVEN_NAME]]" => "given_name",

    ),
    'sms_api_url' => 'http://ebulksen.wwwsg1-sr2.supercp.com',
    'option_names' => array(
        'appointment_sms' => 'appointment_sms',
        'sms_api_key' => 'sms_api_key',
        'sms_api_secret' => 'sms_api_secret',
    ),
    'media_array' => array(
        'email_uploads' => array(
            'type' => 'email_upload_pictuer',
            'folder_name' => 'email_upload_pictuer'
        )
    ),
    "system_messages" => array(
        'CUSTOMER_REGISTER_WELCOME_SMS' => 'CUSTOMER_REGISTER_WELCOME_SMS',
        'CUSTOMER_BIRTHDAY_MESSAGE' => 'CUSTOMER_BIRTHDAY_MESSAGE',
        'COMPLAINT_REGISTERED_BY_CLIENT_SMS' => 'COMPLAINT_REGISTERED_BY_CLIENT_SMS',
        'SMS_QUOTA_RUNNING_LOW_MESSAGE' => 'SMS_QUOTA_RUNNING_LOW_MESSAGE',
        'NEW_COMPLAINT_ALERT_FOR_STAFF' => 'NEW_COMPLAINT_ALERT_FOR_STAFF',
        'PAYMENT_RECIVED_SMS' => 'PAYMENT_RECIVED_SMS',

        'PANEL_INSTALLATION_STATUS_CHANGE' => 'PANEL_INSTALLATION_STATUS_CHANGE',
        'INVERTER_INSTALLATION_STATUS_CHANGE' => 'INVERTER_INSTALLATION_STATUS_CHANGE',
        'APPLICATION_SUBMITION_STATUS_CHANGE' => 'APPLICATION_SUBMITION_STATUS_CHANGE',
        'CEB_HARMONIC_TEST' => 'CEB_HARMONIC_TEST',
        'CEB_INSPECTION' => 'CEB_INSPECTION',
        'ESTIMATE' => 'ESTIMATE',
        'NET_METER_FIX' => 'NET_METER_FIX',
         'WARRANTY_ISSUED' => 'WARRANTY_ISSUED',

    ),
];
?>