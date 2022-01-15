<?php
/**
 * Created by PhpStorm.
 * User: PRAMIX
 * Date: 9/26/2019
 * Time: 10:03 AM
 */

function formText($label, $name, $value = '', $parameters_array = [])
{
    return view('xcodegenerator::forms.text_format')
        ->with('label', $label)
        ->with('name', $name)
        ->with('value', $value)
        ->with('parameters_array', $parameters_array);
}


function formPassword($label, $name, $value = '', $parameters_array = [])
{
    return view('xcodegenerator::forms.password_format')
        ->with('label', $label)
        ->with('name', $name)
        ->with('value', $value)
        ->with('parameters_array', $parameters_array);
}

function formEmail($label, $name, $value = '', $parameters_array = [])
{
    return view('xcodegenerator::forms.email_format')
        ->with('label', $label)
        ->with('name', $name)
        ->with('value', $value)
        ->with('parameters_array', $parameters_array);
}

function formNumber($label, $name, $value = '', $parameters_array = [])
{
    return view('xcodegenerator::forms.number_format')
        ->with('label', $label)
        ->with('name', $name)
        ->with('value', $value)
        ->with('parameters_array', $parameters_array);
}

function formTextArea($label, $name, $value = '', $parameters_array = [])
{
    return view('xcodegenerator::forms.textarea_format')
        ->with('label', $label)
        ->with('name', $name)
        ->with('value', $value)
        ->with('parameters_array', $parameters_array);
}

function formDate($label, $name, $value = '', $parameters_array = [])
{
    return view('xcodegenerator::forms.date_format')
        ->with('label', $label)
        ->with('name', $name)
        ->with('value', $value)
        ->with('parameters_array', $parameters_array);
}

function formDateTime($label, $name, $value = '', $parameters_array = [])
{
    return view('xcodegenerator::forms.date_time_format')
        ->with('label', $label)
        ->with('name', $name)
        ->with('value', $value)
        ->with('parameters_array', $parameters_array);
}


function formDropdown($label, $name,$options_array = [], $value = '', $parameters_array = [])
{
    return view('xcodegenerator::forms.dropdown_format')
        ->with('label', $label)
        ->with('name', $name)
        ->with('value', $value)
        ->with('options_array', $options_array)
        ->with('parameters_array', $parameters_array);
}
