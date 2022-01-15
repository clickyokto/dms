<?php

return [
    "templates" => array(
        "solar_quotation_template" => 1,
        "solar_warranty_template" => 4,
        "solar_agreement_template" => 3,
        "solar_cover_template" => 2
    ),
    "shortcodes" => array(
        "GREENEE_SOLAR_QUOTATION" => array(
            "shortcodes" => array(

            ),
              "functions" => array(
                "[[SOLARQUOTATION]]" => "generateSolarQuotation",
            ),

    ),
    "SOLAR_COVER" => array(
            "shortcodes" => array(

            ),
            "functions" => array(
                "[[SOLARCOVER]]" => "generateSolarCover",
            ),
        ),
        "SOLAR_AGREEMENT" => array(
            "shortcodes" => array(
                "[[REF_NO]]" => "invoice_no",
                "[[PRICE]]" => "total",
            ),
            "functions" => array(
                "[[SYSTEM_CAPACITY]]" => "getCapacity",
                "[[DELIVERABLES]]" => "getDescription",
                "[[CUSTOMER_FULL_NAME]]" => "getCustomerFullName",
                "[[AVERAGE_MONTHLY_UNIT_PRODUCTION]]" => "getAverageUnit",
                "[[START_DATE]]" => "getJobStartDate",
                "[[END_DATE]]" => "getJobEndDate",
            ),
        ),
        "SOLAR_WARRANTY" => array(
            "shortcodes" => array(
            ),
            "functions" => array(
                "[[INVERTER_BRAND]]" => "getInverterBrand",
                "[[NO_OF_INVERTERS]]" => "getNoOfInverters",
                "[[INVERTOR_MODEL_NO]]" => "getInvertorModelNo",
                "[[INVERTOR_MODEL_NO_2]]" => "getInvertor2ModelNo",
                "[[INVERTOR_SNS]]" => "getInvertorSNs",
                "[[PANEL_BRAND]]" => "getPanelBrand",
                "[[NO_OF_PANEL]]" => "getNoOfPanels",
                "[[PANEL_MODEL_NO]]" => "getPanelModelNo",
                "[[PANEL_SNS]]" => "getPanelSNs",
                "[[PRODUCT_WARRENTY]]" => "getProductWarranty",
                "[[PROFORMENCR_WARRENTY]]" => "getPerformanceWarranty",
                "[[START_DATE]]" => "getJobStartDate",
                "[[CUSTOMER_USER_NAME]]" => "getCustomerUserName",
                "[[CUSTOMER_USER_EMAIL]]" => "getCustomerUserEmail",
                "[[CUSTOMER_USER_PASSWORD]]" => "getCustomerUserPass",
                "[[CUSTOMER_PORTAL_URL]]" => "getCustomerPortalURL",
                "[[SOLARCOVER]]" => "generateSolarCover",
            ),
        ),
    ),
        ]
?>