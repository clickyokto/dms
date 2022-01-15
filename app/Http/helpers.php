<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http;
use App\Models\Options;

/**
 * Description of helpers
 *
 * @author Praveen
 */
class Helper {

    public static function formatPrice($price) {
        return getConfig('DEFAULT_CURRENCY') .  number_format($price, 2);
    }
    public static function formatNumber($price) {
        return number_format($price, 2);
    }

    public static function formatTitle($title){

      $company_name=  Options::get_option('company_name');
        return $title .'-'. $company_name ;
    }

}
