<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XAOD\Models\AODModel;
use PDF;

class GenerateAODModel extends Model
{
    public static function generateAOD($aod_id)
    {
        $aod = AODModel::with('customer')->with('user')->with('staff_member')->with('aodProducts')->where('id' ,$aod_id)->first();
        $business_address = AddressModel::getAddress(($aod['customer_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $shipping_address = AddressModel::getAddress(($aod['customer_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $pdf = PDF::loadView('templates::generate_aod', array('aod' => $aod , 'customer' => $aod->customer , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'aodproducts' => $aod->aodProducts));


        $path = 'reports/aods';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/aod_'.$aod_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
        $path = str_replace(':','_',$path);


        if($pdf->save($path))
        {
            return asset($path);
        }
    }
}
