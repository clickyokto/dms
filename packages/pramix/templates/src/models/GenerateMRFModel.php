<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XMRF\Models\MRFModel;
use PDF;

class GenerateMRFModel extends Model
{
    public static function generateMRF($mrf_id)
    {
        $mrf = MRFModel::with('customer')->with('mrfProducts')->where('id' ,$mrf_id)->first();
        $business_address = AddressModel::getAddress(($mrf['customer_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $shipping_address = AddressModel::getAddress(($mrf['customer_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $pdf = PDf::loadView('templates::generate_mrf', array('mrf' => $mrf , 'customer' => $mrf->customer , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'mrf_products' => $mrf->mrfProducts));
        $path = 'reports/mrf';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/mrf_'.$mrf_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
        $path = str_replace(':','_',$path);


        if($pdf->save($path))
        {
            return asset($path);
        }
    }
}
