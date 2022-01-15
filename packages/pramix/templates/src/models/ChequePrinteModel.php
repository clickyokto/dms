<?php

namespace Pramix\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pramix\XPayment\Models\ChequeModel;
use Pramix\XPurchase_Order\Models\PurchaseOrderModel;
use Pramix\XGeneral\Models\AddressModel;
use PDF;

class ChequePrinteModel extends Model
{
    public static function printCheque($cheque_id)
    {
        $cheque = ChequeModel::where('id', $cheque_id)->first();
        $customPaper = array(0,0,501.7323,252.283);
        $pdf = PDF::loadView('templates::print_cheque_view', array('cheque' => $cheque))->setPaper($customPaper);

        $path = 'reports/cheques';

        if (!file_exists($path)) {
            mkdir($path, 666, true);
        }
        $path = $path.'/cheque_'.$cheque_id.'_'.str_replace('-','_',Carbon::now()).'.pdf';
        $path = str_replace(':','_',$path);

        if($pdf->save($path))
        {
                return asset($path);
        }

    }
}
