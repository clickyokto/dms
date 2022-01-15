<?php

namespace Pramix\Templates\Models;

use Illuminate\Database\Eloquent\Model;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XInvoice\Models\InvoiceReturnModel;
use PDF;

class GenerateInvoiceReturnModel extends Model
{
    public static function generateInvoiceReturn($invoice_return_id)
    {
        $invoice_return = InvoiceReturnModel::with('customer')->with('invoiceReturnProducts')->where('id' ,$invoice_return_id)->first();
        $business_address = AddressModel::getAddress(($invoice_return['customer_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $shipping_address = AddressModel::getAddress(($invoice_return['customer_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $pdf = PDF::loadView('templates::generate_invoice_return', array('invoice_return' => $invoice_return , 'customer' => $invoice_return->customer , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'invoice_return_products' => $invoice_return->invoiceReturnProducts));
        $reportname= rand();
        if($pdf->save('reports/invoices/report'.$reportname.'_.pdf'))
        {
            return asset('reports/invoices/report'.$reportname.'_.pdf');
        }
    }
}
