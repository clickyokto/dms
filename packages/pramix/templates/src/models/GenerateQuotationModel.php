<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XQuotation\Models\QuotationModel;
use Pramix\XGeneral\Models\AddressModel;
use PDF;


class GenerateQuotationModel extends Model
{
    public static function generateQuotation($quoation_id, $public_path=false)
    {

        $quotation = QuotationModel::with('customer')->with('quotationProducts')->where('id', $quoation_id)->first();

        $business_address = AddressModel::getAddress(($quotation['customer_id']), 'B', getConfigArrayValueByKey('ADDRESS_USER_TYPE', 'customer'));
        $shipping_address = AddressModel::getAddress(($quotation['customer_id']), 'S', getConfigArrayValueByKey('ADDRESS_USER_TYPE', 'customer'));


        $pdf = PDF::loadView('templates::generate_quotation', array('quotation' => $quotation , 'customer' => $quotation->customer , 'business_address' => $business_address , 'quotationProducts' => $quotation->quotationProducts));

        $path = 'reports/quotations';
        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/quotations_'.$quoation_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
        $path = str_replace(':','_',$path);

        if($pdf->save($path))
        {

            if ($public_path)
                return public_path($path);
            else
                return asset($path);
        }

    }
}
