<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XDeliveryNote\Models\DeliveryNoteModel;
use Pramix\XGeneral\Models\AddressModel;
use Pramix\XInvoice\Models\InvoiceModel;
use PDF;

class GenerateDeliveryNoteModel extends Model
{
    public static function generateDeliveryNote($delivery_note_id, $public_path= false)
    {

        $delivery_note = DeliveryNoteModel::with('customer')->with('user')->with('delivery_products')->where('id' ,$delivery_note_id)->first();
        $business_address = AddressModel::getAddress(($delivery_note['customer_id']),'B' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $shipping_address = AddressModel::getAddress(($delivery_note['customer_id']),'S' ,getConfigArrayValueByKey('ADDRESS_USER_TYPE',  'customer'));
        $pdf = PDF::loadView('templates::generate_delivery_note', array('delivery_note' => $delivery_note , 'customer' => $delivery_note->customer , 'business_address' => $business_address , 'shipping_address' => $shipping_address , 'delivery_products' => $delivery_note->delivery_products))->setPaper('a4');


        $path = 'reports/invoices';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/delivery_note'.$delivery_note_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
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
