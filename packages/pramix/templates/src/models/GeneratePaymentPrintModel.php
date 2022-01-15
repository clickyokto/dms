<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use PDF;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XInvoice\Models\InvoiceModel;
use Pramix\XInvoice\Models\InvoicePaymentModel;

class GeneratePaymentPrintModel extends Model
{

    public static function generatePaymentPrint($payment_id , $invoice_id)
    {
        $payment = InvoicePaymentModel::find($payment_id);
        $invoice = InvoiceModel::with('customer')->where('id' ,$invoice_id)->first();
        $business_address = AddressModel::getAddress(($invoice['customer_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $shipping_address = AddressModel::getAddress(($invoice['customer_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));

        $pdf = PDF::loadView('templates::generate_payment_print', array('payment' => $payment , 'invoice' => $invoice,  'customer' => $invoice->customer,'business_address' => $business_address , 'shipping_address' => $shipping_address));

        $path = 'reports/payments';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/payments_'.$payment_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
        $path = str_replace(':','_',$path);

        if($pdf->save($path))
        {
            return asset($path);
        }
    }
}
