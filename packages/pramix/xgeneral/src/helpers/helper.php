<?php
/**
 * Created by PhpStorm.
 * User: PRAMIX
 * Date: 9/26/2019
 * Time: 10:03 AM
 */

function getLogo($array = [])
{

    $media_type = getConfigArrayValueByKey('MEDIA_TYPES','company_logo');

    $media = \Pramix\XMedia\Models\MediaModel::where('media_type',$media_type)->latest()->first();

    if($media == NULL)
        return '<h3>'.getConfigArrayValueByKey('COMPANY_DETAILS','company_name').'</h3>';

    $path = 'uploads/'.$media->folder_name.'/'.$media->file_name;

    return view('xgeneral::logo.logo')
        ->with('array',$array)
        ->with('logo_path', $path);

}


