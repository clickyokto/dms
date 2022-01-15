<?php
/**
 * Created by PhpStorm.
 * User: PRAMIX
 * Date: 9/26/2019
 * Time: 10:03 AM
 */

function getConfig($config_name){

    $config = \Pramix\XConfig\Models\XConfig::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL && $config->config_type == 'TX')
        return $config->value;
    else  if ($config!=NULL && $config->config_type == 'DD')
        return json_decode($config->options_array);
    else
        return '';

}

function getConfigValue($config_name){

    $config = \Pramix\XConfig\Models\XConfig::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL)
        return $config->value;
    else
        return '';

}

function getConfigArrayValueByKey($config_name, $array_key)
{
    $config = \Pramix\XConfig\Models\XConfig::where('name', $config_name)->where('status',1)->first();

  if ($config!=NULL && $config->config_type == 'DD' && (isset(json_decode($config->options_array)->$array_key) || isset(json_decode($config->options_array)[$array_key]))){
        return json_decode($config->options_array)->$array_key ?? json_decode($config->options_array)[$array_key];
  }
    else
        return '';
}


function getConfigArrayKeyByValue($config_name, $array_value)
{
    $config = \Pramix\XConfig\Models\XConfig::where('name', $config_name)->where('status',1)->first();
   $options_array= json_decode($config->options_array,true);
    if ($config!=NULL && $config->config_type == 'DD'){

        $key = array_search($array_value,$options_array);
        return $key;
    }
    else
        return '';
}

