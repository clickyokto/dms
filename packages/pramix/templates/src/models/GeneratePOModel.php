<?php

namespace Pramix\Templates\Models;

use Illuminate\Database\Eloquent\Model;
use Pramix\XPurchase_Order\Models\PurchaseOrderModel;
use Pramix\XGeneral\Models\AddressModel;
use PDF;

class GeneratePOModel extends Model
{
    public static function generatePurchaseOrder($po_id)
    {
        $purchase_order = PurchaseOrderModel::with('supplier')->with('purchaseOrderProducts')->where('id', $po_id)->first();
        $business_address = AddressModel::getAddress(($purchase_order['suppiler_id']), 'B', getConfigArrayValueByKey('ADDRESS_USER_TYPE', 'supplier'));
        $shipping_address = AddressModel::getAddress(($purchase_order['suppiler_id']), 'S', getConfigArrayValueByKey('ADDRESS_USER_TYPE', 'supplier'));
        $pdf = PDF::loadView('templates::generate_purchase_order', array('purchase_order' => $purchase_order , 'supplier' => $purchase_order->supplier , 'business_address' => $business_address , 'purchase_order_products' => $purchase_order->purchaseOrderProducts));
        $reportname= rand();
        if($pdf->save('reports/purchase_orders/report'.$reportname.'_.pdf'))
        {
            return asset('reports/purchase_orders/report'.$reportname.'_.pdf');
        }
    }
}
