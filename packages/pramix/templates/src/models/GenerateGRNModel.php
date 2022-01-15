<?php

namespace Pramix\Templates\Models;

use PDF;
use Illuminate\Database\Eloquent\Model;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XGRN\Models\GRNModel;
class GenerateGRNModel extends Model
{
    public static function generateGRN($grn_id)
    {
        $grn = GRNModel::with('supplier')->with('grnProducts')->where('id' ,$grn_id)->first();
        $business_address = AddressModel::getAddress(($grn['supplier_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'supplier'));
        $shipping_address = AddressModel::getAddress(($grn['supplier_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'supplier'));
        $pdf = PDF::loadView('templates::generate_grn', array('grn' => $grn , 'supplier' => $grn->supplier , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'grn_products' => $grn->grnProducts));
        $reportname= rand();
        if($pdf->save('reports/purchase_orders/report'.$reportname.'_.pdf'))
        {
            return asset('reports/purchase_orders/report'.$reportname.'_.pdf');
        }
    }
}
