<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XInvoice\Models\InvoiceModel;
use PDF;

class GenerateInvoiceModel extends Model
{
    public static function generateInvoice($invoice_id, $public_path= false)
    {

        $invoice = InvoiceModel::with('customer')->with('user')->with('invoiceProducts')->where('id' ,$invoice_id)->first();
        $business_address = AddressModel::getAddress(($invoice['customer_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $shipping_address = AddressModel::getAddress(($invoice['customer_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));



        $pdf = PDF::loadView('templates::generate_invoice', array('invoice' => $invoice , 'customer' => $invoice->customer , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'invoiceproducts' => $invoice->invoiceProducts))->setPaper('a5', 'landscape');


        $path = 'reports/invoices';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/invoice_'.$invoice_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
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
