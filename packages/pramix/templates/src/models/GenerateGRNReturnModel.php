<?php

namespace Pramix\Templates\Models;

use Illuminate\Database\Eloquent\Model;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XGRN\Models\GRNReturnModel;
use Pramix\XPurchase_Order\Models\POReturnModel;
use PDF;

class GenerateGRNReturnModel extends Model
{
    public static function generateGRNReturn($po_return_id)
    {

        $po_return = GRNReturnModel::with('supplier')->with('poReturnProducts')->where('id' ,$po_return_id)->first();

        $business_address = AddressModel::getAddress(($po_return['supplier_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'supplier'));
        $shipping_address = AddressModel::getAddress(($po_return['supplier_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'supplier'));
        $pdf = PDF::loadView('templates::generate_po_return', array('po_return' => $po_return , 'supplier' => $po_return->supplier , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'po_return_products' => $po_return->poReturnProducts));
        $reportname= rand();
        if($pdf->save('reports/purchase_orders/report'.$reportname.'_.pdf'))
        {
            return asset('reports/purchase_orders/report'.$reportname.'_.pdf');
        }
    }
}
