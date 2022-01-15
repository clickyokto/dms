<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XJobCard\Models\JobCardModel;
use PDF;

class GenerateJobCardModel extends Model
{
    public static function generateJobCard($job_card_id)
    {
        $job_card = JobCardModel::with('customer')->with('vehicle')->with('job_card_products')->where('id' ,$job_card_id)->first();

       if(!empty($job_card->vehicle))
        $job_card->vehicle= '';
       $business_address = AddressModel::getAddress(($job_card['customer_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $shipping_address = AddressModel::getAddress(($job_card['customer_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $pdf = PDF::loadView('templates::generate_job_card', array('job_card' => $job_card , 'customer' => $job_card->customer , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'job_card_products' => $job_card->job_card_products , 'vehicle' => $job_card->vehicle));


        $path = 'reports/job_cards';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/job_card_'.$job_card_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
        $path = str_replace(':','_',$path);


        if($pdf->save($path))
        {
            return asset($path);
        }
    }
}
