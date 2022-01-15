<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use PDF;
use Illuminate\Database\Eloquent\Model;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XPurchaseOrder\Models\PurchaseOrderModel;
class GeneratePurchaseOrderModel extends Model
{
    public static function generatePurchaseOrder($purchase_order_id)
    {

        $purchase_order = PurchaseOrderModel::with('supplier')->with('purchase_orderProducts')->where('id' ,$purchase_order_id)->first();
        $business_address = AddressModel::getAddress(($purchase_order['supplier_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'supplier'));
        $shipping_address = AddressModel::getAddress(($purchase_order['supplier_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'supplier'));
        $pdf = PDF::loadView('templates::generate_purchase_order', array('purchase_order' => $purchase_order , 'supplier' => $purchase_order->supplier , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'purchase_order_products' => $purchase_order->purchase_orderProducts));

        $path = 'reports/purchase_orders';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/purchase_order_'.$purchase_order_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
        $path = str_replace(':','_',$path);


        if($pdf->save($path))
        {
            return asset($path);
        }
    }
}
